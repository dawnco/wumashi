<?php

namespace wumashi\lib\session;

/**
 * @author  Dawnc
 * @date    2015-04-17
 */
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
            $this->_handle = new \Redis();
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
