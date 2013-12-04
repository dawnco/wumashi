<?php

/**
 *
 * @author WuMaShi.com
 */


/**
 * 读取配置信息
 * @param string $key[, ... string $key]
 * @return mixed 
 */
function conf() {
	$parameter = func_get_args();
	return __get_var( $GLOBALS, $parameter );
}

/**
 * 无限获取数组key
 * @param $data
 * @param array $keys
 */
function __get_var( &$data, array $keys ) {
    if( empty( $keys ) ) {
        return $data;
    }
    $key = array_shift( $keys );
    // $key不存在
    if( ! isset( $data[$key] ) ) {
        return null;
    }
    // 到达最底层
    if( empty( $keys ) ) {
        return isset( $data[$key] ) ? $data[$key] : null;
    }
    return __get_var( $data[$key], $keys );
}

/**
 * 引入model
 * @param type $model
 */
function minclude($model, $m = "p"){
    if($m == "p"){
        require ROOT . "model/" .$model . "Model.php";
    }else{
        require APP_PATH . "model/" .$model . "Model.php";
    }
}


function show_404($str) {
    if(ENV == "dev"){
        echo $str . "<br>";
    }
    echo "page not found";
    exit;
}


function site_url($uri){
    return conf('app','base_url') . $uri;
}

function static_url($uri){
    return conf('app','site') . "static/" . $uri . "?v=" . conf('app','version');
}


function str_clear($str){
    return $str;
}

function str_clean($str){
    return $str;
}


function get_client_ip() {
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第?个非unknown的有效IP字符? */
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    return !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
}


function redirect($uri = ""){
    header("Location: " . site_url($uri));
    exit;
}