<?php

namespace wumashi\core;
/**
 *
 * @author 五马石
 */
class Conf{

    private static $__files = array();
    private static $__data  = array();

    /**
     * 获取配置, 先获取五马石下的配置 在获取 app下的配置 
     * @param type $name
     * @param type... $name
     * @return type
     */
    public static function get($name){
        
        //过滤文件名
        $name = preg_replace("/[^0-1a-zA-Z]/", "", $name);
        
        if (!isset(self::$__files[$name])){
            
            self::$__files[$name] = APP_PATH . "conf/{$name}.conf.php";

            if (!is_file(self::$__files[$name])){
                trigger_error("can't find {$name}.conf.php");
            }
            
            
            $core_conf = CORE_PATH . "conf/{$name}.conf.php";
            if(is_file($core_conf)){
                $data = include $core_conf;
                self::set($name, $data, true);
            }
            
            $data = include self::$__files[$name];
            self::set($name, $data, true);
        }

        $args = func_get_args();
        $conf = self::$__data;

        foreach ($args as $n){
            $conf = isset($conf[$n]) ? $conf[$n] : null;
        }

        return $conf;
    }
    
    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $append 合并或者复制
     */
    public static function set($name, $value, $append = false){
        if($append){
            self::$__data[$name] = array_merge(isset(self::$__data[$name]) ? self::$__data[$name] : array(), $value);
        }else{
            self::$__data[$name] = $value;
        }
    }

}
