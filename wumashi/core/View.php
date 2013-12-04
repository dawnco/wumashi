<?php

/**
 * @author: 五马石 <abke@qq.com> 
 * Time: 2013-8-11
 * Description: 
 */
class View {

    private static $__data = array();

    static function assign($key, $val = null) {
        if (is_array($key)) {
            self::$__data = array_merge(self::$__data, $key);
        } elseif ($val) {
            self::$__data[$key] = $val;
        }
    }

    /**
     * 合并或者增加val 到 $key中 
     * @param string $key
     * @param mix $val  数组或者字符串
     * @param type $signal false  合并 $val 到 $key 中,  true 增加 val 到 $key中
     */
    static function addValue($key, $val = '', $signal = false) {
        if (is_string($val) || $signal) {
            self::$__data[$key][] = $val;
        } elseif (is_array($val)) {
            self::$__data[$key] = array_merge(isset(self::$__data[$key])? self::$__data[$key] : array(), $val);
        }
    }

    static function getData() {
        return self::$__data;
    }

    static function render($tpl = '', $data = array()) {
        echo self::fetch($tpl, $data);
    }

    static function fetch($tpl = '', $data = array()) {
        $data = array_merge($data, self::$__data);
        if (self::$__data) {
            extract(self::$__data);
        }
        ob_start();
        include APP_PATH . "template/$tpl.tpl.php";
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * 返回json格式字符串 如果没有验证通过则会设置status为error message为错误信息
     * @param type $data
     * @param type $callback
     * @return type
     */
    static function json($data, $callback = "") {
        if (self::$__data['__error']) {
            $data['status'] = 'error';
            $data['message'] = array_pop(self::$__data['__error']);//显示一条错误信息
        }
        return $callback ? $callback . "(" . json_encode($data) . ")" : json_encode($data);
    }

}
