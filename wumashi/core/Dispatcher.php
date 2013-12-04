<?php

/**
 * @author: 五马石 <abke@qq.com> 
 * Time: 2013-8-10
 * Description: 
 */


class Dispatcher {

    protected $_request, $_hook, $_route;

    function __construct($request) {
        $this->_request = $request;
//        $this->_route   = $route;
//        $this->_hook    = $hook;
    }

    //执行
    function run() {
        if (isset($this->_request->get['s'])) {
            $uri = $this->_request->get['s'];
        } else {
            $uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "";
        }
        $uri = trim($uri, " /");
        
        $this->_route = new Route($uri);
        
        $this->_hook  = new Hook($this->_route);
        
        $this->_hook->load();
        
        $control = $this->_route->getControl();
        $method  = $this->_route->getMethod();
        $param   = $this->_route->getParam();

        $this->_request->context['control'] = $control;
        $this->_request->context['method']  = $method;
        $this->_request->context['param']   = $param;
        
        $this->_hook->trigger("pre_control");
        
        $controlFile = APP_PATH . "control/" . ucfirst(strtolower($control)) . "Control.php";
        if(is_file($controlFile)){
            include $controlFile;
            $controlCls = $control ."Control";
            $classInstance = new $controlCls($this->_request);
            if (method_exists($classInstance, $method)) {
                call_user_func_array(array($classInstance, $method), $this->_request->context['param']);
            } else {
                show_404($control . " : Method ". $method ." Not Found");
            }
        }else{
            show_404($controlFile ." File Not Found");
        }
        
        $this->_hook->trigger("after_control");
    }
    
  
    
    
    
    

}
