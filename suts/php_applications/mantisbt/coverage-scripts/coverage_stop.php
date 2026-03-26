<?php
if (extension_loaded('xdebug') && function_exists('xdebug_get_code_coverage')) {
    $data = xdebug_get_code_coverage();
    xdebug_stop_code_coverage(false); // false = keep accumulated data

    $dir = '/var/www/coverage';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // One file per request, named by time + random suffix to avoid collisions
    $file = $dir . '/cov_' . microtime(true) . '_' . bin2hex(random_bytes(4)) . '.ser';
    file_put_contents($file, serialize($data));
}