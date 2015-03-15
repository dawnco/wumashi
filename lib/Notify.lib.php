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
     * @param type $who         调试用 查看是那个调用的
     */
    public static function add($name, $message = array(), $who = null) {

        self::__addShutdown();

        self::$__notify[] = array(
            "name"    => $name,
            "message" => $message,
            "who"     => $who, 
        );
    }

    private static function __addShutdown() {
        if (!self::$__isAddShutDown) {
            register_shutdown_function(array("Notify", "run"));
            self::$__isAddShutDown = true;
        }
    }

    
    private static function __loadClass(){
        if(!self::$__runClass){
            //读取目录
       
            $files = array_merge(glob(APP_PATH . "lib/notify/*Notify.php"), glob(CORE_PATH . "lib/notify/*Notify.php"));
            
            
            foreach ($files as $f) {
                $name = basename($f, ".php");
                if(!isset(self::$__runClass[$name])){
                    include $f;
                    self::$__runClass[$name] = new $name();
                }
            }
        }
    }
    
    /**
     * 执行通知
     */
    public static function run() {
        
        self::__loadClass();
        
        foreach (self::$__runClass as $cls) {
            foreach (self::$__notify as $notify) {
                $cls->notify($notify['name'], $notify['message'], $notify['who']);
            }
        }
    }

}
