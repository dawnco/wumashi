<?php

namespace wumashi\lib\cache;

use wumashi\core\Conf;

/**
 * @author  Dawnc
 * @date    2015-04-04
 */
class CacheStorageMemcache{
    
    private $__memcache = null;
    //是否已经链接
    public $connected   = false;

    public function __construct(){
        $this->__memcache = new \Memcache();
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
