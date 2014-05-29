<?php

/**
 *
 * @author 五马石
 */
class Conf {

    private static $__files = array();
    private static $__data  = array();

    /**
     * 获取配置
     * @param type $name
     * @param type... $name
     * @return type
     */
    public static function get($name){

        if (!isset(self::$__files[$name])) {
            self::$__files[$name] = APP_PATH . "conf/{$name}.conf.php";

            if(!is_file(self::$__files[$name])){
                trigger_error("can't find {$name}.conf.php");
            }

            $data = include self::$__files[$name];
            self::$__data = array_merge(self::$__data, array($name => $data));
        }

        $args   = func_get_args();
        $conf   = self::$__data;

        foreach ($args as $n) {
            $conf = isset($conf[$n]) ? $conf[$n] : null;
        }

        return $conf;
    }

    public static function set($name, $value){
        self::$__data[$name] = $value;
    }

}
