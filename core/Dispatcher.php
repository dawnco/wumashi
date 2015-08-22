<?php

namespace wumashi\core;

/**
 * @author: 五马石 <abke@qq.com>
 * Time: 2013-8-10
 * Description:
 */
class Dispatcher{

    protected $_request;

    function __construct(Request $request){
        $this->_request = $request;

    }

    /**
     * 执行
     */
    function run(){

        $control     = $this->_request->getControl();
        $method      = $this->_request->getMethod();
        $param       = $this->_request->getParam();

        Hook::doAction("pre_control", $param);

        if (class_exists($control)){
            $classInstance = new $control();
            if (method_exists($classInstance, $method)){
                call_user_func_array(array($classInstance, $method), $param);
            } else{
                throw new \wumashi\core\Exception($control . "->" . $method . "() Method Not Found", 404);
            }
        } else{
            throw new \wumashi\core\Exception($control . " File Not Found", 404);
        }
        
        Hook::doAction("after_control", $param);

    }

}
