<?php

/**
 *
 * @author Dawnc <abke@qq.com>
 */
function input($key) {
    return isset($_POST[$key]) ? $_POST[$key] :
            (isset($_GET[$key]) ? $_GET[$key] : false );
}

function s_input($key) {
    return strip_tags(urldecode(input($key)));
}

/**
 * 是否ajax请求
 * @return boolean
 */
function is_ajax() {
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
        return true;
    } else {
        return false;
    }
}

function show_404($str) {

    if (ENV == "development") {
        $message = $str;
    } else {
        $message = "";
    }

    header("HTTP/1.0 404 Not Found");

    if (is_ajax()) {
        echo json_encode(array("status" => "error", "message" => "页面不存在 " . $message));
        exit;
    }

    echo $message;
    echo "<br>page not found";
    exit;
}

function site_url($uri = "") {
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
function static_url($uri, $base = "app", $version = true) {
    if ($base == "app") {
        $conf = "app_static_url";
    } elseif ($base == "common") {
        $conf = "com_static_url";
    }
    return Conf::get('app', $conf) . $uri . ($version ? ("?v=" . Conf::get('app', 'version')) : "");
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
 */
function redirect($uri = "", $local = true) {
    if ($local) {
        header("Location: " . site_url($uri));
    } else {
        header("Location: " . $uri);
    }
    exit;
}

/**
 * 数组变成一个树
 * @param type $items
 * @param boolean $format $items 是否是  格式话化的二维数组数据 数组的一维key为 id值
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

/**
 * 产生随机数字字母字符串
 *  assic 值
 *  65 - 90 A-Z
 *  97 - 122 a-z
 *  48 - 57 0-9
 * @param type $length
 */
function create_rand_string($length = 8) {
    $str  = "";
    $step = $length / 2;
    for ($i = 0; $i < $step; $i++) {
        $str .= chr(rand(65, 90));
        $str .= chr(rand(97, 122));
        $str .= chr(rand(48, 57));
        $str .= chr(rand(97, 122));
    }

    return substr($str, 0, $length);
}

/**
 * 根据条件拼接sql where片段
 * 主要解决前台可选一项或多项条件进行查询时的sql拼接
 * 
 * 拼接规则：
 * 's'=>sql缩写，必须，伪sql片段，$1..$n为反向引用，引用后面的值数组
 * 'f'=>fill缩写，必须，sql片段中要填充的值
 * 'c'=>condition缩写，选填，默认判断不为空，如果设置了条件则用所设置的条件
 * 
 * $factor_list = array(
 * 		array('s'=>'and a.id=$1', 'f'=>12 ),
 * 		array('s'=>"and a.name like '%$1'", 'f'=>'peng'),
 * 		array('s'=>'and a.age > $1', 'f'=>18),
 * 		array('s'=>'or (a.time>$1 and a.time<$2)', 'f'=>array(2186789, 389876789), 'c'=>(1==1) )
 * );
 * @param array $factor_list
 */
function sql_where(array $factor_list) {
    $where_sql = '1=1';

    foreach ($factor_list as $factor) {
        // 如果用户没有设置条件，默认条件为填充值不能为空
        // 如果用户设置了条件，则使用用户所设置的条件
        $condition = isset($factor['c']) ? $factor['c'] : !empty($factor['f']);
        if ($condition) {
            $sql_part = $factor['s'];
            if (is_array($factor['f'])) {
                $i = 1;
                foreach ($factor['f'] as $v) {
                    $sql_part = str_replace('$' . $i, s($v), $sql_part);
                    $i++;
                }
            } else {
                $sql_part = str_replace('$1', $factor['f'], $sql_part);
            }
            $where_sql .= " {$sql_part} ";
        }
    }

    return $where_sql;
}
