<?php

namespace wumashi\lib;

/**
 * @link    http://blog.wumashi.com
 * @author  Dawnc
 * @date    2014-05-22
 */
class Validator {

    /**
     * 是否邮箱
     * @param type $val
     * @return type
     */
    public static function mail($val){
        return preg_match('/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/', $val) > 0 ? true : false;
    }


    /**
     * 不为空
     * @param type $val
     * @return type
     */
    public static function required($val){
         return ($val == null || trim($val) == '' || strlen($val) <= 0) ? false : true;
    }

    /**
     * 长度大于
     * @param type $val
     * @param type $min
     * @return type
     */
    public static function minlen($val, $min){
        $len = mb_strlen($val, "UTF-8");
        return   $len >= $min ? true : false;
    }

    /**
     * 长度小于
     * @param type $val
     * @param type $max
     * @return type
     */
    public static function maxlen($val, $max){
        $len = mb_strlen($val, "UTF-8");
        return  $len <= $max ? true : false;
    }

    public static function rangelen($val, $min, $max){
        $len = mb_strlen($val, "UTF-8");
        return   ($len >= $min  &&  $len <= $max) ? true : false;
    }

    public static function url($url){
         return (preg_match('/^https?:\/\/[\d\-a-zA-Z]+(\.[\d\-a-zA-Z]+)*\/?$/', $url) > 0) ? true : false;
    }

    public static function mobile($mobile){
         return (preg_match('/^1[0-9]{10,10}$/', $mobile) > 0) ? true : false;
    }

    public static function equal($str1, $str2){
        return strcasecmp($str1, $str2) == 0 ? true : false;
    }
}
