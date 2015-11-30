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
use wumashi\core\Conf;
use wumashi\core\Hook;

if (!defined('ENV')) {
    exit('No direct script access allowed');
}

// 设置时区（中国）
date_default_timezone_set('PRC');

header("Content-Type: text/html; charset=UTF-8");

switch (ENV) {
    case "development":
        error_reporting(E_ALL);
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

//第三方库代码
if (!defined('VENDOR_PATH')) {
    define("VENDOR_PATH", ROOT . "vendor" . DIRECTORY_SEPARATOR);
}


require WUMASHI_PATH . "run/init.php";
require WUMASHI_PATH . "run/compatible.php";
require APP_PATH . "init.php";


Registry::add("request", new Request(new Route()));


//系统钩子
$hooks = Conf::get("hook");
foreach ((array) $hooks as $preg => $hook) {
    if (preg_match("#^$preg$#i", Registry::get("request")->getUri())) {
        Hook::addAction($hook['weld'], [new $hook['h'](), isset($hook['m']) ? $hook['m'] : "hook"], $hook['seq'], isset($hook['p']) ? $hook['p'] : [] );
    }
}

//注册系统关闭时执行的函数
//register_shutdown_function("\\wumashi\\core\\Hook::doAction", "shutdown");
//通知回调
Hook::addAction("after_control", "\\wumashi\\lib\\Notify::run", 11);

$dispatcher = new Dispatcher(Registry::get("request"));
$dispatcher->run();
