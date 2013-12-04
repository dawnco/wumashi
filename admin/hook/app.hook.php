<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */

function start_session(){
    Session::start();
}

function hook_auth(){
    if(!Session::get("admin_id")){
        redirect("login");
    }
}