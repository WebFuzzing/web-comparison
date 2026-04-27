<?php
/**
 * Merges all coverage/*.ser files from XDebug and writes report.csv.
 * Compatible with PHP 5.6+
 *
 * Called automatically from coverage_stop.php after each request.
 * Can also be run manually inside the container:
 *   php /coverage-scripts/generate_report.php [appRoot] [coverDir]
 */

function generate_coverage_report($appRoot, $coverDir)
{
    $outFile = $coverDir . '/report.csv';

    // ── Collect .ser files ────────────────────────────────────────────────────
    $serFiles = glob($coverDir . '/cov_*.ser');
    if (empty($serFiles)) {
        error_log('generate_report: no .ser files found in ' . $coverDir);
        return;
    }

    // ── Merge coverage data ───────────────────────────────────────────────────
    // Union rule: if a line is executed (1) in ANY request, it counts as covered.
    $merged = array();
    foreach ($serFiles as $f) {
        $raw  = file_get_contents($f);
        $data = unserialize($raw);
        if (!is_array($data)) {
            error_log('generate_report: could not unserialize ' . $f . ', skipping');
            continue;
        }
        foreach ($data as $phpFile => $lines) {
            if (!is_array($lines)) {
                continue;
            }
            foreach ($lines as $lineNo => $status) {
                if (!isset($merged[$phpFile][$lineNo]) || $status === 1) {
                    $merged[$phpFile][$lineNo] = $status;
                }
            }
        }
    }

    if (empty($merged)) {
        error_log('generate_report: merged data is empty, nothing to write');
        return;
    }

    // ── Open CSV for writing ──────────────────────────────────────────────────
    $fp = fopen($outFile, 'w');
    if ($fp === false) {
        error_log('generate_report: cannot open ' . $outFile . ' for writing');
        return;
    }

    fputcsv($fp, array('file', 'total_lines', 'covered_lines', 'coverage_percent'));

    $totalLines   = 0;
    $coveredLines = 0;

    ksort($merged);

    foreach ($merged as $phpFile => $lines) {
        // NOTE: is_file() intentionally skipped — paths inside .ser files are
        // container-internal and may not resolve depending on where this runs.
        $relPath = ltrim(str_replace($appRoot, '', $phpFile), '/');
        if ($relPath === '') {
            $relPath = $phpFile;
        }

        $fileTot = 0;
        $fileCov = 0;

        foreach ($lines as $status) {
            if ($status === -2) {
                continue; // exclude dead code
            }
            $fileTot++;
            if ($status === 1) {
                $fileCov++;
            }
        }

        if ($fileTot === 0) {
            continue; // nothing trackable in this file
        }

        $pct = round(($fileCov / $fileTot) * 100, 2);

        $totalLines   += $fileTot;
        $coveredLines += $fileCov;

        fputcsv($fp, array($relPath, $fileTot, $fileCov, $pct));
    }

    // ── Summary row ───────────────────────────────────────────────────────────
    $totalPct = $totalLines > 0 ? round(($coveredLines / $totalLines) * 100, 2) : 0;
    fputcsv($fp, array('TOTAL', $totalLines, $coveredLines, $totalPct));

    fclose($fp);
}

// ── CLI entry point ───────────────────────────────────────────────────────────
// Runs only when executed directly (php generate_report.php), not when
// require_once'd from coverage_stop.php.
// php_sapi_name() is used instead of __FILE__ === SCRIPT_FILENAME
// for broader PHP 5.6 compatibility.
if (php_sapi_name() === 'cli' && isset($argv[0]) &&
    realpath($argv[0]) === realpath(__FILE__)) {

    $appRoot  = isset($argv[1]) ? $argv[1] : '/var/www/html';
    $coverDir = isset($argv[2]) ? $argv[2] : '/var/www/coverage';

    if (!is_dir($coverDir)) {
        echo 'ERROR: Coverage directory not found: ' . $coverDir . "\n";
        exit(1);
    }
    if (!is_writable($coverDir)) {
        echo 'ERROR: Coverage directory is not writable: ' . $coverDir . "\n";
        exit(1);
    }

    generate_coverage_report($appRoot, $coverDir);

    $outFile = $coverDir . '/report.csv';
    if (is_file($outFile)) {
        echo 'Report written to: ' . $outFile . "\n";
        $lines = file($outFile);
        if (!empty($lines)) {
            $last = str_getcsv(end($lines));
            if (isset($last[0]) && $last[0] === 'TOTAL') {
                echo 'Total coverage   : ' . $last[3] . '% '
                   . '(' . $last[2] . '/' . $last[1] . " lines)\n";
            }
        }
    }
}
