<?php

/**
 * 自动加载
 * wumashi 开头的 加载  WUMASHI_PATH 目录下的,  APP_NAME 开头的 加载 APP_NAME 下的文件
 * @param type $class_name
 */
//auto load start
function __wumashi_autoload($class_name) {

    $_class = str_replace(array("\\", "/"), "/", $class_name) . ".php";

    $dir  = explode("/", $_class);
    $file = "";

    switch ($dir[0]) {
        case "wumashi":
            $file = WUMASHI_PATH . substr($_class, 8);
            break;
        case APP_NAME :
            $file = APP_PATH . substr($_class, strlen(APP_NAME) + 1);
            break;
    }

    if (is_file($file)) {
        include $file;
        return true;
    }

    if (is_file(ROOT . $_class)) {
        include ROOT . $_class;
    } else if (is_file(VENDOR_PATH . $_class)) {
        //第三方库
        include VENDOR_PATH . $_class;
    }
}

spl_autoload_register("__wumashi_autoload");

// end autoload

//custom error handler 
//function __wumashi_error_handler($errno, $errstr, $errfile, $errline, $errcontext){
//
//    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
//
//}
//set_error_handler("__wumashi_error_handler");



//start custom exception handle
function __wumashi_exception_handler($exception) {
    
    header("HTTP/1.0 500 Internal Server Error");
    
    if (ENV == "development") {
        $data['trace']   = $exception->getTraceAsString();
        $data['code']    = $exception->getCode();
        $data['file']    = $exception->getFile();
        $data['line']    = $exception->getLine();
        $data['message'] = $exception->getMessage();
        \wumashi\core\View::render("error", $data);
    } else {
        echo "System error occurred, ";
        echo "error code" . $exception->getCode();
    }
}

set_exception_handler('__wumashi_exception_handler');
//end   customer exception handle

require WUMASHI_PATH . "run/define.php";
require WUMASHI_PATH . "fn/app.fn.php";
require WUMASHI_PATH . "fn/transcribe.fn.php";



