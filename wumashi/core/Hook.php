<?php

/**
 * 钩子类
 * 加载钩子和执行钩子
 * @author WuMaShi.com
 * @date 2013-11-30
 */
class Hook {

    private $__hooks = null, $__route, $__load_hook_file;

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
        $hooks = include APP_PATH . "conf/hook.conf.php";
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
            //加载文件
            $hook_file = APP_PATH . "hook/" . $hook['file'];
            if(!$this->__load_hook_file[$hook_file]){
                $this->__load_hook_file[$hook_file] = $hook_file;
                require $hook_file;
            }
            //执行
            $fn = $hook['fn'];
            call_user_func_array($fn , $this->__route->getParam());
        }
    }

}
