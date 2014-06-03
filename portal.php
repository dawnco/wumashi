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

 if(!defined('CORE_PATH')){
	define("CORE_PATH", dirname(__FILE__) . "/");
 }

 if(!defined('ROOT')){
	define("ROOT", dirname(dirname(__FILE__)). "/");
 }

require CORE_PATH . "run/autoloader.php";

require CORE_PATH . "fn/common.fn.php";

require CORE_PATH . "core/Conf.php";
require CORE_PATH . "core/Route.php";
require CORE_PATH . "core/Dispatcher.php";
require CORE_PATH . "core/Request.php";
require CORE_PATH . "core/View.php";
require CORE_PATH . "core/Hook.php";
require CORE_PATH . "core/Control.php";
require CORE_PATH . "core/Db.php";

require APP_PATH . "fn/app.fn.php";

if(Conf::get("app", "session_autostart") !== false){
    Session::instance();
}

$dispatcher = new Dispatcher(new Request(new Route()));
$dispatcher->run();