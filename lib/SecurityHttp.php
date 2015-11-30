<?php

namespace wumashi\lib;

/**
 * 安全http通信
 * @author  Dawnc
 * @date    2015-11-11
 */
class SecurityHttp {

    /**
     * 
     * @param type $url
     * @param type $data
     * @param type $sign
     * @return type
     */
    public static function request($url, $data = array(), $sign = null) {
        if ($sign == null) {
            $sign = \wumashi\core\Conf::get("app", "key", "http_communicate");
        }
        ksort($data);
        $str          = http_build_query($data, '', '&');
        $data['sign'] = md5($str . $sign);

        foreach ($data as $k => $v) {
            $data[$k] = rawurlencode($v);
        }

        $res = Http::request($url, $data);

        return $res ? json_decode($res, true) : false;
    }

    /**
     * 获取数据
     * @param type $data
     * @param type $sign
     * @return boolean true 
     */
    public static function input($data = null, $sign = null) {
        if ($data == null) {
            $data = $_POST;
        }
        
        if ($sign == null) {
            $sign = \wumashi\core\Conf::get("app", "key", "http_communicate");
        }
      
        foreach ($data as $k => $v) {
            $data[$k] = rawurldecode($v);
        }

        $post_sign = isset($data['sign']) ? $data['sign'] : "";
        unset($data['sign']);
        ksort($data);
        $str       = http_build_query($data, '', '&');

        $str_sign = md5($str . $sign);
        
        if ($post_sign == $str_sign) {
            return $data;
        } else {
            return false;
        }
    }

}
