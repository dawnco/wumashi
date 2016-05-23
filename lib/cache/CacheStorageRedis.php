<?php

namespace wumashi\lib\cache;

use wumashi\core\Conf;
/**
 * @author  Dawnc
 * @date    2015-04-04
 */
class CacheStorageRedis{
    
    private $__redis = null;
    
    public $connected   = false;

    public function __construct($conf){
        $this->__redis = new \Redis();
        $this->__redis->connect($conf['host'], $conf['port']);
    }
    
    public function set($key, $value, $expire){
        if($expire){
            return $this->__redis->setex($key, $expire, $value);
        }else{
            return $this->__redis->set($key, $value);
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
