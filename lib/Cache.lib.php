<?php

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
        $cls = "CacheStorage{$storage}";
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

class CacheStorageRedis{
    
    private $__redis = null;
    
    public $connected   = false;

    public function __construct(){
        $this->__redis = new Redis();
        $conf = Conf::get("cache", "redis", "default");
        $this->__redis->connect($conf['host'], $conf['port']);
    }
    
    public function set($key, $value, $expire){
        if($expire){
            return $this->__redis->setex($key, $expire, $value);
        }else{
            return $this->__redis->set($key, $expire);
        }
    }
    
    public function get($key){
        return $this->__redis->get($key);
    }
    
    public function delete($key){
        return $this->__redis->delete($key);
    }
    
    /**
     * 是否存在
     * @param type $key
     * @return boolean true 存在 false 不存在
     */
    public function exist($key){
        return $this->__redis->exists($key);
    }
    
    public function close(){
        $this->__redis->close();
    }
    
}

class CacheStorageMemcache{
    
    private $__memcache = null;
    //是否已经链接
    public $connected   = false;

    public function __construct(){
        $this->__memcache = new Memcache();
        $conf             = $conf = Conf::get("cache", "memcache", "default");;
        $this->connected  = $this->__memcache->connect($conf['host'], $conf['port'], 10);
    }
    
    public function get($key){
        return $this->__memcache->get($key);
    }
    
    public function set($key, $value, $expire){
        return $this->__memcache->set($key, $value, MEMCACHE_COMPRESSED, $expire ? $expire : 3600 * 24);
    }
    
    public function delete($key){
        return $this->__memcache->delete($key);
    }
    
    public function exist($key){
        
    }
    
    public function close(){
        $this->__memcache->close();
    }
}