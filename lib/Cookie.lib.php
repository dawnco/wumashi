<?php

/**
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-2-21
 * @version: 1.0
 * @Description
 */
class Cookie {

    /**
     *
     * @param type $key
     * @param type $value
     * @param type $expire 多长时间后过期
     * @param type $path
     * @param type $domain
     */
    public static function set($key, $value, $expire = 0, $path = "/", $domain = null){
        setcookie($key, $value, $expire ? (time() + $expire) : 0, $path, $domain);
        $_COOKIE[$key] = $value;
    }

    public static function get($key){
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : false;
    }

}
