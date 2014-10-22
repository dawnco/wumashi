<?php

/**
 * 基类
 * @author Dawnc
 * @date 2013-11-23
 */
abstract class _Control{
    
    const AJAX_SUCCESS = "success";
    const AJAX_ERROR   = "error";
    const AJAX_NOTICE  = "notice";
    
    protected $_request = null, $_error   = null;

    function __construct(){
        $this->_request = Registry::get("request");
    }

    /**
     * ajax 输出
     * @param type $message
     * @param string $status
     * @param type $data
     * @param type $url
     */
    protected function _ajax($message = '', $status = self::AJAX_SUCCESS, $data = '', $url = ''){
        $all_status = array(self::AJAX_SUCCESS, self::AJAX_ERROR, self::AJAX_NOTICE);
        if (!in_array($status, $all_status)){
            $status = 'unkonw';
        }
        $out['status'] = $status;
        $out['message'] = $message;
        if($data){
            $out['data']    = $data;
        }
        if($url){
            $out['url']    = $url;
        }
        echo View::json($out, input('jsoncallback'));
        exit;
    }

    /** 设置页面 三要数 信息 */
    protected function _setMeta($title, $description, $keywords){
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
