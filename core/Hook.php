<?php

namespace wumashi\core;

/**
 * 钩子类
 * 加载钩子和执行钩子
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-30
 */
class Hook {

    private static $__callbacks = [];

    /**
     * 
     * @param type $name
     * @param type $callback
     * @param type $seq 按升序
     * @param type $parameter  
     */
    public static function addAction($name, $callback, $seq = 10, $parameter = []) {
        self::__setCallbacks("action", $name, [
            "callback"  => $callback,
            "seq"       => $seq,
            "parameter" => $parameter,
                ]
        );
    }

    /**
     * 执行action
     * @param type $name
     * @param type $parameter
     */
    public static function doAction($name, $parameter = []) {
        foreach (self::__getCallbacks("action", $name) as $k => $c) {
            //执行
            call_user_func_array($c['callback'], array_merge($c['parameter'], $parameter));
        }
    }

    public static function addFilter($name, $callback, $seq = 10, $parameter = []) {
        self::__setCallbacks("filter", $name, [
            "callback" => $callback,
            "seq"      => $seq
        ]);
    }

    /**
     * 执行过滤
     * @param type $name
     * @param type $value
     * @return type
     */
    public static function applyFilter($name, $value, $parameter = []) {
        foreach (self::__getCallbacks("filter", $name) as $k => $c) {
            //执行
            $value = call_user_func_array($c['callback'], array_merge([$value], $parameter));
        }
        return $value;
    }

    private static function __getCallbacks($type, $name) {

        //没有钩子返回false
        if (!isset(self::$__callbacks[$type][$name])) {
            return [];
        }
        $callbacks = self::$__callbacks[$type][$name];

        usort($callbacks, array(self, "__sort"));
        return $callbacks;
    }

    private static function __setCallbacks($type, $name, $callbacks) {
        if (!isset(self::$__callbacks[$type])) {
            self::$__callbacks[$type] = [];
        }

        if (!isset(self::$__callbacks[$type][$name])) {
            self::$__callbacks[$type][$name] = [];
        }


        self::$__callbacks[$type][$name][] = $callbacks;
    }

    /**
     * 按升序排列
     * @param type $a
     * @param type $b
     * @return int
     */
    private static function __sort($a, $b) {
        if ($a['seq'] == $b['seq']) {
            return 0;
        }
        return $a['seq'] > $b['seq'] ? 1 : -1; // 按升序排列
    }

}
