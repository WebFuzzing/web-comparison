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
#       run_cytestion.py
#       suts/
#           addressbook/
#               docker-compose.yml
#           claroline/
#           ...
#       tools/
#           cytestion/       ← Node.js project with package.json

BASE_DIR      = os.path.dirname(os.path.abspath(__file__))
SUTS_DIR      = os.path.join(BASE_DIR, "suts")
CYTESTION_DIR = os.path.join(BASE_DIR, "tools", "cytestion")

# Seconds to wait after `docker compose up -d` before running cytestion.
# Increase if your containers need more startup time.
DOCKER_STARTUP_WAIT = 15

# ─────────────────────────────────────────────
#  Node.js / Yarn Configuration
# ─────────────────────────────────────────────

# Set this if yarn/node is not on the system PATH, e.g.:
# NODE_HOME = r"C:\Program Files\nodejs"
# Otherwise leave as None to use the system PATH.
NODE_HOME = None


def setup_node_env():
    """
    Prepends NODE_HOME/bin to PATH if NODE_HOME is set,
    then verifies node and yarn are accessible.
    """
    if NODE_HOME:
        node_bin = os.path.join(NODE_HOME, "bin") if os.name != "nt" else NODE_HOME
        os.environ["PATH"] = node_bin + os.pathsep + os.environ.get("PATH", "")
        log(f"NODE_HOME : {NODE_HOME}")
        log(f"Node bin  : {node_bin}")

    log("node --version check ...")
    result = subprocess.run(
        ["node", "--version"],
        shell=(os.name == "nt"),
        capture_output=True,
        text=True,
    )
    version_output = result.stdout.strip() or result.stderr.strip()
    if version_output:
        log(f"  node {version_output}")
    if result.returncode != 0:
        log("[WARNING] 'node --version' failed — check NODE_HOME path.")

    log("yarn --version check ...")
    result = subprocess.run(
        ["yarn", "--version"],
        shell=(os.name == "nt"),
        capture_output=True,
        text=True,
    )
    version_output = result.stdout.strip() or result.stderr.strip()
    if version_output:
        log(f"  yarn {version_output}")
    if result.returncode != 0:
        log("[WARNING] 'yarn --version' failed — is Yarn installed?")


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
        shell=(os.name == "nt"),   # Required on Windows to resolve PATH
        env=env or os.environ.copy(),
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


def run_cytestion(sut: str) -> bool:
    """
    Run Cytestion against the SUT via:
        yarn generate-test:prod

    The target URL is passed as an environment variable SUT_URL,
    which Cytestion should read from process.env.SUT_URL in its config.
    Adjust the variable name below if your Cytestion config uses a different key.
    """
    log(f"Running Cytestion for: {sut}", indent=1)

    if not os.path.isdir(CYTESTION_DIR):
        log(f"[ERROR] Cytestion directory not found at: {CYTESTION_DIR}", indent=1)
        return False

    url = "http://127.0.0.1:8080/parabank" if sut == "parabank" else "http://127.0.0.1:8080/"
    log(f"Target URL : {url}", indent=1)

    # Build the child environment: inherit everything, then add/override SUT vars.
    env = os.environ.copy()
    env["SUT_URL"]  = url          # Primary URL variable consumed by Cytestion
    env["SUT_NAME"] = sut          # Optional: lets Cytestion name output files per SUT
    env["SUT_OUTPUT"] = os.path.join(BASE_DIR, f"outputfolder{sut.upper()}")

    log(f"Output folder: {env['SUT_OUTPUT']}", indent=1)

    code = run_command(
        ["yarn", "generate-test:prod"],
        cwd=CYTESTION_DIR,
        env=env,
    )

    if code == 0:
        log(f"[SUCCESS] Cytestion finished for: {sut}", indent=1)
        return True

    log(f"[FAILED] Cytestion failed for: {sut} (exit code {code})", indent=1)
    return False


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    separator("Cytestion BATCH RUNNER")

    # ── Set up Node / Yarn environment ────────────────────────────
    separator("NODE / YARN ENVIRONMENT SETUP")
    setup_node_env()
    separator()

    log(f"Base dir       : {BASE_DIR}")
    log(f"SUTs dir       : {SUTS_DIR}")
    log(f"Cytestion dir  : {CYTESTION_DIR}")
    log(f"Total SUTs     : {len(SUTS)}")
    separator()

    # ── Sanity checks ─────────────────────────────────────────────
    if not os.path.isdir(SUTS_DIR):
        log(f"[ERROR] 'suts/' folder not found at: {SUTS_DIR}")
        log("Place this script at the project root, next to suts/ and tools/.")
        sys.exit(1)

    if not os.path.isdir(CYTESTION_DIR):
        log(f"[WARNING] Cytestion directory not found at: {CYTESTION_DIR}")
        log("Continuing — make sure the tool is present before this script runs.")

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

        # 3. Run Cytestion — blocks until done
        cytestion_ok = run_cytestion(sut)

        # 4. Stop Docker
        docker_compose_down(sut_dir, sut)

        # 5. Record result
        results[sut] = "SUCCESS" if cytestion_ok else "FAILED   (cytestion)"

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
