<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-23
 */
abstract class  _Control {
    protected $_request = null,$_post = null,$_error = null;
    
    function __construct($request) {
        $this->_request = $request;
    }
    
    protected function _render($tpl, $data = array()) {
        View::render($tpl, $data);
    }
    
    protected function _layout($tpl = '', $data = array(), $layout = "layout"){
        $data['tpl'] = $tpl;
        View::render($layout, $data);
    }
    
    /**
     * 输出json数据
     * @param string $status 状态
     * @param type $msg 消息
     * @param type $data 数据
     */
    protected function _outJson($status = 'success', $msg = '', $data = '', $url = '') {
        $all_status = array("success", "error", "fail", "warn", "notice");
        if (!in_array($status, $all_status)) {
            $status = 'unkonw';
        }
        $out = array("status" => $status, "message" => $msg, "data" => $data, "url" => $url);
        echo View::json($out, input('jsoncallback'));
        exit;
    }
/** 设置页面javascript */
    protected function _addScript($src) {
        View::addValue('scripts', $src);
    }

    /** 设置页面 stylesheet */
    protected function _addStylesheet($src) {
        View::addValue('stylesheets', $src);
    }

    /** 设置页面 title 信息 */
    protected function _setMetaTitle($string) {
        $this->_a("meta_title", $string);
    }

    /** 设置页面 description */
    protected function _setMetaDescription($string) {
        View::assign("meta_description", $string);
    }

    /** 设置页面 keyword */
    protected function _setMetaKeyword($string) {
        View::assign("meta_keyword", $string);
    }

    /** 面包屑导航  */
    protected function _addCrumb($name, $url) {
        View::addValue('crumb', array("name" => $name, "url" => $url), true);
    }

    /**
     * 设置页面消息信息
     * @param mix $msg 数组或者字符串
     * @param string $field 消息字段或者消息类型
     * @return mix 
     */
    protected function _noticeMessage($msg = null, $field = "error") {
        View::addValue("__" . $field, $msg);
    }
    /** 是否ajax提交 */
    protected function _isAjax() {
        if ($_REQUEST['ajax']) {
            return true;
        } elseif ($_SERVER['HTTP_X_REQUESTED_WITH']) {
            return true;
        }
        return false;
    }

    /** 是否POST */
    protected function _isPost() {
        return strtoupper($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false;
    }

    /**
     * 是否post提交且通过验证
     * @return boolean
     */
    protected function _isPostValidate() {
        if (!$this->_isPost()) {
            return false;
        }
        return $this->_isValidate();
    }

    /**
     * ajax 验证 如果验证不通过直接输出错误信息并exit
     */
    protected function _isAjaxValidate() {
        if (!$this->_isValidate()) {
            $this->_outJson();
            exit;
        } else {
            return true;
        }
    }

    /**
     * 判断是否通过验证 
     * 验证不同过会在视图上有$__error 数组变量显示错误信息
     * 验证规则使用conf/validate.conf.php 格式
     *   array("控制器不加Control.方法" => array(
     *      array("验证字段","规则", "验证不通过的错误信息")
     *     )
     *   )
     * @return boolean  
     */
    protected function _isValidate() {
        $validator = FormValidator::instance();
        $rules = include APP_PATH . "conf/validate.conf.php";
        $rules = array_change_key_case($rules);

        $rule_name = strtolower($this->_request->context['action'] . "." . $this->_request->context['method']);
        $rule = $rules[$rule_name];
        foreach ($rule as $r) {
                $validator->add($r[0], $r[1], isset($r[2]) ? $r[2] : "", isset($r[3]) ? $r[3] : "str_clear", isset($r[4]) ? $r[4] : FormValidator::VALIDATE_TIME_BOTH);
        }
        $validator->validate();
        $this->_post = $validator->data();
        $this->_error = $validator->errors();
        //有验证通过的数据且没有错误数据
        if ($this->_post && !$this->_error) {
            return true;
        }
        //设置页面信息
        View::addValue("__error", $this->_error);
        return false;
    }

    /**
     * 获取验证后的POST数据
     * @param sting $key 键名
     * @return mix Description
     */
    protected function _post($key = null) {
        if ($key == null) {
            return $this->_post ? $this->_post : $_POST;
        } else {
            return $this->_post[$key] ? $this->_post[$key] : $_POST[$key];
        }
    }
}
