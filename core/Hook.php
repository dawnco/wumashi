<?php

/**
 * 钩子类
 * 加载钩子和执行钩子
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-30
 */
class Hook {

    private $__hooks = null, $__route, $___hook_class;

    /**
     *
     * @param Route $route
     */
    public function __construct($route){
        $this->__route = $route;
    }
    /**
     * 加载钩子
     * $this->_route->getUri()
     */
    public function load() {
        $hooks = Conf::get("hook");

        foreach ($hooks as $preg => $hook) {
            if (preg_match("#^$preg$#i", $this->__route->getUri())) {
                $this->__hooks[$hook['weld']][] = $hook;
            }
        }
    }

    /**
     * 执行钩子
     * @param type $name 钩子名称
     */
    public function trigger($name) {

        //没有钩子返回false
        if(!isset($this->__hooks[$name])){
            return false;
        }

        foreach ($this->__hooks[$name] as $hook) {

            $hook_class_name    = $hook['h'] . "Hook";
            $method             = isset($hook['m']) ? $hook['m'] : "hook";
            if (!isset($this->___hook_class[$hook_class_name])) {
                $this->___hook_class[$hook_class_name] = new $hook_class_name($this->__route);
            }
            //执行
            call_user_func_array(array($this->___hook_class[$hook_class_name], $method), $this->__route->getParam());

        }
    }

}
