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

if (!defined('APP_PATH')) {
    define("APP_PATH", ROOT . APP_NAME . DIRECTORY_SEPARATOR);
}

//第三方库代码
if (!defined('VENDOR_PATH')) {
    define("VENDOR_PATH", ROOT . "vendor" . DIRECTORY_SEPARATOR);
}

/** 页面请求时间 */
define("WMS_TIME", time());

require WUMASHI_PATH . "run/init.php";
require WUMASHI_PATH . "run/compatible.php";
require APP_PATH  . "init.php";

//注册系统关闭时执行的函数
register_shutdown_function("\\wumashi\\core\\Hook::doAction", "shutdown");
//通知回调
\wumashi\core\Hook::addAction("shutdown", "wumashi\\lib\\Notify::run", 11);
