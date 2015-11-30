<?php

namespace wumashi\core;

/**
 *
 * @author 五马石
 */
class Conf {

    private static $__load = array();
    private static $__data = array();

    /**
     * 获取配置, 先获取五马石下的配置 在获取 app下的配置 
     * @param type $name
     * @param type... $name
     * @return type
     */
    public static function get($name) {

        //过滤文件名
        $filter_name = preg_replace("/[^0-1a-zA-Z]/", "", $name);

        if (!isset(self::$__load[$filter_name])) {

            // 开发模式用 local下的配置
            $dir       = ENV == "development" ? ($filter_name == "url" ? "conf" : "conf/local") : "conf" ;
            
            
            $app_conf  = APP_PATH . "$dir/{$filter_name}.conf.php";
            $core_conf = WUMASHI_PATH . "$dir/{$filter_name}.conf.php";

            if (is_file($core_conf)) {
                $data = include $core_conf;
                self::set($filter_name, $data, true);
            }

            if (is_file($app_conf)) {
                $data = include $app_conf;
                self::set($filter_name, $data, true);
            }

            self::$__load[$filter_name] = true;
        }

        $args = func_get_args();
        $conf = self::$__data;

        foreach ($args as $n) {
            $conf = isset($conf[$n]) ? $conf[$n] : null;
        }

        return $conf;
    }

    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $append true合并  false 赋值
     */
    public static function set($name, $value, $append = false) {
        if ($append) {
            self::$__data[$name] = array_merge(isset(self::$__data[$name]) ? self::$__data[$name] : array(), $value);
        } else {
            self::$__data[$name] = $value;
        }
    }

}
