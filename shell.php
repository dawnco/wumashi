<?php

/**
 * @author  Dawnc
 * @date    2014-07-21
 */
if (!defined('WUMASHI_PATH')) {
    define("WUMASHI_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

if (!defined("ROOT")) {
    define("ROOT", dirname(WUMASHI_PATH) . DIRECTORY_SEPARATOR);
}

require WUMASHI_PATH . "run/init.php";
require WUMASHI_PATH . "run/compatible.php";
require APP_PATH  . "init.php";
