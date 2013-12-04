<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-30
 */

require CORE_PATH . "fn/common.fn.php";
require CORE_PATH . "fn/db.fn.php";
require CORE_PATH . "core/Route.php";
require CORE_PATH . "core/Dispatcher.php";
require CORE_PATH . "core/Request.php";
require CORE_PATH . "core/View.php";
require CORE_PATH . "core/Hook.php";
require CORE_PATH . "core/Control.php";


require ROOT . "conf/db.conf.php";
require ROOT . "conf/app.conf.php";
require ROOT . "lib/FormValidator.lib.php";
require ROOT . "lib/Session.lib.php";

require APP_PATH . "conf/app.conf.php";




$dispatcher = new Dispatcher(new Request());
$dispatcher->run();