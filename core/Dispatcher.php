<?php

namespace wumashi\core;

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-10
 * Description:
 */
class Dispatcher{

    protected $_request, $_hook;

    function __construct(Request $request){
        $this->_request = $request;

        $this->_hook = new Hook($this->_request);
        $this->_hook->load();
    }

    /**
     * 执行
     */
    function run(){

        $control     = $this->_request->getControl();
        $method      = $this->_request->getMethod();
        $param       = $this->_request->getParam();

        $this->_hook->trigger("pre_control");
        
        if (class_exists($control)){
            $classInstance = new $control();
            if (method_exists($classInstance, $method)){
                call_user_func_array(array($classInstance, $method), $param);
            } else{
                show_404($control . "->" . $method . "() Method Not Found");
            }
        } else{
            show_404($control . " File Not Found");
        }

        $this->_hook->trigger("after_control");
    }

}
