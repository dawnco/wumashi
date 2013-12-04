<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */
minclude("Admin", 'a');

class LoginControl extends _Control{
    public function index(){
        if($this->_isPost()){
            $model = new AdminModel();
            $admin = $model->login($this->_post('username'), $this->_post('password'));
            if($admin){
                Session::set('admin_id', $admin['admin_id']);
                Session::set('username', $admin['username']);
                redirect();
            }else{
                $this->_noticeMessage("用户名或密码错误");
            }
        }
        $this->_render("login");
    }
}
