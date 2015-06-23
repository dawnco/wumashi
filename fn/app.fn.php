<?php

/**
 * 
 * @param type $key
 * @param type $val_type 转换类型 i 整型  s 字符串  a 数组  默认不转换
 * @return type
 */
function input($key, $val_type = "s") {
    $val = isset($_POST[$key]) ? $_POST[$key] :
            (isset($_GET[$key]) ? $_GET[$key] : false );


    switch ($val_type) {
        case "i":
            $val = (int) $val;
            break;
        case "a" :
            $val = (array) $val;
            break;
        default :
            $val = (string) $val;
            break;
    }

    return $val;
}

function s_input($key) {
    return strip_tags(urldecode(input($key)));
}

function show_404($str = "") {

    header("HTTP/1.0 404 Not Found");
    
    if (ENV == "development") {
        echo "<hr>";
        echo $str;
        echo "<hr>";
        echo "<pre>";
        echo var_dump(\wumashi\core\Registry::get("request"));
        echo "</pre>";
    } else {
        echo " Page Not Found";
    }
    
    exit;
}

function site_url($uri = "", $param = array()) {
    
    $base_url = wumashi\core\Conf::get('app', 'base_url');
 
    if(strpos($base_url, "?")){
        $sp = "&";
    }else{
        $sp = "?";
    }
    
    
    $qs = "";
    foreach($param as $k=>$v){
        $qs .= sprintf("%s=%s&", $k, $v);
    }
    $qs = trim($qs, " &");
    
    return $base_url . $uri . ($qs ? $sp . $qs : "");
}

/**
 * 静态资源
 * @param type $uri
 * @param type $base  默认  app
 *                      app 应用资讯
 *                      common  公共资源
 * @param boolean $version 是否显示版本号
 * @return type
 */
function static_url($uri, $base = "app", $version = true) {
    if ($base == "app") {
        $conf = "app_static_url";
    } elseif ($base == "common") {
        $conf = "com_static_url";
    }
    return wumashi\core\Conf::get('app', $conf) . $uri . ($version ? ("?v=" . wumashi\core\Conf::get('app', 'version')) : "");
}

/**
 * 获取客户端ip
 * @return type
 */
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
    preg_match("/[\d\.]{7,15}/", $realip, $realip);
    return !empty($realip[0]) ? $realip[0] : '0.0.0.0';
}

/**
 * 跳转
 * @param type $uri
 * @param type $local 是否本地跳转
 */
function redirect($uri = "", $local = true) {
    if ($local) {
        header("Location: " . site_url($uri));
    } else {
        header("Location: " . $uri);
    }
    exit;
}
