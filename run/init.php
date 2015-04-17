<?php

/**
 * 自动加载
 * @param type $class_name
 */
function __wumashi_autoload($class_name) {
    $class_file = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $class_name) . ".php";
    
    if(is_file($class_file)){
        include ROOT . $class_file;
    }else{
        trigger_error($class_name . " Not Found", E_USER_ERROR);
    }
}
spl_autoload_register("__wumashi_autoload");

// end 

require CORE_PATH . "run/define.php";
require CORE_PATH . "fn/app.fn.php";
require CORE_PATH . "fn/transcribe.fn.php";



