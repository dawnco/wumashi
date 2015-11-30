<?php

namespace wumashi\lib;

/**
 *
 * @author  Dawnc
 * @date    2014-04-08
 */
class Cache {

  
    private $__keyPrefix = "cache-key-";
    private $__storage = null;
    
    
    private static $__instance = array();

    /**
     * 获取缓存实例
     * @param type $storage  存储方式  可选址  Memcache Redis
     * @return type
     */
    public static function getInstance($storage = "Redis") {
        if (!isset(self::$__instance[$storage])) {
            self::$__instance[$storage] = new Cache($storage);
        }
        return self::$__instance[$storage];
    }

    /**
     * 设置前缀
     * @param type $prefix
     */
    public function setPrefix($prefix) {
        $this->__keyPrefix = $prefix;
    }

    private function __construct($storage) {
        $cls = "\\wumashi\\lib\\cache\\CacheStorage{$storage}";
        $this->__storage = new $cls();

    }

    private function __key($key) {
        return $this->__keyPrefix . $key;
    }

    /**
     * 设置缓存
     * @param type $key
     * @param type $val
     * @param type $expire 过期时间  0 不过期
     */
    public function set($key, $val, $expire = 3600) {
        $this->__storage->set($this->__key($key), serialize($val), $expire);
    }

    /**
     * 获取缓存
     * @param type $key
     */
    public function get($key) {
        $data =  $this->__storage->get($this->__key($key));
        if($data){
            return unserialize($data);
        }else{
            return false;
        }
    }

    /**
     * 删除缓存
     * @param type $key
     */
    public function delete($key) {
        $this->__storage->delete($this->__key($key));
    }
}
