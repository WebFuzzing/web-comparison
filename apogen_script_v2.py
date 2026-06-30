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
TOOL = "APOGEN"

NUM_ITERATIONS = 9

# project structure:
#
#   <project_root>/                        ← BASE_DIR
#       run_apogen.py
#       suts/
#           addressbook/
#               docker-compose.yml
#               coverage/                  ← PHP: report.csv lives here
#           parabank/
#               docker-compose.yml
#               coverage/                  ← Java: jacoco.exec lives here
#               classes/                   ← Java: compiled .class files
#       tools/
#           apogen/
#               target/
#                   apogen-0.0.1-SNAPSHOT-jar-with-dependencies.jar
#           jacococli.jar                  ← JaCoCo CLI jar
#       apogen_run1/                       ← output for iteration 1
#       apogen_run2/                       ← output for iteration 2
#       ...

BASE_DIR   = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR   = os.path.join(BASE_DIR, "suts")
APOGEN_JAR = os.path.join(
    BASE_DIR, "tools", "apogen", "target",
    "apogen-0.0.1-SNAPSHOT-jar-with-dependencies.jar",
)
JACOCO_CLI  = os.path.join(SUTS_DIR, "jacococli.jar")
JACOCO_PORT = 6300

# Seconds to wait after `docker compose up -d` before running apogen.
DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Java / JDK Configuration (Linux)
# ─────────────────────────────────────────────

JAVA_HOME = r"C:\Program Files\Eclipse Adoptium\jdk-8.0.472.8-hotspot"

def setup_java_env():
    java_bin = os.path.join(JAVA_HOME, "bin")

    os.environ["JAVA_HOME"] = JAVA_HOME
    os.environ["PATH"]      = java_bin + os.pathsep + os.environ.get("PATH", "")

    log(f"JAVA_HOME : {os.environ['JAVA_HOME']}")
    log(f"Java bin  : {java_bin}")
    log(f"java -version check ...")

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
    log(f"CMD : {' '.join(str(c) for c in cmd)}", indent=1)
    log(f"CWD : {cwd}", indent=1)

    process = subprocess.Popen(
        cmd,
        cwd=cwd,
        shell=False,
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


def get_run_dir(iteration: int) -> str:
    """Return path to apogen_runX directory, creating it if needed."""
    run_dir = os.path.join(BASE_DIR, f"apogen_run{iteration}")
    os.makedirs(run_dir, exist_ok=True)
    return run_dir


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


def run_apogen(sut: str) -> bool:
    log(f"Running Apogen for: {sut}", indent=1)
    if not os.path.exists(APOGEN_JAR):
        log(f"[WARNING] JAR not found at: {APOGEN_JAR}", indent=1)

    code = run_command(["java", "-jar", APOGEN_JAR, sut], cwd=BASE_DIR)
    if code == 0:
        log(f"[SUCCESS] Apogen finished for: {sut}", indent=1)
        return True

    log(f"[FAILED] Apogen failed for: {sut} (exit code {code})", indent=1)
    return False


# ─────────────────────────────────────────────
#  Coverage helpers
# ─────────────────────────────────────────────

def empty_directory(path: str):
    """Remove all contents of a directory without deleting the directory itself."""
    if not os.path.isdir(path):
        log(f"[WARNING] Coverage dir not found, skipping cleanup: {path}", indent=2)
        return
    for entry in os.listdir(path):
        entry_path = os.path.join(path, entry)
        if os.path.isfile(entry_path) or os.path.islink(entry_path):
            os.remove(entry_path)
        elif os.path.isdir(entry_path):
            shutil.rmtree(entry_path)
    log(f"Emptied coverage directory: {path}", indent=2)


def collect_php_report(sut: str, run_dir: str) -> bool:
    """
    Copy  suts/<sut>/coverage/report.csv  →  apogen_runX/report_<SUT>_APOGEN.csv
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
    2. Generate CSV report → apogen_runX/report_<SUT>_APOGEN.csv
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
#  Single-iteration runner
# ─────────────────────────────────────────────

def run_iteration(iteration: int) -> dict:
    """Run all SUTs for one iteration. Returns {sut: status} dict."""
    separator(f"ITERATION {iteration} / {NUM_ITERATIONS}")

    run_dir = get_run_dir(iteration)
    log(f"Output directory: {run_dir}")

    results = {}

    for index, sut in enumerate(SUTS, start=1):
        is_java  = sut in JAVA_SUTS
        sut_type = "JAVA" if is_java else "PHP"
        separator(f"[iter {iteration}]  [{index}/{len(SUTS)}]  SUT: {sut.upper()}  ({sut_type})")

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

        # 3. Run Apogen
        apogen_ok = run_apogen(sut)

        # 4. Collect coverage report into the iteration's run_dir
        if apogen_ok:
            if is_java:
                report_ok = collect_java_report(sut, run_dir)
            else:
                report_ok = collect_php_report(sut, run_dir)

            if not report_ok:
                log(f"[WARNING] Apogen succeeded but report collection failed for: {sut}", indent=1)
                results[sut] = "SUCCESS  (report collection failed)"
            else:
                results[sut] = "SUCCESS"
        else:
            results[sut] = "FAILED   (apogen)"

        # 5. Stop Docker
        docker_compose_down(sut_dir, sut)

    return results


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    separator("APOGEN BATCH RUNNER")

    separator("JAVA ENVIRONMENT SETUP")
    setup_java_env()
    separator()

    log(f"Base dir      : {BASE_DIR}")
    log(f"SUTs dir      : {SUTS_DIR}")
    log(f"Apogen JAR    : {APOGEN_JAR}")
    log(f"JaCoCo CLI    : {JACOCO_CLI}")
    log(f"PHP SUTs      : {len(PHP_SUTS)}")
    log(f"Java SUTs     : {len(JAVA_SUTS)}")
    log(f"Total SUTs    : {len(SUTS)}")
    log(f"Iterations    : {NUM_ITERATIONS}")
    separator()

    if not os.path.isdir(SUTS_DIR):
        log(f"[ERROR] 'suts/' folder not found at: {SUTS_DIR}")
        log("Place this script at the project root, next to suts/ and tools/.")
        sys.exit(1)

    if not os.path.exists(APOGEN_JAR):
        log(f"[WARNING] Apogen JAR not found at: {APOGEN_JAR}")
        log("Continuing — make sure the JAR is built before this script runs.")

    if not os.path.isfile(JACOCO_CLI):
        log(f"[WARNING] jacococli.jar not found at: {JACOCO_CLI}")
        log("Java SUT report collection will fail without it.")

    # ── Run all iterations ────────────────────────────────────────
    all_results = {}   # {iteration: {sut: status}}

    for iteration in range(1, NUM_ITERATIONS + 1):
        all_results[iteration] = run_iteration(iteration)

    # ── Final summary across all iterations ──────────────────────
    separator("FINAL SUMMARY — ALL ITERATIONS")

    grand_total   = 0
    grand_success = 0

    for iteration in range(1, NUM_ITERATIONS + 1):
        results  = all_results[iteration]
        total    = len(results)
        success  = sum(1 for s in results.values() if s == "SUCCESS")
        failed   = total - success
        grand_total   += total
        grand_success += success

        print(f"\n  ── Iteration {iteration}  (output: apogen_run{iteration}/)  "
              f"Success: {success}/{total} ──")

        for sut, status in results.items():
            icon     = "v" if status == "SUCCESS" else "x"
            sut_type = "JAVA" if sut in JAVA_SUTS else "PHP "
            print(f"    [{icon}]  [{sut_type}]  {sut:<20}  {status}")

    separator()
    grand_failed = grand_total - grand_success
    log(f"Grand total — runs: {grand_total}   "
        f"Success: {grand_success}   "
        f"Failed / Skipped: {grand_failed}")
    separator()

    sys.exit(0 if grand_failed == 0 else 1)


if __name__ == "__main__":
    main()
