<?php

namespace wumashi\lib\session;
/**
 * @author  Dawnc
 * @date    2015-04-01
 */

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
            $this->_data = $data ? json_decode($data, true) : array();
        }

        register_shutdown_function(array($this, "save"));
        register_shutdown_function(array($this, "close"));
    }

    public function save() {
        $this->write($this->_prefix . $this->_sid, json_encode($this->_data));
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

        $_COOKIE[$this->_name] = $this->_sid;

        return $this->_sid;
    }

    public function getName() {
        return $this->_name;
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
