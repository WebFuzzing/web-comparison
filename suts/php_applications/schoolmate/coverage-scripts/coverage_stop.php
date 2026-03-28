<?php
if (extension_loaded('xdebug') && function_exists('xdebug_get_code_coverage')) {

    $data = xdebug_get_code_coverage();
    xdebug_stop_code_coverage(false); // false = keep accumulated data

    $dir = '/var/www/coverage';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Generate random suffix safely
    if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(4);
        $rand = bin2hex($bytes);
    } else {
        // Safe fallback for PHP 5.4
        $rand = substr(md5(uniqid(mt_rand(), true)), 0, 8);
    }

    // Safer timestamp (avoid dots in filename)
    $time = str_replace('.', '_', microtime(true));

    // Create filename
    $file = $dir . '/cov_' . $time . '_' . $rand . '.ser';

    file_put_contents($file, serialize($data));
}