<?php
if (!extension_loaded('xdebug') || !function_exists('xdebug_get_code_coverage')) {
    return;
}

$data = xdebug_get_code_coverage();
xdebug_stop_code_coverage(false); // false = keep accumulated data

$coverDir = '/var/www/coverage';
$appRoot  = '/var/www/html';

// ── Create coverage directory if needed ───────────────────────────────────────
if (!is_dir($coverDir)) {
    mkdir($coverDir, 0777, true);
}

// ── Save this request's coverage as a .ser file ───────────────────────────────
if (function_exists('openssl_random_pseudo_bytes')) {
    $rand = bin2hex(openssl_random_pseudo_bytes(4));
} else {
    $rand = bin2hex(substr(md5(uniqid(mt_rand(), true)), 0, 4));
}
$serFile = $coverDir . '/cov_' . microtime(true) . '_' . $rand . '.ser';
file_put_contents($serFile, serialize($data));

// ── Regenerate the merged CSV report ──────────────────────────────────────────
require_once '/coverage-scripts/generate_report.php';
generate_coverage_report($appRoot, $coverDir);