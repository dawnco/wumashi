<?php

/**
 *
 * @author  Dawnc
 * @date    2014-04-30
 */

function __autoload($class){


    //控制器
    if(substr($class, -7) == "Control"){
        $libclass = APP_PATH . "control/$class.php";
        if(is_file($libclass)){
            require $libclass;
        }
        return true;
    }


    //模型
    if(substr($class, -5) == "Model"){
        $libclass = APP_PATH . "model/$class.php";
        if(is_file($libclass)){
            require $libclass;
        }else{
            require CORE_PATH . "model/$class.php";
        }
        return true;
    }

    //钩子类
    if(substr($class, -4) == "Hook"){
        $libclass = APP_PATH . "hook/$class.php";
        if(is_file($libclass)){
            require $libclass;
        }else{
            require CORE_PATH . "hook/$class.php";
        }
        return true;
    }

    //库
    $libclass = APP_PATH . "lib/$class.lib.php";
    if(is_file($libclass)){
        require $libclass;
    }else{
        require CORE_PATH . "lib/$class.lib.php";
    }
    return true;

}
