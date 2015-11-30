<?php

namespace wumashi\core;

/**
 *
 * @author  Dawnc
 * @date    2014-04-28
 */
class Session {

    protected static $_instance = null;

    public static function instance($conf) {
        if (self::$_instance == null) {
            if (isset($conf['storage'])) {
                $cls = $conf['storage'];
            } else {
                $cls = "\\wumashi\\lib\\session\\SessionDefault";
            }
            
            self::$_instance = new $cls($conf);
        }
    }

    public static function get($key) {
        return self::$_instance->get($key);
    }

    public static function set($key, $val) {
        self::$_instance->set($key, $val);
    }

    public function delete($key) {
        self::$_instance->delete($key);
    }

    public static function destroy() {
        self::$_instance->isDelete = true;
    }

    /**
     * 获得sid
     * @return type
     */
    public static function sid() {
        return self::$_instance->sid();
    }
    
    /**
     * 获取session name
     * @return type
     */
    public static function sname() {
        return self::$_instance->getName();
    }
    
}
