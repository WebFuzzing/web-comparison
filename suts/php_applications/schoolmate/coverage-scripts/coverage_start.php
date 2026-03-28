<?php
if (extension_loaded('xdebug') && function_exists('xdebug_start_code_coverage')) {

    $flags = 0;

    if (defined('XDEBUG_CC_UNUSED')) {
        $flags |= XDEBUG_CC_UNUSED;
    }

    if (defined('XDEBUG_CC_DEAD_CODE')) {
        $flags |= XDEBUG_CC_DEAD_CODE;
    }

    xdebug_start_code_coverage($flags);
}