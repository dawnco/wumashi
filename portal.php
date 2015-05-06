<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-30
 */
use wumashi\core\Registry;
use wumashi\core\Request;
use wumashi\core\Route;
use wumashi\core\Dispatcher;

if (!defined('ENV')) {
    exit('No direct script access allowed');
}

// 设置时区（中国）
date_default_timezone_set('PRC');

header("Content-Type: text/html; charset=UTF-8");

switch (ENV) {
    case "development":
        error_reporting(E_ALL ^ E_NOTICE);
        break;
    case "testing":
        error_reporting(E_ALL);
        break;
    case "product":
    default:
        error_reporting(0);
        break;
}

if (!defined('WUMASHI_PATH')) {
    define("WUMASHI_PATH", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

if (!defined('ROOT')) {
    define("ROOT", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}

if (!defined('APP_PATH')) {
    define("APP_PATH", ROOT . APP_NAME . DIRECTORY_SEPARATOR);
}


require WUMASHI_PATH . "run/init.php";
require WUMASHI_PATH . "run/compatible.php";
require APP_PATH  . "init.php";


Registry::add("request", new Request(new Route()));

$dispatcher = new Dispatcher(Registry::get("request"));
$dispatcher->run();
