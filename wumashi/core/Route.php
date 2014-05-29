<?php

/**
 * URL 路由
 * @author  Dawnc
 * @date    2013-11-25
 */
class Route {

    private $__control  = "",
            $__method   = "index",
            $__param    = array(),
            $__uri      = null,
            $__controlFile = "";

    public function __construct($uri = ''){

        $this->__uri = trim($uri," /");



        $routed = false;
        $rules  = Conf::get("url");


        //默认
        if($this->__uri == ''){
            $this->__controlFile = "Default";
            $routed          = true;
        }

        //是否配置过路由
        foreach($rules as $muri => $rule){
            $matches = array();
            if(preg_match("#^$muri$#", $this->__uri, $matches)){
                $this->__prase($rule, $matches);
                $routed             = true;
                break;
            }
        }


        $has_sp = strrpos($this->__controlFile, "/");
        if ($has_sp === false) {
            $this->__control = $this->__controlFile;
        } else {
            $this->__control = substr($this->__controlFile, $has_sp + 1);
        }

        if(!$routed){
            show_404("not route");
        }


        //默认路由
//        if(!$routed){
//           $info                    = preg_split("/[-\/]/", $this->__uri);
//           $this->__control         = (isset($info[0]) && $info[0]) ? $info[0] : "Default";
//           $this->__method          = (isset($info[1]) && $info[1]) ? $info[1] : "index";
//           $this->__param           = array_slice($info, 2);
//        }
    }

    /**
     * 解析uri
     * @param type $rule
     * @param type $matches
     */
    private function __prase($rule, $matches = array()){
        $this->__controlFile    = $rule['c'];
        $this->__method         = isset($rule['m']) ? $rule['m'] : $this->__method;

        $url_param              = array_slice($matches, 1);
        //合并参数
        $prarm                = array();
        if(isset($rule['p'])){
             $prarm       = array_merge($url_param, (array)$rule['p']);
        }else{
             $prarm      = $url_param;
        }
        $this->__param      = $prarm;
    }

    public function getGroup(){
        return $this->__group;
    }

    public function getControl(){
        return $this->__control;
    }

    public function getControlFile(){
        return $this->__controlFile;
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
