<?php

/**
 * URL 路由
 * @author  Dawn
 * @date    2013-11-25
 */
class Route {

    private $__control, $__method, $__param, $__uri;

    public function __construct($uri = ''){
        
        $this->__uri = trim($uri," /");
        
        $routed = false;
        $rules  = require ROOT . 'conf/url.conf.php';
        //是否配置过路由
        foreach($rules as $muri => $rule){
            $matches = array();
            if(preg_match("#^$muri$#", $this->__uri, $matches)){
                $this->__control    = $rule['c'];
                $this->__method     = isset($rule['m']) ? $rule['m'] : "index";

                $url_param          = array_slice($matches, 1);
                
                //合并参数
                $prarm                = array();
                if(isset($rule['p'])){
                     $prarm       = array_merge($url_param, (array)$rule['p']);
                }
                $this->__param      = $prarm;
                $routed             = true;
                break;
            }
        }

        //默认路由
        if(!$routed){
           $info                    = preg_split("/[-\/]/", $this->__uri);
           $this->__control         = (isset($info[0]) && $info[0]) ? $info[0] : "Default";
           $this->__method          = (isset($info[1]) && $info[1]) ? $info[1] : "index";
           $this->__param           = array_slice($info, 2);
        }
    }

    public function getGroup(){
        return $this->__group;
    }
    public function getControl(){
        return $this->__control;
    }

    public function getMethod(){
        return $this->__method;
    }

    public function getParam(){
        return $this->__param;
    }
    
    public function getUri(){
        return $this->__uri;
    }

}
