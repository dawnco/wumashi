<?php

/**
 * 自动加载
 * @param type $class_name
 */
//auto load start
function __wumashi_autoload($class_name) {
    $class_file = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $class_name) . ".php";

    if (is_file(ROOT . $class_file)) {
        include ROOT . $class_file;
    } else if (is_file(WUMASHI_PATH . $class_file)) {
        include WUMASHI_PATH . $class_file;
    } else if (is_file(ROOT . "vendor/" . $class_file)) {
        include ROOT . "vendor/" . $class_file;
    } else {
        throw new \wumashi\core\Exception($class_name . " Not Found");
    }
}

spl_autoload_register("__wumashi_autoload");

// end  autoload
//start custom exception handle
function __wumashi_exception_handle($exception) {
    if (ENV == "development") {
        $data['trace']   = $exception->getTraceAsString();
        $data['code']    = $exception->getCode();
        $data['file']    = $exception->getFile();
        $data['line']    = $exception->getLine();
        $data['message'] = $exception->getMessage();
        \wumashi\core\View::render("error", $data);
    } else {
        echo "System error occurred";
    }
    exit;
}

set_exception_handler('__wumashi_exception_handle');
//end   customer exception handle

require WUMASHI_PATH . "run/define.php";
require WUMASHI_PATH . "fn/app.fn.php";
require WUMASHI_PATH . "fn/transcribe.fn.php";



