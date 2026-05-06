import subprocess
import os
import sys
import time

# ─────────────────────────────────────────────
#  Configuration
# ─────────────────────────────────────────────

SUTS = [
    "addressbook",
    "claroline",
    "collabtive",
    "mantisbt",
    "mrbs",
    "parabank",
    "petclinic",
    "petstore",
    "schoolmate",
    "socialnetwork",
    "timeclock",
]

# project structure:
#
#   <project_root>/
#       run_apogen.py
#       suts/
#           addressbook/
#               docker-compose.yml
#           claroline/
#           ...
#       tools/
#           crawljax/
#               cli/
#                   target/
#                        crawljax-cli-5.2.3.jar 

BASE_DIR   = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR   = os.path.join(BASE_DIR, "suts")
CRAWLJAX_JAR = os.path.join(
    BASE_DIR, "tools", "crawljax", "cli", "target","crawljax-cli-5.2.3.jar",)

# Seconds to wait after `docker compose up -d` before running apogen.
# Increase if your containers need more startup time.
DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Java / JDK Configuration (Windows)
# ─────────────────────────────────────────────

JAVA_HOME = r"C:\Program Files\Eclipse Adoptium\jdk-11.0.28.6-hotspot"


def setup_java_env():
    """
    Equivalent to running in PowerShell:
        $env:JAVA_HOME = "C:\Program Files\Eclipse Adoptium\jdk-11.0.28.6-hotspot"
        $env:Path      = "$env:JAVA_HOME\bin;$env:Path"
    """
    java_bin = os.path.join(JAVA_HOME, "bin")

    os.environ["JAVA_HOME"] = JAVA_HOME
    os.environ["PATH"]      = java_bin + os.pathsep + os.environ.get("PATH", "")

    log(f"JAVA_HOME : {os.environ['JAVA_HOME']}")
    log(f"Java bin  : {java_bin}")
    log(f"java -version check ...")

    # Quick sanity-check — prints the JDK version to confirm it works
    result = subprocess.run(
        ["java", "-version"],
        shell=True,
        capture_output=True,
        text=True,
    )
    # java -version prints to stderr by convention
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


def run_command(cmd: list, cwd: str) -> int:
    """
    Run a command in the given working directory, streaming
    stdout + stderr live to the console. Returns the exit code.
    """
    log(f"CMD : {' '.join(str(c) for c in cmd)}", indent=1)
    log(f"CWD : {cwd}", indent=1)

    process = subprocess.Popen(
        cmd,
        cwd=cwd,
        shell=True,                # Required on Windows to resolve PATH
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,  # Merge stderr into stdout
        text=True,
        bufsize=1,
    )

    for line in process.stdout:
        log(line.rstrip(), indent=2)
    process.wait()
    return process.returncode


def docker_compose_up(sut_dir: str, sut: str) -> bool:
    """docker compose up --build -d  then wait for services."""
    log(f"Starting Docker container for: {sut}", indent=1)

    code = run_command(["docker", "compose", "up", "--build", "-d"], cwd=sut_dir)

    if code != 0:
        log(f"[FAILED] docker compose up failed (exit code {code})", indent=1)
        return False

    log(f"Containers started. Waiting {DOCKER_STARTUP_WAIT}s for services to be ready...", indent=1)
    time.sleep(DOCKER_STARTUP_WAIT)
    return True


def docker_compose_down(sut_dir: str, sut: str):
    """docker compose down --remove-orphans"""
    log(f"Stopping Docker container for: {sut}", indent=1)
    code = run_command(["docker", "compose", "down", "--remove-orphans"], cwd=sut_dir)
    if code != 0:
        log(f"[WARNING] docker compose down had issues (exit code {code})", indent=1)


def run_crawljax(sut: str) -> bool:
    """
    Run crawljax against the SUT. BLOCKS until the process exits.
    """
    log(f"Running Crawljax for: {sut}", indent=1)

    if not os.path.exists(CRAWLJAX_JAR):
        log(f"[WARNING] JAR not found at: {CRAWLJAX_JAR}", indent=1)

    url = "http://127.0.0.1:8080/parabank" if sut == "parabank" else "http://127.0.0.1:8080/"
    
    output_folder = f"./outputfolder{sut.upper()}"  # e.g. ./outputfolderPARABANK
    log(f"Output folder: {output_folder}", indent=1)

    code = run_command(["java", "-jar", CRAWLJAX_JAR, url, output_folder], cwd=BASE_DIR)

    if code == 0:
        log(f"[SUCCESS] Crawljax finished for: {sut}", indent=1)
        return True

    log(f"[FAILED] Crawljax failed for: {sut} (exit code {code})", indent=1)
    return False


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    separator("Crawljax BATCH RUNNER")

    # ── Set JAVA_HOME + PATH before anything else ─────────────────
    separator("JAVA ENVIRONMENT SETUP")
    setup_java_env()
    separator()

    log(f"Base dir     : {BASE_DIR}")
    log(f"SUTs dir     : {SUTS_DIR}")
    log(f"Crawljax JAR : {CRAWLJAX_JAR}")
    log(f"Total SUTs   : {len(SUTS)}")
    separator()

    # ── Sanity checks ─────────────────────────────────────────────
    if not os.path.isdir(SUTS_DIR):
        log(f"[ERROR] 'suts/' folder not found at: {SUTS_DIR}")
        log("Place this script at the project root, next to suts/ and tools/.")
        sys.exit(1)

    if not os.path.exists(CRAWLJAX_JAR):
        log(f"[WARNING] Crawljax JAR not found at: {CRAWLJAX_JAR}")
        log("Continuing — make sure the JAR is built before this script runs.")

    results = {}

    for index, sut in enumerate(SUTS, start=1):
        separator(f"[{index}/{len(SUTS)}]  SUT: {sut.upper()}")

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

        # 3. Run Ceawljax — blocks until done
        crawljax_ok = run_crawljax(sut)

        # 4. Stop Docker
        docker_compose_down(sut_dir, sut)

        # 5. Record result
        results[sut] = "SUCCESS" if crawljax_ok else "FAILED   (crawljax)"

    # ── Final summary ─────────────────────────────────────────────
    separator("SUMMARY")
    for sut, status in results.items():
        icon = "v" if status == "SUCCESS" else "x"
        print(f"  [{icon}]  {sut:<20}  {status}")

    total   = len(results)
    success = sum(1 for s in results.values() if s == "SUCCESS")
    failed  = total - success

    separator()
    log(f"Total: {total}   Success: {success}   Failed / Skipped: {failed}")
    separator()

    sys.exit(0 if failed == 0 else 1)


if __name__ == "__main__":
    main()
