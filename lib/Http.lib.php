<?php

/**
 * @author  Dawnc
 * @date    2014-06-23
 */
class Http {
	
	static $error = '';
    /**
     * 
     * @param type $url
     * @param type $data
	 * @param array $opt curl set_opt参数
     * @return type
     */
    public static function request($url, $data = array(), $opt = array()) {

        $userAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0";
        $header    = array('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');

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
		
		if($opt){
			foreach($opt as $k => $v){
				curl_setopt($ch, $k , $v); //跟随跳转
			}
		}
		
        $output = curl_exec($ch);
		
		if($output === false){
			self::$error = curl_error($ch);
		}
		
        curl_close($ch);
		
		
		
		
        return $output;
    }

    /**
     * 
     * @param type $des 保存路径
     * @param type $url
     */
    public static function saveFromUrl($des, $url, $opt = array()) {
        $data = self::request($url, array(), $opt);
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
    public static function saveImageFromUrl($des, $url, $opt = array()) {
        self::saveFromUrl($des, $url, $opt);
        if (!getimagesize($des)) {
            unlink($des);
        }
    }

}
