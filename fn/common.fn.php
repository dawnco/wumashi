<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 */

function input($key){
    return isset($_POST[$key]) ? $_POST[$key] :
        (isset($_GET[$key]) ? $_GET[$key] : false );
}

/**
 * 是否ajax请求
 * @return boolean
 */
function is_ajax(){
    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
        return true;
    }else{
        return false;
    }
}

function show_404($str) {

    if(ENV == "development"){
        $message = $str ;
    }else{
        $message = "";
    }

    if(is_ajax()){
        echo json_encode(array("status" => "error", "message" => "页面不存在 " . $message));
        exit;
    }

    echo $message;
    echo "<br>page not found";
    exit;
}

function site_url($uri){
    return Conf::get('app', 'base_url') . $uri;
}

/**
 * 静态资源
 * @param type $uri
 * @param type $base  默认  app
 *                      app 应用资讯
 *                      common  公共资源
 * @return type
 */
function static_url($uri , $base = "app"){
    if($base == "app"){
        $conf = "app_static_url";
    }elseif($base == "common"){
        $conf = "com_static_url";
    }
    return Conf::get('app', $conf) . $uri . "?v=" . Conf::get('app','version');
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


/**
 * 跳转
 * @param type $uri
 */
function redirect($uri = ""){
    header("Location: " . site_url($uri));
    exit;
}

/**
 * 数组变成一个树
 * @param type $items
 * @param boolean $format $items 是否是  格式话好的二维数组数据 数组的一维key为 id值
 * @return type
 */
function convert2tree($items, $format = false) {

    if (!$format) {
        $tmp = array();
        foreach ($items as $vo) {
            $tmp[$vo['id']] = $vo;
        }
        $items = $tmp;

    }

    foreach ($items as $item) {
        $items[$item['pid']]['child'][$item['id']] = &$items[$item['id']];
    }
    return isset($items[0]['child']) ? $items[0]['child'] : array();
}
