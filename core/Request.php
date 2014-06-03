<?php

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-11
 * Description:
 */

class Request {

    public $post, $get, $request, $cookie, $context;

    function __construct() {
        $this->post     = $_POST;
        $this->get      = $_GET;
        $this->request  = $_REQUEST;
        $this->cookie   = $_COOKIE;
        $this->context  = array();
    }

}
