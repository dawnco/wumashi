<?php

/**
 * 通知消息
 * 处理消息的类放在   notify 目录   
 * 命名格式   XxxxNotify.php
 * @author  Dawnc
 * @date    2015-01-09
 */
class Notify {

    private static $__notify        = array();
    private static $__isAddShutDown = false;
    private static $__runClass      = array();

    /**
     * 
     * @param type $name        消息名称  
     * @param type $message     消息 id 或者字符串
     */
    public static function add($name, $message) {

        self::__addShutdown();

        self::$__notify[] = array(
            "name"    => $name,
            "message" => $message,
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
        
        if(!self::$__runClass){
            //读取目录
            $files = glob(CORE_PATH . "lib/notify/*Notify.php", GLOB_NOCHECK);
            foreach ($files as $f) {
                $name = basename($f, ".php");
                include $f;
                self::$__runClass[$name] = new $name();
            }
        }
        
        foreach (self::$__runClass as $cls) {
            foreach (self::$__notify as $notify) {
                $cls->notify($notify['name'], $notify['message']);
            }
        }
    }

}
