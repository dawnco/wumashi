<?php

namespace wumashi\lib\session;

/**
 * @author  Dawnc
 * @date    2015-04-04
 */
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
        $this->_handle = new \Memcache();
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

