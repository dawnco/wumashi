<?php

/**
 * 通知消息
 * @author  Dawnc
 * @date    2015-01-09
 */
class Notify {


    private static $__notify        = array();
    private static $__isAddShutDown = false;
    private static $__runClass      = array();

    /**
     * 
     * @param type $type        类型  
     * @param type $action      动作 如 delete modify 
     * @param type $message     消息 id 或者字符串
     */
    public static function add($type, $action, $message) {

        self::__addShutdown();

        self::$__notify[] = array(
            "type"    => $type,
            "message" => $message,
            "action"  => $action,
        );
    }

    private static function __addShutdown() {
        if (!self::$__isAddShutDown) {
            register_shutdown_function(array("Notify", "run"));
            self::$__isAddShutDown = true;
        }
    }

    /**
     * 执行通知
     */
    public static function run() {

        foreach (self::$__notify as $notify) {

            $name = $notify['type'] . "Notify";

            //加载通知处理类
            $file = CORE_PATH . "libs/nofity/$name.php";
            
            if (!isset(self::$__runClass[$name]) && is_file($file)) {
                
                include $file;
                
                if (class_exists($name)) {
                    self::$__runClass[$name] = new $name();
                }
            }
            
            //处理通知
            if(isset(self::$__runClass[$name]) && is_callable(array(self::$__runClass[$name], $notify['action']))){
                call_user_func(array(self::$__runClass[$name], $notify['action']), $notify['message']);
            }
        }
    }

}
