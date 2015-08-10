<?php

namespace wumashi\core;

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-11
 * Description:
 */
class View{
    
    //ajax 状态
    const AJAX_SUCCESS = "success";
    const AJAX_ERROR   = "error";
    const AJAX_NOTICE  = "notice";
    
    private static $__data = array();

    /**
     * 赋值模板
     * @param type $key
     * @param type $val
     */
    public static function assign($key, $val = null){
        if (is_array($key)){
            self::$__data = array_merge(self::$__data, $key);
        } else{
            self::$__data[$key] = $val;
        }
    }

    /**
     * 合并或者增加val 到 $key中
     * @param string $key
     * @param mix $val  数组或者字符串
     * @param type $signal false  合并 $val 到 $key 中,  true 增加 val 到 $key中
     */
    public static function addValue($key, $val = '', $signal = false){
        if (is_string($val) || $signal){
            self::$__data[$key][] = $val;
        } elseif (is_array($val)){
            self::$__data[$key] = array_merge(isset(self::$__data[$key]) ? self::$__data[$key] : array(), $val);
        }
    }

    public static function getData(){
        return self::$__data;
    }

    /**
     * 输出模板
     * @param type $tpl
     * @param type $data
     */
    public static function render($tpl = '', $data = array()){
        echo self::fetch($tpl, $data);
    }

    /**
     * 输出layout模板
     * @param string $tpl
     * @param array $data
     * @param string $layout
     */
    public static function layout($tpl = "", $data = array(), $layout = "layout"){
        $data['tpl'] = $tpl;
        self::render($layout, $data);
    }

    /**
     * 渲染模板
     * @param type $tpl 模板文件
     * @param type $data 数据
     * @return type
     */
    public static function fetch($tpl = '', $data = array()){
        self::$__data = array_merge(self::$__data, $data);
       
        $_file = APP_PATH . "view/$tpl.tpl.php";
     
        if (self::$__data){
            extract(self::$__data);
        }
        ob_start();
        include $_file;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * 数据json数据
     * @param type $data 数据
     * @param type $callback 回调函数
     */
    public static function json($data, $callback = ""){
        echo $callback ? $callback . "(" . json_encode($data) . ")" : json_encode($data);
    }

    public static function ajax($message, $status = self::AJAX_SUCCESS, $data = "") {
        $all_status = array(self::AJAX_SUCCESS, self::AJAX_ERROR, self::AJAX_NOTICE);
        if (!in_array($status, $all_status)){
            $status = 'unkonw';
        }
        $out['status'] = $status;
        $out['message'] = $message;
        if($data){
            $out['data']    = $data;
        }
        echo self::json($out, input('jsoncallback'));
        exit;
    }
}
