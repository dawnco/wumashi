<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */
class AdminModel {
    public function login($username, $password) {
        $where['username'] = $username;
        $admin = get_line(prepare("SELECT * FROM admin WHERE username = ?s ", array($username)));
        if ($admin['password'] == md5($password)) {
            $row                        = array();
            $row['login_time']          = $admin['login_time'] + 1;
            $row['last_login_time']     = time();
            $row['last_login_ip']       = get_client_ip();
            update("admin", $row, $where);
            return $admin;
        } else {
            return false;
        }
    }

}
