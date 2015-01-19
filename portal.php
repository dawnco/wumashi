<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-30
 */

if (!defined('ENV')) {exit('No direct script access allowed');}

// 设置时区（中国）
date_default_timezone_set('PRC');

header("Content-Type: text/html; charset=UTF-8");

switch (ENV) {
    case "development":
        error_reporting(E_ALL ^E_NOTICE);
        break;
    case "testing":
        error_reporting(E_ALL ^E_NOTICE);
        break;
    case "product":
    default:
        error_reporting(0);
        break;
}

 if(!defined('CORE_PATH')){
	define("CORE_PATH", dirname(__FILE__) . "/");
 }

 if(!defined('ROOT')){
	define("ROOT", dirname(dirname(__FILE__)). "/");
 }

require CORE_PATH . "fn/autoload.fn.php";
require CORE_PATH . "fn/common.fn.php";
require CORE_PATH . "fn/app.fn.php";
require CORE_PATH . "fn/transcribe.fn.php";

require APP_PATH .  "fn/app.fn.php";

require CORE_PATH . "core/Conf.php";
require CORE_PATH . "core/Registry.php";
require CORE_PATH . "core/Route.php";
require CORE_PATH . "core/Dispatcher.php";
require CORE_PATH . "core/Request.php";
require CORE_PATH . "core/View.php";
require CORE_PATH . "core/Hook.php";
require CORE_PATH . "core/Control.php";
require CORE_PATH . "core/Db.php";

if(Conf::get("app", "session_autostart") !== false){
    Session::instance(Conf::get("session"));
}


Registry::add("request", new Request(new Route()));

$dispatcher = new Dispatcher(Registry::get("request"));
$dispatcher->run();