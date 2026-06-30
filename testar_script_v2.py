#!/usr/bin/env python3
import subprocess
import os
import sys
import time
import shutil

# ─────────────────────────────────────────────
#  Configuration
# ─────────────────────────────────────────────

PHP_SUTS = [
    #"addressbook",
    "claroline",
    "collabtive",
    "mantisbt",
    "mrbs",
    "schoolmate",
    "socialnetwork",
    "timeclock",
]

JAVA_SUTS = [
    "parabank",
    "petclinic",
    "petstore",
    "triangle"
]

SUTS = PHP_SUTS + JAVA_SUTS
TOOL = "TESTAR"
TESTAR_MAX_TIME = 7200  # 1 hour per SUT
NUM_RUNS = 10          # Number of full iterations over all SUTs

# project structure:
#
#   <project_root>/                        ← BASE_DIR
#       run_testar.py
#       suts/
#           addressbook/
#               docker-compose.yml
#               coverage/                  ← PHP: report.csv lives here
#           parabank/
#               docker-compose.yml
#               target/classes/            ← Java: compiled .class files
#               src/main/java/             ← Java: source files
#           jacococli.jar                  ← JaCoCo CLI jar
#       tools/
#           TESTAR_dev-2.7.18/
#               testar/
#                   testar/
#                       target/
#                           install/
#                               testar/
#                                   bin/
#                                       testar.bat
#       testar_run1/                       ← output run 1
#       testar_run2/                       ← output run 2
#       ...

BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR    = os.path.join(BASE_DIR, "suts")
TESTAR_BIN  = os.path.join(
    BASE_DIR, "tools", "TESTAR_dev-2.7.18", "testar", "testar",
    "target", "install", "testar", "bin",
)
TESTAR_BAT  = os.path.join(TESTAR_BIN, "testar.bat")
JACOCO_CLI  = os.path.join(SUTS_DIR, "jacococli.jar")
JACOCO_PORT = 6300

DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Java / JDK Configuration (Windows)
# ─────────────────────────────────────────────

JAVA_HOME = r"C:\Program Files\Eclipse Adoptium\jdk-17.0.16.8-hotspot"


def setup_java_env():
    if JAVA_HOME:
        java_bin = os.path.join(JAVA_HOME, "bin")
        os.environ["JAVA_HOME"] = JAVA_HOME
        os.environ["PATH"]      = java_bin + os.pathsep + os.environ.get("PATH", "")
        log(f"JAVA_HOME : {JAVA_HOME}")
        log(f"Java bin  : {java_bin}")

    log("java -version check ...")
    result = subprocess.run(
        ["java", "-version"],
        shell=True,
        capture_output=True,
        text=True,
    )
    version_output = result.stderr.strip() or result.stdout.strip()
    if version_output:
        log(f"  {version_output}")
    if result.returncode != 0:
        log("[WARNING] 'java -version' failed — check JAVA_HOME path.")


# ─────────────────────────────────────────────
#  Helpers
# ─────────────────────────────────────────────

def log(msg: str, indent: int = 0):
    print("  " * indent + msg, flush=True)


def separator(title: str = ""):
    line = "=" * 60
    if title:
        print(f"\n{line}\n  {title}\n{line}")
    else:
        print(line)


def run_command(cmd: list, cwd: str, env: dict = None) -> int:
    """
    Run a command in the given working directory, streaming
    stdout + stderr live to the console. Returns the exit code.
    """
    log(f"CMD : {' '.join(str(c) for c in cmd)}", indent=1)
    log(f"CWD : {cwd}", indent=1)

    process = subprocess.Popen(
        cmd,
        cwd=cwd,
        shell=True,
        env=env or os.environ.copy(),
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,
        text=True,
        bufsize=1,
    )

    for line in process.stdout:
        log(line.rstrip(), indent=2)
    process.wait()
    return process.returncode


def docker_compose_up(sut_dir: str, sut: str) -> bool:
    log(f"Starting Docker container for: {sut}", indent=1)
    if sut == "mrbs":
        sut_dir = os.path.join(sut_dir, "src", "docker_app")

    code = run_command(["docker", "compose", "up", "--build", "-d", "--force-recreate"], cwd=sut_dir)
    if code != 0:
        log(f"[FAILED] docker compose up failed (exit code {code})", indent=1)

    time.sleep(15)
    code = run_command(["docker", "start", f"{sut}-mitmproxy-1"], cwd=sut_dir)
    if code != 0:
        log(f"[FAILED] docker proxy start (exit code {code})", indent=1)

    log(f"Containers started. Waiting {DOCKER_STARTUP_WAIT}s for services to be ready...", indent=1)
    time.sleep(DOCKER_STARTUP_WAIT)
    return True


def docker_compose_down(sut_dir: str, sut: str):
    run_command(["docker", "stop", f"{sut}-web"], cwd=sut_dir)
    run_command(["docker", "rm",   f"{sut}-web"], cwd=sut_dir)

    run_command(["docker", "stop", f"{sut}-db"], cwd=sut_dir)
    run_command(["docker", "rm",   f"{sut}-db"], cwd=sut_dir)

    run_command(["docker", "stop", f"{sut}-mitmproxy-1"], cwd=sut_dir)
    run_command(["docker", "rm",   f"{sut}-mitmproxy-1"], cwd=sut_dir)

    if sut == "mrbs":
        run_command(["docker", "stop", "mrbs-phpmyadmin"], cwd=sut_dir)
        run_command(["docker", "rm",   "mrbs-phpmyadmin"], cwd=sut_dir)

    time.sleep(30)

    log(f"Stopping Docker container for: {sut}", indent=1)
    code = run_command(["docker", "compose", "down", "--remove-orphans"], cwd=sut_dir)
    if code != 0:
        time.sleep(30)

def run_testar(sut: str) -> bool:
    """
    Run TESTAR against the SUT. BLOCKS until the process exits.
    """
    log(f"Running TESTAR for: {sut}", indent=1)

    if not os.path.isfile(TESTAR_BAT):
        log(f"[ERROR] testar.bat not found at: {TESTAR_BAT}", indent=1)
        return False

    url = "http://localhost:8080/parabank" if sut == "parabank" else "http://localhost:8080"
    log(f"URL : {url}", indent=1)
    code = run_command(
        [
            TESTAR_BAT,
            "sse=web",
            f"SUTConnectorValue={url}",
            "ShowVisualSettingsDialogOnStartup=false",
            "Mode=Generate",
            f"MaxTime={TESTAR_MAX_TIME}",
            "StopGenerationOnFault = false"
            
        ],
        cwd=TESTAR_BIN,
    )

    if code == 0:
        log(f"[SUCCESS] TESTAR finished for: {sut}", indent=1)
        return True

    log(f"[FAILED] TESTAR failed for: {sut} (exit code {code})", indent=1)
    return False


# ─────────────────────────────────────────────
#  Coverage helpers
# ─────────────────────────────────────────────

def empty_directory(path: str):
    """Remove all contents of a directory without deleting the directory itself."""
    if not os.path.isdir(path):
        log(f"[WARNING] Dir not found, skipping cleanup: {path}", indent=2)
        return
    for entry in os.listdir(path):
        entry_path = os.path.join(path, entry)
        if os.path.isfile(entry_path) or os.path.islink(entry_path):
            os.remove(entry_path)
        elif os.path.isdir(entry_path):
            shutil.rmtree(entry_path)
    log(f"Emptied directory: {path}", indent=2)


def collect_php_report(sut: str, run_dir: str) -> bool:
    """
    Copy  suts/<sut>/coverage/report.csv  →  <run_dir>/report_<SUT>_TESTAR.csv
    then empty the coverage directory.
    """
    if sut == "mrbs":
        coverage_dir = os.path.join(SUTS_DIR, sut, "src", "coverage")
    else:
        coverage_dir = os.path.join(SUTS_DIR, sut, "coverage")
    src          = os.path.join(coverage_dir, "report.csv")
    dest_name    = f"report_{sut.upper()}_{TOOL}.csv"
    dest         = os.path.join(run_dir, dest_name)

    log(f"Collecting PHP coverage report for: {sut}", indent=1)

    if not os.path.isfile(src):
        log(f"[WARNING] report.csv not found at: {src}", indent=2)
        return False

    shutil.copy2(src, dest)
    log(f"Copied  : {src}", indent=2)
    log(f"      → : {dest}", indent=2)

    empty_directory(coverage_dir)
    return True


def collect_java_report(sut: str, run_dir: str) -> bool:
    """
    1. Dump coverage from the running JaCoCo agent.
    2. Generate CSV report into run_dir.
    3. Delete the jacoco.exec file.
    """
    sut_dir     = os.path.join(SUTS_DIR, sut)
    exec_file   = os.path.join(sut_dir, "jacoco.exec")
    classes_dir = os.path.join(sut_dir, "target", "classes")
    sources_dir = os.path.join(sut_dir, "src", "main", "java")
    dest_name   = f"report_{sut.upper()}_{TOOL}.csv"
    dest        = os.path.join(run_dir, dest_name)

    log(f"Collecting JaCoCo coverage for: {sut}", indent=1)

    if not os.path.isfile(JACOCO_CLI):
        log(f"[ERROR] jacococli.jar not found at: {JACOCO_CLI}", indent=2)
        return False

    # ── Step 1: dump ──────────────────────────────────────────────
    log(f"Dumping coverage from localhost:{JACOCO_PORT} ...", indent=2)
    code = run_command(
        [
            "java", "-jar", JACOCO_CLI,
            "dump",
            "--address",  "localhost",
            "--port",     str(JACOCO_PORT),
            "--destfile", exec_file,
        ],
        cwd=SUTS_DIR,
    )
    if code != 0:
        log(f"[FAILED] JaCoCo dump failed (exit code {code})", indent=2)
        return False
    log(f"Exec file written to: {exec_file}", indent=2)

    # ── Step 2: report ────────────────────────────────────────────
    log(f"Generating CSV report → {dest}", indent=2)

    if not os.path.isdir(classes_dir):
        log(f"[WARNING] classes dir not found at: {classes_dir}", indent=2)

    if not os.path.isdir(sources_dir):
        log(f"[WARNING] sources dir not found at: {sources_dir}", indent=2)

    code = run_command(
        [
            "java", "-jar", JACOCO_CLI,
            "report", exec_file,
            "--classfiles",  classes_dir,
            "--sourcefiles", sources_dir,
            "--csv",         dest,
        ],
        cwd=SUTS_DIR,
    )
    if code != 0:
        log(f"[FAILED] JaCoCo report generation failed (exit code {code})", indent=2)
        return False

    log(f"Report saved to: {dest}", indent=2)

    # ── Step 3: clean up exec file ────────────────────────────────
    if os.path.isfile(exec_file):
        os.remove(exec_file)
        log(f"Removed exec file: {exec_file}", indent=2)

    return True


# ─────────────────────────────────────────────
#  Single run over all SUTs
# ─────────────────────────────────────────────

def run_all_suts(run_dir: str) -> dict:
    """
    Run TESTAR against every SUT once, collecting reports into run_dir.
    Returns a results dict {sut: status_string}.
    """
    results = {}

    for index, sut in enumerate(SUTS, start=1):
        is_java  = sut in JAVA_SUTS
        sut_type = "JAVA" if is_java else "PHP"
        separator(f"[{index}/{len(SUTS)}]  SUT: {sut.upper()}  ({sut_type})")

        sut_dir = os.path.join(SUTS_DIR, sut)

        # 1. Validate SUT folder
        if not os.path.isdir(sut_dir):
            log(f"[SKIPPED] Folder not found: {sut_dir}", indent=1)
            results[sut] = "SKIPPED  (folder missing)"
            continue

        # 2. Start Docker
        if not docker_compose_up(sut_dir, sut):
            results[sut] = "FAILED   (docker compose up)"
            continue

        # 3. Run TESTAR — blocks until done
        testar_ok = run_testar(sut)

        # 4. Collect coverage report into this run's folder
        if testar_ok:
            if is_java:
                report_ok = collect_java_report(sut, run_dir)
            else:
                report_ok = collect_php_report(sut, run_dir)

            if not report_ok:
                log(f"[WARNING] TESTAR succeeded but report collection failed for: {sut}", indent=1)
                results[sut] = "SUCCESS  (report collection failed)"
            else:
                results[sut] = "SUCCESS"
        else:
            results[sut] = "FAILED   (testar)"

        # 5. Stop Docker
        docker_compose_down(sut_dir, sut)

    return results


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    separator("TESTAR BATCH RUNNER")

    separator("JAVA ENVIRONMENT SETUP")
    setup_java_env()
    separator()

    log(f"Base dir      : {BASE_DIR}")
    log(f"SUTs dir      : {SUTS_DIR}")
    log(f"TESTAR bat    : {TESTAR_BAT}")
    log(f"JaCoCo CLI    : {JACOCO_CLI}")
    log(f"PHP SUTs      : {len(PHP_SUTS)}")
    log(f"Java SUTs     : {len(JAVA_SUTS)}")
    log(f"Total SUTs    : {len(SUTS)}")
    log(f"Runs          : {NUM_RUNS}")
    log(f"Max time/SUT  : {TESTAR_MAX_TIME}s ({TESTAR_MAX_TIME // 3600}h)")
    separator()

    if not os.path.isdir(SUTS_DIR):
        log(f"[ERROR] 'suts/' folder not found at: {SUTS_DIR}")
        log("Place this script at the project root, next to suts/ and tools/.")
        sys.exit(1)

    if not os.path.isfile(TESTAR_BAT):
        log(f"[WARNING] testar.bat not found at: {TESTAR_BAT}")
        log("Continuing — make sure TESTAR is built/installed before this script runs.")

    if not os.path.isfile(JACOCO_CLI):
        log(f"[WARNING] jacococli.jar not found at: {JACOCO_CLI}")
        log("Java SUT report collection will fail without it.")

    all_run_results = {}  # {run_number: {sut: status}}

    for run_number in range(1, NUM_RUNS + 1):
        run_label = f"testar_run{run_number}"
        run_dir   = os.path.join(BASE_DIR, run_label)

        separator(f"RUN {run_number}/{NUM_RUNS}  →  {run_label}/")
        os.makedirs(run_dir, exist_ok=True)
        log(f"Output folder : {run_dir}", indent=1)

        results = run_all_suts(run_dir)
        all_run_results[run_number] = results

        # Per-run summary
        separator(f"RUN {run_number} SUMMARY")
        for sut, status in results.items():
            icon     = "v" if status == "SUCCESS" else "x"
            sut_type = "JAVA" if sut in JAVA_SUTS else "PHP "
            print(f"  [{icon}]  [{sut_type}]  {sut:<20}  {status}")

    # ── Grand summary across all runs ─────────────────────────────
    separator("GRAND SUMMARY  (all runs)")
    total_sut_runs = 0
    total_success  = 0

    for run_number, results in all_run_results.items():
        run_success = sum(1 for s in results.values() if s == "SUCCESS")
        run_total   = len(results)
        total_sut_runs += run_total
        total_success  += run_success
        print(f"  Run {run_number:>2}:  {run_success}/{run_total} SUTs succeeded")

    separator()
    log(f"Total SUT executions : {total_sut_runs}")
    log(f"Total successes      : {total_success}")
    log(f"Total failures/skips : {total_sut_runs - total_success}")
    separator()

    sys.exit(0 if total_success == total_sut_runs else 1)


if __name__ == "__main__":
    main()
