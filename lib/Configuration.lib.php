<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */
class Configuration {

    static private $__data;

    static function get($key) {
        return isset(self::$__data[$key]) ? self::$__data[$key] : false;
    }

    static function set($key, $val) {
        self::$__data[$key] = $val;
    }

}
