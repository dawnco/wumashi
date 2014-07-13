<?php

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-11
 * Description:
 */
class Request{

    /** 路由 */
    private $__route = null;

    /** 响应的 控制器文件 */
    private $__controlFile;

    /** 控制器 */
    private $__control;

    /** 方法 */
    private $__method;

    /** 参数 */
    private $__param;

    /** uri 资源 */
    private $__uri;
    public $post, $get, $request, $cookie;

    public function __construct(Route $route){
        $this->post    = $_POST;
        $this->get     = $_GET;
        $this->request = $_REQUEST;
        $this->cookie  = $_COOKIE;


        $this->__route = $route;
        $this->__initUri();
        $this->__route->setUri($this->__uri);
        $this->__route->run();

        $this->__controlFile = $this->__route->getControlFile();
        $this->__control     = $this->__route->getControl();
        $this->__method      = $this->__route->getMethod();
        $this->__param       = $this->__route->getParam();
    }

    /*
     * 获取uri
     */

    private function __initUri(){

        if (isset($this->get['s'])){
            $uri = $this->get['s'];
        } elseif (isset($_SERVER['PATH_INFO'])){
            $uri = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_SCRIPT_URL'])){
            $uri = $_SERVER['REDIRECT_SCRIPT_URL'];
        } elseif (isset($_SERVER['REDIRECT_URL'])){
            $uri = $_SERVER['REDIRECT_URL'];
        } else{
            $uri = "";
        }

        $this->__uri = trim($uri, " /");
    }

    /**
     * 获取请求的uri
     * @return type
     */
    public function getUri(){
        return $this->__uri;
    }

    public function getControlFile(){
        return $this->__controlFile;
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

}
