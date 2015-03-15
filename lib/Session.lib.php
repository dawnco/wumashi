<?php

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
                $cls = "Session{$conf['storage']}";
            } else {
                $cls = "SessionDefault";
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

    public static function sid() {
        return self::$_instance->sid();
    }

}

abstract class SessionAbstract {

    //session 数据
    protected $_data   = null;
    //cookie 域名
    protected $_domain = "";
    //session_id cookie 名称
    protected $_name   = "_sid";
    //失效时间
    protected $_expire = 1800;
    //前缀
    protected $_prefix = "sess_";
    //session_id
    protected $_sid    = "";
    public $isDelete = false;

    public function __construct($conf) {

        if (isset($conf['domain'])) {
            $this->_domain = $conf['domain'];
        }
        if (isset($conf['name'])) {
            $this->_name = $conf['name'];
        }
        if (isset($conf['expire'])) {
            $this->_expire = $conf['expire'];
        }
        if (isset($conf['prefix'])) {
            $this->_prefix = $conf['prefix'];
        }

        $this->sid();
        $this->open($conf);

        $data = $this->read($this->_prefix . $this->_sid);

        if ($data === false) {
            //没有数据新建一个 SID
            $this->sid(true);
        } else {
            $this->_data = $data ? @unserialize($data) : array();
        }

        register_shutdown_function(array($this, "save"));
        register_shutdown_function(array($this, "close"));
    }

    public function save() {
        $this->write($this->_prefix . $this->_sid, serialize($this->_data));
    }

    /**
     * 获取或产生sid
     * @param type $new 产生新SID
     * @return type
     */
    public function sid($new = false) {

        $sid = isset($_GET[$this->_name]) ?
                $_GET[$this->_name] :
                (isset($_COOKIE[$this->_name]) ? $_COOKIE[$this->_name] : "");

        if ($new || !$sid || strlen($sid) < 32) {
            $str = '';
            while (strlen($str) <= 32) {
                $str .= mt_rand(0, mt_getrandmax());
            }
            $str = microtime(true) . $str . serialize($_SERVER);
            $sid = md5($str);
        }

        $this->_sid = preg_replace("/[^0-9a-zA-Z]/", "", $sid);

        $params   = array();
        $params[] = $this->_name;
        $params[] = $this->_sid;
        $params[] = time() + $this->_expire;
        $params[] = "/";
        if ($this->_domain) {
            $params[] = $this->_domain;
        }
        call_user_func_array("setcookie", $params);

        $_COOKIE[$this->_name] = $sid;

        return $this->_sid;
    }

    public function get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : false;
    }

    public function set($key, $val) {

        if ($val === null) {
            unset($this->_data[$key]);
        } else {
            $this->_data[$key] = $val;
        }
    }

    public function delete($key) {
        unset($this->_data[$key]);
    }

    abstract function open($conf);

    abstract function read($session_id);

    abstract function write($session_id, $session_data);

    abstract function close();

    abstract function destroy($session_id);
}

/**
 * php 本身session 实现
 */
class SessionDefault extends SessionAbstract {

    public function close() {
        
    }

    public function get($key) {

        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function delete($key) {
        unset($_SESSION[$key]);
    }

    public function destroy($session_id) {
        session_destroy();
    }

    public function gc($maxlifetime) {
        
    }

    public function open($conf) {
        session_name($this->_name);
        session_id($this->_sid);
        //设置 session cookie 参数
        session_set_cookie_params(0, "/", $this->_domain);
        session_start();
    }

    public function read($session_id) {
        return $_SESSION === null ? false : true;
    }

    public function write($session_id, $session_data) {
        
    }

    public function save() {
        if ($this->isDelete) {
            $_SESSION = array();
        }
    }

}

class SessionMemcache extends SessionAbstract {

    private $_handle = null;

    public function close() {
        $this->_handle->close();
    }

    public function destroy($session_id) {
        $this->_handle->delete($session_id);
    }

    public function gc($maxlifetime) {
        
    }

    public function open($conf) {
        $this->_handle = new Memcache();
        $result        = $this->_handle->connect($conf['host'], $conf['port']);
        if (!$result) {
            trigger_error("can't connect memcache ", E_USER_ERROR);
        }
    }

    public function read($session_id) {
        return $this->_handle->get($session_id);
    }

    public function write($session_id, $session_data) {

        if ($this->isDelete) {
            $this->_handle->delete($session_id);
        } else {
            $this->_handle->set($session_id, $session_data, MEMCACHE_COMPRESSED, $this->_expire);
        }
    }

}

class SessionRedis extends SessionAbstract {

    private $_handle = null;

    public function close() {
        $this->_handle->close();
    }

    public function destroy($session_id) {

        $this->_handle->delete($session_id);
    }

    public function gc($maxlifetime) {
        
    }

    public function open($conf) {
        try {
            $this->_handle = new Redis();
            $this->_handle->connect($conf['host'], $conf['port']);
        } catch (Exception $e) {
            trigger_error("can't connect redis", E_USER_ERROR);
        }
    }

    public function read($session_id) {
        try {
            $data = $this->_handle->get($session_id);
        } catch (Exception $e) {
            trigger_error("can't connect redis", E_USER_ERROR);
            return false;
        }
        return $data;
    }

    public function write($session_id, $session_data) {
        if ($this->isDelete) {
            $this->_handle->delete($session_id);
        } else {
            $this->_handle->setex($session_id, $this->_expire, $session_data);
        }
    }

}
