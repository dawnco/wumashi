<?php

namespace wumashi\lib;

use wumashi\core\Conf;

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
            register_shutdown_function(array(__NAMESPACE__ . "\\Notify", "run"));
            self::$__isAddShutDown = true;
        }
    }

    private static function __loadClass() {

        $classes = Conf::get("notify");

        foreach ($classes as $cls) {
            $cls_name = $cls['c'];
            if (class_exists($cls_name)) {
                self::$__runClass[] = new $cls_name();
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
                if(method_exists($cls, "notify")){
                    $cls->notify($notify['name'], $notify['message'], $notify['who']);
                }
            }
        }
    }

}
