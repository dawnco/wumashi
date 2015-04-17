<?php

namespace wumashi\core;

/**
 * 钩子类
 * 加载钩子和执行钩子
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-30
 */
class Hook{

    private $__hooks = null, $__request, $___hook_class;

    /**
     *
     * @param Request $request
     */
    public function __construct($request){
        $this->__request = $request;
    }

    /**
     * 加载钩子
     * $this->_route->getUri()
     */
    public function load(){
        $hooks = Conf::get("hook");

        foreach ($hooks as $preg => $hook){
            if (preg_match("#^$preg$#i", $this->__request->getUri())){
                $this->__hooks[$hook['weld']][] = $hook;
            }
        }
    }

    /**
     * 执行钩子
     * @param type $name 钩子名称
     */
    public function trigger($name){

        //没有钩子返回false
        if (!isset($this->__hooks[$name])){
            return false;
        }
        
        //排序 升序排
        usort($this->__hooks[$name], array($this, "__sort"));
                
        foreach ($this->__hooks[$name] as $hook){

            $hook_class_name = $hook['h'];
            $method          = isset($hook['m']) ? $hook['m'] : "hook";
            if (!isset($this->___hook_class[$hook_class_name])){
                $this->___hook_class[$hook_class_name] = new $hook_class_name();
            }
            //执行
            call_user_func_array(array($this->___hook_class[$hook_class_name], $method), $this->__request->getParam());
        }
    }
    
    /**
     * 排序
     */
    private function __sort($a, $b) {
        if ($a['seq'] == $b['seq']) return 0;
	return $a['seq'] > $b['seq'] ? 1 : -1;	// 按升序排列
    }

}
