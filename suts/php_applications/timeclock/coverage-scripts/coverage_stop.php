<?php
if (extension_loaded('xdebug') && function_exists('xdebug_get_code_coverage')) {
    $data = xdebug_get_code_coverage();
    xdebug_stop_code_coverage(false); // false = keep accumulated data

    $dir = '/var/www/coverage';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // PHP 5.6-compatible random suffix
    if (function_exists('openssl_random_pseudo_bytes')) {
        $rand = bin2hex(openssl_random_pseudo_bytes(4));
    } else {
        // fallback if OpenSSL not available
        $rand = bin2hex(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    }

    // One file per request, named by time + random suffix to avoid collisions
    $file = $dir . '/cov_' . str_replace(' ', '_', microtime()) . '_' . $rand . '.ser';
    file_put_contents($file, serialize($data));
}
else{
    echo "XDebug issue!";
}