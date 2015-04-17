<?php

namespace wumashi\core;

/**
 * 注册器
 * @author  Dawnc
 * @date    2014-07-13
 */
class Registry {

    private static $__data = array();

    public static function add($name, $class) {
        self::$__data[$name] = $class;
    }

    public static function get($name) {
        return isset(self::$__data[$name]) ? self::$__data[$name] : null;
    }

    public static function has($name) {
        return isset(self::$__data[$name]);
    }

}
