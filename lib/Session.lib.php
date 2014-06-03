<?php

/**
 *
 * @author  Dawnc
 * @date    2014-04-28
 */
class Session {

   private static $__instance = null;

    private $__option = array(
        "prefix" => "cookie",
        "cookie_domain" => "", //cookie 域名
        "cookie_sid_name" => "__sid", //session_id cookie 名称
        "expire_time" => 1800, //失效时间
    );

    private function __construct() {
        
        $cookie_domain = Conf::get("app", "cookie_domain");
        if($cookie_domain){
            $this->__option['cookie_domain'] = $cookie_domain;
        }
        
        $sid = $this->__sid();

        
        
        session_name($this->__option['cookie_sid_name']);
        session_id($sid);
      //  ini_set("session.gc_maxlifetime", $this->__option['expire_time']);
        session_start();
    }

    public static function instance() {
        if (self::$__instance == null) {
            self::$__instance = new Session();
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

        if($new || !$sid || strlen($sid) < 32){
            $str = '';
            while (strlen($str) <= 32) {
                $str .= mt_rand(0, mt_getrandmax());
            }
            $str = microtime(true) . $str . serialize($_SERVER);
            $sid = md5($str);
        }

        $sid = preg_replace("/[^0-9a-zA-Z]/", "", $sid);
        
        //设置 session cookie 参数
        session_set_cookie_params(0, "/", $this->__option['cookie_domain']);
        $__COOKIE[$this->__option['cookie_sid_name']] = $sid;

        return $sid;
    }

    public static function get($key){
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function set($key, $val){

        if($val === null){
            unset($_SESSION[$key]);
        }else{
             $_SESSION[$key] = $val;
        }

    }

    public static function destroy(){
        session_destroy();
    }
}