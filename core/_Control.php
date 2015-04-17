<?php

namespace wumashi\core;
/**
 * 基类
 * @author Dawnc
 * @date 2013-11-23
 */
abstract class _Control{
  
    protected $_request = null, $_error   = null;

    function __construct(){
        $this->_request = Registry::get("request");
    }

    /** 设置页面 三要数 信息 */
    protected function _setMeta($title, $description = "", $keywords = ""){
        View::assign("meta", array("title" => $title, "description" => $description, "keywords" =>  $keywords));
    }

    /** 面包屑导航  */
    protected function _addCrumb($name, $url){
        View::addValue('crumb', array("name" => $name, "url" => $url), true);
    }

    /**
     * 设置页面消息信息
     * @param mix $msg 数组或者字符串
     * @param string $field 消息字段或者消息类型
     * @return mix 
     */
    protected function _message($msg = null, $field = "error"){
        View::addValue("__" . $field, $msg);
    }

}
