<?php

namespace wumashi\lib\session;

use wumashi\core\Exception;
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
            throw new Exception("session can't connect redis");
        }
    }

    public function read($session_id) {
        try {
            $data = $this->_handle->get($session_id);
        } catch (\Exception $e) {
            throw new Exception("session can't connect redis");
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
