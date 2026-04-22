<?php
/**
 * Run this manually inside the container to produce an HTML report.
 * Usage: php /coverage-scripts/generate_report.php /var/www/html /var/www/coverage
 */

// PHP 5.6-compatible argument handling
$appRoot  = isset($argv[1]) ? $argv[1] : '/var/www/html';
$coverDir = isset($argv[2]) ? $argv[2] : '/var/www/coverage';
$outFile  = $coverDir . '/report.html';

// Merge all serialized coverage files
$merged = array();
foreach (glob($coverDir . '/cov_*.ser') as $f) {
    $data = unserialize(file_get_contents($f));
    foreach ($data as $file => $lines) {
        foreach ($lines as $line => $status) {
            if (!isset($merged[$file][$line])) {
                $merged[$file][$line] = $status;
            } elseif ($status === 1) {
                $merged[$file][$line] = 1; // executed beats not-executed
            }
        }
    }
}

// Build report
$totalLines = 0;
$coveredLines = 0;
$rows = '';

ksort($merged);
foreach ($merged as $phpFile => $lines) {
    if (!is_file($phpFile)) continue;
    $source   = file($phpFile);
    $relPath  = str_replace($appRoot, '', $phpFile);
    $fileTot  = 0;
    $fileCov  = 0;

    foreach ($lines as $line => $status) {
        $fileTot++;
        if ($status === 1) $fileCov++;
    }

    $totalLines   += $fileTot;
    $coveredLines += $fileCov;
    $pct = $fileTot > 0 ? round($fileCov / $fileTot * 100, 1) : 0;

    if ($pct >= 80) {
        $color = '#2d7a2d';
    } elseif ($pct >= 40) {
        $color = '#b07d00';
    } else {
        $color = '#a02020';
    }

    $rows .= "<tr>
        <td><a href='#" . md5($phpFile) . "'>{$relPath}</a></td>
        <td>{$fileCov}/{$fileTot}</td>
        <td style='color:{$color};font-weight:500'>{$pct}%</td>
    </tr>";
}

$totalPct = $totalLines > 0 ? round($coveredLines / $totalLines * 100, 1) : 0;

// File-level detail
$detail = '';
foreach ($merged as $phpFile => $lines) {
    if (!is_file($phpFile)) continue;
    $source  = file($phpFile);
    $relPath = str_replace($appRoot, '', $phpFile);

    $detail .= "<h3 id='" . md5($phpFile) . "'>" . htmlspecialchars($relPath) . "</h3><pre>";

    foreach ($source as $i => $srcLine) {
        $lineNo = $i + 1;

        // PHP 5.6-compatible null coalescing replacement
        $status = isset($lines[$lineNo]) ? $lines[$lineNo] : null;

        if ($status === 1) {
            $class = 'cov';
        } elseif ($status === -1) {
            $class = 'uncov';
        } elseif ($status === -2) {
            $class = 'dead';
        } else {
            $class = 'noexec';
        }

        $detail .= "<span class='{$class}'>" . sprintf('%4d', $lineNo) . ' '
                 . htmlspecialchars($srcLine) . "</span>";
    }

    $detail .= "</pre>";
}

$html = <<<HTML
<!DOCTYPE html><html><head><meta charset="utf-8">
<title>Coverage Report</title>
<style>
body{font-family:sans-serif;margin:2rem;background:#fafafa;color:#222}
table{border-collapse:collapse;width:100%;margin-bottom:2rem}
th,td{text-align:left;padding:6px 12px;border-bottom:1px solid #ddd}
th{background:#eee}
pre{font-size:12px;line-height:1.5;overflow-x:auto;background:#1e1e1e;
    color:#d4d4d4;padding:1rem;border-radius:4px}
.cov  {background:#1a3a1a;color:#98e898;display:block}
.uncov{background:#3a1a1a;color:#e89898;display:block}
.dead {background:#2a2a1a;color:#e8e898;display:block}
.noexec{display:block}
h2{margin-top:0}
</style></head><body>
<h2>Coverage report</h2>
<p>Total: <strong>{$coveredLines}/{$totalLines} lines ({$totalPct}%)</strong></p>
<table><tr><th>File</th><th>Lines</th><th>Coverage</th></tr>
{$rows}
</table>
{$detail}
</body></html>
HTML;

file_put_contents($outFile, $html);

echo "Report written to {$outFile}\n";
echo "Total coverage: {$totalPct}% ({$coveredLines}/{$totalLines} lines)\n";