<?php

/**
 * @author  Dawnc
 * @date    2014-07-21
 */
if (!defined('CORE_PATH')) {
    define("CORE_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

if (!defined("ROOT")) {
    define("ROOT", dirname(CORE_PATH) . DIRECTORY_SEPARATOR);
}

require CORE_PATH . "init.php";
require APP_PATH  . "init.php";
