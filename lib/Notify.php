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

    private static $__notify   = array();
    private static $__runClass = array();

    /**
     * 
     * @param type $name        消息名称  
     * @param type $message     消息 id 或者字符串
     * @param type $who         调试用 查看是那个调用的
     */
    public static function add($name, $message = array(), $who = null) {
        self::$__notify[] = array(
            "name"    => $name,
            "message" => $message,
            "who"     => $who,
        );
    }

    /**
     * 执行通知
     */
    public static function run() {

        $classes = Conf::get("notify");
        
        if(!$classes){
            return false;
        }
        
        foreach ($classes as $cls) {
            $cls_name = $cls['c'];
            if (class_exists($cls_name)) {
                self::$__runClass[] = [
                    "seq" => isset($cls['s']) ? $cls['s'] : 10,
                    "cls" => new $cls_name(),
                ];
            }
        }

        usort(self::$__runClass, array(__NAMESPACE__ . "\\Notify", "sort"));

        foreach (self::$__runClass as $cls) {
            foreach (self::$__notify as $notify) {
                if (method_exists($cls['cls'], "notify")) {
                    $cls['cls']->notify($notify['name'], $notify['message'], $notify['who']);
                }
            }
        }
    }

   public static function sort($a, $b) {
        if ($a['seq'] == $b['seq']) {
            return 0;
        }
        return $a['seq'] > $b['seq'] ? 1 : -1; // 按升序排列
    }

}
