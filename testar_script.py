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
    "addressbook",
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
]

SUTS = PHP_SUTS + JAVA_SUTS
TOOL = "TESTAR"

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

BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR    = os.path.join(BASE_DIR, "suts")
TESTAR_BIN  = os.path.join(
    BASE_DIR, "tools", "TESTAR_dev-2.7.18", "testar", "testar",
    "target", "install", "testar", "bin",
)
TESTAR_BAT  = os.path.join(TESTAR_BIN, "testar.bat")
JACOCO_CLI  = os.path.join(SUTS_DIR, "jacococli.jar")  # BASE_DIR/suts/jacococli.jar
JACOCO_PORT = 6300

# Seconds to wait after `docker compose up -d` before running TESTAR.
# Increase if your containers need more startup time.
DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Java / JDK Configuration (Windows)
# ─────────────────────────────────────────────

# Set to your JDK path, or None to use the system PATH.
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
        shell=True,                # Required on Windows to resolve PATH and .bat files
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
    code = run_command(["docker", "compose", "up", "--build", "-d"], cwd=sut_dir)
    if code != 0:
        log(f"[FAILED] docker compose up failed (exit code {code})", indent=1)
        return False
    log(f"Containers started. Waiting {DOCKER_STARTUP_WAIT}s for services to be ready...", indent=1)
    time.sleep(DOCKER_STARTUP_WAIT)
    return True


def docker_compose_down(sut_dir: str, sut: str):
    log(f"Stopping Docker container for: {sut}", indent=1)
    code = run_command(["docker", "compose", "down", "--remove-orphans"], cwd=sut_dir)
    if code != 0:
        log(f"[WARNING] docker compose down had issues (exit code {code})", indent=1)


def run_testar(sut: str) -> bool:
    """
    Run TESTAR against the SUT. BLOCKS until the process exits.
        testar.bat sse=web SUTConnectorValue=http://localhost:8080
        testar.bat sse=web SUTConnectorValue=http://localhost:8080/parabank  (parabank)
    """
    log(f"Running TESTAR for: {sut}", indent=1)

    if not os.path.isfile(TESTAR_BAT):
        log(f"[ERROR] testar.bat not found at: {TESTAR_BAT}", indent=1)
        return False

    url = "http://localhost:8080/parabank" if sut == "parabank" else "http://localhost:8080"
    log(f"URL : {url}", indent=1)
    code = run_command(
        [TESTAR_BAT, "sse=web", f"SUTConnectorValue={url}"],
        cwd=TESTAR_BIN,   # testar.bat must be run from its own bin/ directory
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


def collect_php_report(sut: str) -> bool:
    """
    Copy  suts/<sut>/coverage/report.csv  →  <BASE_DIR>/report<SUT>TESTAR.csv
    then empty the coverage directory.
    """
    coverage_dir = os.path.join(SUTS_DIR, sut, "coverage")
    src          = os.path.join(coverage_dir, "report.csv")
    dest_name    = f"report_{sut.upper()}_{TOOL}.csv"
    dest         = os.path.join(BASE_DIR, dest_name)

    log(f"Collecting PHP coverage report for: {sut}", indent=1)

    if not os.path.isfile(src):
        log(f"[WARNING] report.csv not found at: {src}", indent=2)
        return False

    shutil.copy2(src, dest)
    log(f"Copied  : {src}", indent=2)
    log(f"      → : {dest}", indent=2)

    empty_directory(coverage_dir)
    return True


def collect_java_report(sut: str) -> bool:
    """
    1. Dump coverage from the running JaCoCo agent:
           java -jar jacococli.jar dump --address localhost --port 6300
                --destfile suts/<sut>/jacoco.exec

    2. Generate CSV report:
           java -jar jacococli.jar report suts/<sut>/jacoco.exec
                --classfiles  suts/<sut>/target/classes
                --sourcefiles suts/<sut>/src/main/java
                --csv         <BASE_DIR>/report<SUT>TESTAR.csv

    3. Delete the jacoco.exec file.
    """
    sut_dir     = os.path.join(SUTS_DIR, sut)
    exec_file   = os.path.join(sut_dir, "jacoco.exec")
    classes_dir = os.path.join(sut_dir, "target", "classes")
    sources_dir = os.path.join(sut_dir, "src", "main", "java")
    dest_name   = f"report_{sut.upper()}_{TOOL}.csv"
    dest        = os.path.join(BASE_DIR, dest_name)

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

        # 4. Collect coverage report
        if testar_ok:
            if is_java:
                report_ok = collect_java_report(sut)
            else:
                report_ok = collect_php_report(sut)

            if not report_ok:
                log(f"[WARNING] TESTAR succeeded but report collection failed for: {sut}", indent=1)
                results[sut] = "SUCCESS  (report collection failed)"
            else:
                results[sut] = "SUCCESS"
        else:
            results[sut] = "FAILED   (testar)"

        # 5. Stop Docker
        docker_compose_down(sut_dir, sut)

    # ── Final summary ─────────────────────────────────────────────
    separator("SUMMARY")
    for sut, status in results.items():
        icon = "v" if status == "SUCCESS" else "x"
        sut_type = "JAVA" if sut in JAVA_SUTS else "PHP "
        print(f"  [{icon}]  [{sut_type}]  {sut:<20}  {status}")

    total   = len(results)
    success = sum(1 for s in results.values() if s == "SUCCESS")
    failed  = total - success

    separator()
    log(f"Total: {total}   Success: {success}   Failed / Skipped: {failed}")
    separator()

    sys.exit(0 if failed == 0 else 1)


if __name__ == "__main__":
    main()
