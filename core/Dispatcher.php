<?php

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-10
 * Description:
 */


class Dispatcher {

    protected $_request, $_hook;

    function __construct(Request $request) {
        $this->_request = $request;
        
        $this->_hook  = new Hook($this->_request);
        $this->_hook->load();

        
    }

    /**
     * 执行
     */
    function run() {
       
        $controlFile = $this->_request->getControlFile();
        $control     = $this->_request->getControl();
        $method      = $this->_request->getMethod();
        $param       = $this->_request->getParam();
        
        $this->_hook->trigger("pre_control");

        $controlFile = APP_PATH . "control/" .$controlFile . "Control.php";
        if(is_file($controlFile)){
            include $controlFile;
            $controlCls = $control ."Control";
            $classInstance = new $controlCls($this->_request);
            if (method_exists($classInstance, $method)) {
                call_user_func_array(array($classInstance, $method), $param);
            } else {
                show_404($control . "->". $method ."() Method Not Found");
            }
        }else{
            show_404($controlFile ." File Not Found");
        }

        $this->_hook->trigger("after_control");
    }

}
