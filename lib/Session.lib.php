<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */
class Session {
    
    static function start(){
         session_start();
    }
    
    static function set($key, $val){
        $_SESSION[$key] = $val;
    }
    
    static function get($key){
        return $_SESSION[$key];
    }
}
