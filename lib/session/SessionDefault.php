<?php

namespace wumashi\lib\session;


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
