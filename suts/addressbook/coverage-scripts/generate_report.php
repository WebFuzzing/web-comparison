<?php
/**
 * Generate CSV coverage report
 * Usage: php generate_report.php /var/www/html /var/www/coverage
 */

$appRoot  = $argv[1] ?? '/var/www/html';
$coverDir = $argv[2] ?? '/var/www/coverage';
$outFile  = $coverDir . '/report.csv';

// Merge coverage
$merged = [];

foreach (glob($coverDir . '/cov_*.ser') as $f) {
    $data = unserialize(file_get_contents($f));

    foreach ($data as $file => $lines) {
        foreach ($lines as $line => $status) {
            if (!isset($merged[$file][$line]) || $status === 1) {
                $merged[$file][$line] = $status;
            }
        }
    }
}

// Totals
$totalLines   = 0;
$coveredLines = 0;

ksort($merged);

if (!is_dir($coverDir)) {
    die("Coverage directory does not exist: $coverDir\n");
}

if (!is_writable($coverDir)) {
    die("Coverage directory is not writable: $coverDir\n");
}
// Open CSV
$fp = fopen($outFile, 'w');
if ($fp === false) {
    die("Failed to open file for writing: $outFile\n");
}

// Header
fputcsv($fp, ['file', 'total_lines', 'covered_lines', 'coverage_percent']);

foreach ($merged as $phpFile => $lines) {
    if (!is_file($phpFile)) continue;

    $relPath = str_replace($appRoot, '', $phpFile);

    $fileTot = 0;
    $fileCov = 0;

    foreach ($lines as $status) {
        if ($status !== -2) { // ignore dead code
            $fileTot++;
            if ($status === 1) {
                $fileCov++;
            }
        }
    }

    $pct = $fileTot > 0 ? round(($fileCov / $fileTot) * 100, 2) : 0;

    $totalLines   += $fileTot;
    $coveredLines += $fileCov;

    fputcsv($fp, [$relPath, $fileTot, $fileCov, $pct]);
}

// Add summary row
$totalPct = $totalLines > 0 ? round(($coveredLines / $totalLines) * 100, 2) : 0;
fputcsv($fp, ['TOTAL', $totalLines, $coveredLines, $totalPct]);

fclose($fp);

echo "CSV report written to {$outFile}\n";
echo "Total coverage: {$totalPct}% ({$coveredLines}/{$totalLines} lines)\n";