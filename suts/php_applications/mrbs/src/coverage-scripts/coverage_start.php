<?php
if (extension_loaded('xdebug') && function_exists('xdebug_start_code_coverage')) {
    xdebug_start_code_coverage(
        XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE
    );
}