<?php

/**
 * 代替php默认Session
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-19
 * @version: 1.0
 * @Description
 */
class USession {

    private static $__instance = null;

    private $__storage         = null;
    private $__option          = array(
        "prefix"            => "cookie",
        "cookie_domain"     => "", //cookie 域名
        "cookie_sid_name"   => "__sid", //session_id cookie 名称
        "expire_time"       => 1800, //失效时间
    );

    private function __construct($conf){
        $class  = "Session{$conf['storage']}";

        //配置参数
        foreach ($conf as $key => $value) {
            $this->__option[$key]  = $value;
        }

        $this->__storage = new $class($this->__option, $this->__sid());
        $this->__gc($this->__option['expire_time']);
    }

    public static function getInstance($conf = ""){
        if(self::$__instance == null){
            self::$__instance = new Session($conf);
        }
        return self::$__instance;
    }



    /**
     * 获取或产生sid
     * @param type $new 产生新SID
     * @return type
     */
    private function __sid($new = false){

        $sid = isset($_GET[$this->__option['cookie_sid_name']]) ?
                $_GET[$this->__option['cookie_sid_name']] :
                    (isset($_COOKIE[$this->__option['cookie_sid_name']]) ? $_COOKIE[$this->__option['cookie_sid_name']] : "");

        if($new || !$sid){
            $str = '';
            while (strlen($str) <= 32) {
                $str .= mt_rand(0, mt_getrandmax());
            }
            $str = microtime(true) . $str . serialize($_SERVER);
            $sid = md5($str);
        }

        $sid = preg_replace("/[^0-9a-zA-Z]/", "", $sid);

        setcookie($this->__option['cookie_sid_name'], $sid, 0, "/", $this->__option['cookie_domain']);
        $__COOKIE[$this->__option['cookie_sid_name']] = $sid;

        return $sid;
    }



    private function __getVal($key){
        $data = $this->__storage->get($key);
        return $data ;
    }

    private function __setVal($key, $val){
        $this->__storage->set($key, $val);
    }

    private function __delete($key){
        $this->__storage->delete($key);
    }

    private function __destroy(){
        $this->__storage->destroy();
    }

    /**
     * 垃圾处理
     */
    private function __gc($time){
        $this->__storage->gc($time);
    }

    public static function get($key){
        return self::$__instance->__getVal($key);
    }

    public static function set($key, $val){
       return self::$__instance->__setVal($key, $val);
    }

    public static function destory(){
        self::$__instance->__destroy();
    }
}

/**
 * Session存储 接口
 */
interface ISessionStorage{
    public function __construct($option, $sid);
    public function set($key, $val);
    public function get($key);
    public function delete($key);
    public function destroy();
    public function gc($time);
}

class SessionFile implements ISessionStorage{

    private $__file = null;
    private $__dir  = null;

    public function __construct($option, $sid){
        $this->__dir    = CORE_PATH . "tmp/sess_{$option['prefix']}_";
        $this->__file   = $this->__dir . preg_replace("[^0-9a-zA-Z]", "", $sid);
        if(!is_file($this->__file)){
            touch($this->__file);
        }
    }

    public function set($key, $val) {
        $data = file_get_contents($this->__file);
        if($data){
            $v      = unserialize($data);
        }
        $v[$key] = $val;

        file_put_contents($this->__file, serialize($v), LOCK_EX);
    }

    public function get($key){

        $data = file_get_contents($this->__file);

        if($data){
            $v = unserialize($data);
            return isset($v[$key]) ? $v[$key] : null;
        }else{
            return null;
        }
    }

    public function delete($key){
        $data = file_get_contents($this->__file);
        if($data){
            $v      = unserialize($data);
            if(isset($v[$key])){
                unset($v[$key]);
                file_put_contents($this->__file, serialize($v), LOCK_EX);
            }
        }
    }

    public function destroy(){
        unlink($this->__file);
    }

    public function gc($time){
         foreach (glob($this->__dir ."*") as $file) {
            if (filemtime($file) + $time < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

}

class SessionRedis implements ISessionStorage{

    private $__redis        = null;
    private $__indentify    = null;
    private $__option       = null;

    public function __construct($option, $sid){
        $this->__redis      = new Redis();
        $this->__redis->connect( $option['host'], $option['port'] );
        $this->__option     = $option;
        $this->__indentify  = $option['prefix'] .":" . $sid;
    }

    public function delete($key) {
        $data = $this->_redis->get($this->__indentify);
        if($data){
            $data = unserialize($data);
            unset($data[$key]);
            $this->__redis->setex($this->__indentify, $this->__option['expire_time'], serialize($data));
        }
    }

    public function destroy() {
        $this->_redis->delete($this->__indentify);
    }

    public function get($key) {
        $data = $this->_redis->get($this->__indentify );
        if($data){
            $data = unserialize($data);
            return isset($data[$key]) ? $data[$key] : null;
        }
        return null;
    }

    public function set($key, $val) {
        $data = $this->_redis->get($this->__indentify);
        if($data){
            $data       = unserialize($data);
            $data[$key] = $val;
            $this->__redis->setex($this->__indentify, $this->__option['expire_time'], serialize($data));
        }
    }

    public function gc($time) {

    }

}

class SessionMemcache implements ISessionStorage{

    private $__memcache = null;
    private $__option   = null;
    private $__indentify    = null;

    public function __construct($option, $sid) {
        $this->__memcache   = memcache_init();
        $this->__indentify  = $option['prefix'] .":" . $sid;
    }

    public function delete($key) {
        $data = memcache_get($this->__memcache, $this->__indentify);
        if($data){
            $data = unserialize($data);
            unset($data[$key]);
            memcache_set($this->__memcache, $this->__indentify, serialize($data));
        }
    }

    public function destroy() {
        memcache_set($this->__memcache, $this->__indentify, "");
        memcache_set($this->__memcache, $this->__indentify . "_time", time());
    }

    public function gc($time) {

    }

    public function get($key) {

        $data = memcache_get($this->__memcache, $this->__indentify);
        if($data){
            $data = unserialize($data);
            return isset($data[$key]) ? $data[$key] : null;
        }
        return null;

    }

    public function set($key, $val) {
        $data = memcache_get($this->__memcache, $this->__indentify);
        if($data){
            $data       = unserialize($data);
        }
        $data[$key] = $val;
        memcache_set($this->__memcache, $this->__indentify, serialize($data));
        memcache_set($this->__memcache, $this->__indentify . "_time", time());
    }

}

USession::getInstance(Conf::get("session"));