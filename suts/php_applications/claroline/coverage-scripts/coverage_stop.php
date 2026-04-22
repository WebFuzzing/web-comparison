<?php
if (!extension_loaded('xdebug') || !function_exists('xdebug_get_code_coverage')) {
    return;
}

$data = xdebug_get_code_coverage();
xdebug_stop_code_coverage(false);

$coverDir = '/var/www/coverage';
$appRoot  = '/var/www/html';

if (!is_dir($coverDir)) {
    mkdir($coverDir, 0777, true);
}

// PHP 5.6-compatible random suffix
if (function_exists('openssl_random_pseudo_bytes')) {
    $rand = bin2hex(openssl_random_pseudo_bytes(4));
} else {
    $rand = bin2hex(substr(md5(uniqid(mt_rand(), true)), 0, 4));
}

$serFile = $coverDir . '/cov_' . microtime(true) . '_' . $rand . '.ser';
file_put_contents($serFile, serialize($data));

require_once '/coverage-scripts/generate_report.php';
generate_coverage_report($appRoot, $coverDir);