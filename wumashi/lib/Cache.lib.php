<?php

/**
 *
 * @author  Dawnc
 * @date    2014-04-25
 */
class Cache {

    protected static $_instance = null;

    public static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new CacheMemcache();
        }
        return self::$_instance;
    }
    
    public function set($key, $value) {}
    
    public function get($key) {}
    
    public function delete($key) {}
}


class CacheMemcache{

    protected $_handler = null;

    public function __construct() {
        $this->_handler = new memcache();
        $host           = Conf::get("cache", 'memcache', "server");
        $port           = Conf::get("cache", 'memcache', "port");
        $this->_handler->connect($host, $port, 30);

        if(!$this->_handler){
            trigger_error("can't connect memcache $host $port");
        }

    }

    public function get($key){
        $data = $this->_handler->get($key);
        if($data){
            return unserialize($data);
        }else{
            return false;
        }
    }

    public function set($key, $value, $expire = 1800){
        return $this->_handler->set($key, serialize($value), MEMCACHE_COMPRESSED, $expire);
    }

    public function delete($key){
        $this->_handler->delete($key);
    }

    public function flush(){
        $this->_handler->flush();
    }
}