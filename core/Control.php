<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-23
 */
abstract class _Control{

    protected $_request = null, $_error   = null;

    function __construct(){
        $this->_request = Registry::get("request");
    }

    /**
     * 输出json数据
     * @param string $status 状态
     * @param type $msg 消息
     * @param type $data 数据
     */
    protected function _outJson($status = 'success', $msg = '', $data = '', $url = ''){
        $all_status = array("success", "error", "fail", "warn", "notice");
        if (!in_array($status, $all_status)){
            $status = 'unkonw';
        }
        $out = array("status" => $status, "message" => $msg, "data" => $data, "url" => $url);
        echo View::json($out, input('jsoncallback'));
        exit;
    }

    /** 设置页面javascript */
    protected function _addScript($src){
        View::addValue('scripts', $src);
    }

    /** 设置页面 stylesheet */
    protected function _addStylesheet($src){
        View::addValue('stylesheets', $src);
    }

    /** 设置页面 title 信息 */
    protected function _setMetaTitle($string){
        $this->_a("meta_title", $string);
    }

    /** 设置页面 description */
    protected function _setMetaDescription($string){
        View::assign("meta_description", $string);
    }

    /** 设置页面 keyword */
    protected function _setMetaKeyword($string){
        View::assign("meta_keyword", $string);
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
    protected function _noticeMessage($msg = null, $field = "error"){
        View::addValue("__" . $field, $msg);
    }

    /** 是否ajax提交 */
    protected function _isAjax(){
        if (input("ajax")){
            return true;
        } elseif ($_SERVER['HTTP_X_REQUESTED_WITH']){
            return true;
        }
        return false;
    }

    /** 是否POST */
    protected function _isPost(){
        return strtoupper($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false;
    }

}
