#!/usr/bin/env python3
# script for cytestion has to be Linux-compatible since the tool can run only on linux-based systems.
import subprocess
import os
import sys
import time
import shutil

# ─────────────────────────────────────────────
#  Configuration
# ─────────────────────────────────────────────

PHP_SUTS = [
    #"claroline",
    #"collabtive",
    #"mantisbt",
    #"mrbs",
    #"schoolmate",
    #"socialnetwork",
    "timeclock",
]
JAVA_SUTS = [
    #"parabank",
    #"petclinic",
    #"petstore",
    "triangle"
]

SUT_CREDENTIALS = {
    "mantisbt":      ("administrator", "root"),
    "schoolmate":    ("test",          "test"),
    "timeclock":     ("admin",         "admin"),
    "collabtive":    ("admin",         "admin"),
    "mrbs":          ("admin",         "admin"),
}


SUTS = PHP_SUTS + JAVA_SUTS

TOOL           = "CYTESTION"
NUM_ITERATIONS = 1

# project structure:
#
#   <project_root>/
#       run_cytestion.py
#       suts/
#           addressbook/
#               docker-compose.yml
#           claroline/
#           ...
#       tools/
#           cytestion/       ← Node.js project with package.json
#       cytestion_run1/      ← output for iteration 1
#       cytestion_run2/      ← output for iteration 2
#       ...

BASE_DIR      = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR      = os.path.join(BASE_DIR, "suts")
CYTESTION_DIR = os.path.join(BASE_DIR, "tools", "cytestion")

# Seconds to wait after `docker compose up -d` before running cytestion.
DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Node.js / Yarn Configuration (Linux)
# ─────────────────────────────────────────────

# Set this only if node/yarn lives outside your PATH, e.g. a manual install:
#   NODE_HOME = "/home/user/.nvm/versions/node/v20.0.0"
# For nvm, volta, or system installs that set PATH correctly, leave as None.
NODE_HOME = None


def setup_node_env():
    if NODE_HOME:
        node_bin = os.path.join(NODE_HOME, "bin")
        os.environ["PATH"] = node_bin + os.pathsep + os.environ.get("PATH", "")
        log(f"NODE_HOME : {NODE_HOME}")
        log(f"Node bin  : {node_bin}")

    for tool in ("node", "yarn"):
        log(f"{tool} --version check ...")
        result = subprocess.run(
            [tool, "--version"],
            shell=False,            
            capture_output=True,
            text=True,
        )
        version_output = result.stdout.strip() or result.stderr.strip()
        if version_output:
            log(f"  {tool} {version_output}")
        if result.returncode != 0:
            log(f"[WARNING] '{tool} --version' failed — is {tool} installed and on PATH?")

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
    """Return path to cytestion_runX directory, creating it if needed."""
    run_dir = os.path.join(BASE_DIR, f"cytestion_run{iteration}")
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

def run_cytestion(sut: str, run_dir: str) -> bool:
    log(f"Running Cytestion for: {sut}", indent=1)

    if not os.path.isdir(CYTESTION_DIR):
        log(f"[ERROR] Cytestion directory not found at: {CYTESTION_DIR}", indent=1)
        return False

    url = "http://127.0.0.1:8080/parabank" if sut == "parabank" else "http://127.0.0.1:8080/"
    log(f"Target URL : {url}", indent=1)

    sut_output = os.path.join(run_dir, sut.upper())
    os.makedirs(sut_output, exist_ok=True)

    login, password = SUT_CREDENTIALS.get(sut, ("user", "pass"))

    env = os.environ.copy()
    env["BASE_URL"]      = url
    env["BASE_URL_API"]  = url
    env["LOGIN_URL"]     = url
    env["USER_LOGIN"]    = login
    env["USER_PASSWORD"] = password
    env["BROWSER"]       = "chrome"
    env["CHECK_400"]     = "true"
    env["CHECK_500"]     = "true"
    env["SUT_NAME"]      = sut
    env["SUT_OUTPUT"]    = sut_output

    log(f"Output folder : {sut_output}", indent=1)
    log(f"Credentials   : {login} / {'*' * len(password)}", indent=1)

    code = run_command(
        ["yarn", "generate-test:prod"],   # no shutil.which — let shell=True resolve it
        cwd=CYTESTION_DIR,
        env=env,
    )

    if code == 0:
        log(f"[SUCCESS] Cytestion finished for: {sut}", indent=1)
        return True

    log(f"[FAILED] Cytestion failed for: {sut} (exit code {code})", indent=1)
    return False


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

        # 3. Run Cytestion
        cytestion_ok = run_cytestion(sut, run_dir)

        # 4. Stop Docker
        docker_compose_down(sut_dir, sut)

        # 5. Record result
        results[sut] = "SUCCESS" if cytestion_ok else "FAILED   (cytestion)"

    return results


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    separator("CYTESTION BATCH RUNNER")

    separator("NODE / YARN ENVIRONMENT SETUP")
    setup_node_env()
    separator()

    log(f"Base dir       : {BASE_DIR}")
    log(f"SUTs dir       : {SUTS_DIR}")
    log(f"Cytestion dir  : {CYTESTION_DIR}")
    log(f"PHP SUTs       : {len(PHP_SUTS)}")
    log(f"Java SUTs      : {len(JAVA_SUTS)}")
    log(f"Total SUTs     : {len(SUTS)}")
    log(f"Iterations     : {NUM_ITERATIONS}")
    separator()

    if not os.path.isdir(SUTS_DIR):
        log(f"[ERROR] 'suts/' folder not found at: {SUTS_DIR}")
        log("Place this script at the project root, next to suts/ and tools/.")
        sys.exit(1)

    if not os.path.isdir(CYTESTION_DIR):
        log(f"[WARNING] Cytestion directory not found at: {CYTESTION_DIR}")
        log("Continuing — make sure the tool is present before this script runs.")

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

        print(f"\n  ── Iteration {iteration}  (output: cytestion_run{iteration}/)  "
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
