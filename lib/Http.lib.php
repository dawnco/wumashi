<?php

/**
 * @author  Dawnc
 * @date    2014-06-23
 */
class Http {

    /**
     * 
     * @param type $url
     * @param type $data
     * @return type
     */
    public static function request($url, $data = array()) {

        $userAgent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; CIBA; InfoPath.2; .NET CLR 2.0.50727)";
        $header    = array('Accept-Language: zh-cn', 'Connection: Keep-Alive');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        //ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

        if ($data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟随跳转
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 
     * @param type $des 保存路径
     * @param type $url
     */
    public static function saveFromUrl($des, $url) {
        $data = self::request($url);
        if(!is_dir(dirname($des))){
            mkdir(dirname($des),0755, true);
        }
        file_put_contents($des, $data);
    }

    /**
     * 保存url到本地图片
     * @param type $des 保存路径
     * @param type $url
     */
    public static function saveImageFromUrl($des, $url) {
        self::saveFromUrl($des, $url);
        if (!getimagesize($des)) {
            unlink($des);
        }
    }

}
