<?php

/**
 * 自动加载
 * @param type $class_name
 */
function __wumashi_autoload($class_name) {
    $class_file = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $class_name) . ".php";
    
    if (is_file(ROOT . $class_file)) {
        include ROOT . $class_file;
    } else if (is_file(WUMASHI_PATH . $class_file)) {
        include WUMASHI_PATH . $class_file;
    }else if (is_file(ROOT . "vendor/" . $class_file)) {
        include ROOT . "vendor/" . $class_file;
    } else {
        trigger_error($class_name . " Not Found", E_USER_ERROR);
    }
}

spl_autoload_register("__wumashi_autoload");

// end 

require WUMASHI_PATH . "run/define.php";
require WUMASHI_PATH . "fn/app.fn.php";
require WUMASHI_PATH . "fn/transcribe.fn.php";



