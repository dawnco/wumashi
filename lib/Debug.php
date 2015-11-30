<?php

namespace wumashi\lib;

/**
 * 调试错误信息
 * @author  Dawnc
 * @date    2014-08-29
 */
class Debug {

    /**
     * 写日志
     * @param type $msg
     * @return boolean
     */
    public static function writeLog($msg) {

        if (ENV == "development") {
            echo "<pre>\n";
            echo $msg;
            echo "\n</pre>";
        }

        $log_file = ROOT . "data/log_error.log";
        if (!$fp       = @fopen($log_file, 'a')) {
            return false;
        }

        $arr = explode("\n", $msg);
        $w   = array();
        $pad = str_pad("", 22, " ");
        foreach ($arr as $vo) {
            $w[] = $pad . $vo;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, date("Y-m-d H:i:s") . " " . implode("\n", $w) . "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * 程序结束时获取最近一次致命错误
     */
    public static function shutDownError() {
        $err = error_get_last();
        if (!$err) {
            return false;
        }

        //普通错误不记录
        if (in_array($err['type'], array(E_USER_NOTICE, E_NOTICE))) {
            return false;
        }

        self::writeLog(self::error2Name($err['type']) . "\n" . var_export($err, 1));
    }

    /**
     * 启动自定义错误调试
     */
    public static function start() {
        set_error_handler(array("\\wumashi\\lib\\Debug", "handler"));
        register_shutdown_function(array("\\wumashi\\lib\\Debug", "shutDownError"));
    }

    /**
     * 自定义错误处理
     * @param type $errno
     * @param type $errstr
     * @param type $errfile
     * @param type $errline
     * @return boolean
     */
    public static function handler($errno, $errstr, $errfile, $errline) {
        $type_name = self::error2Name($errno);
        $msg       = $type_name . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline . "\n";
        self::writeLog($msg);
        return true;
    }

    public static function error2Name($error_type) {
        //错误代码
        $levels = array(
            E_ERROR             => 'Error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parsing Error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core Error',
            E_CORE_WARNING      => 'Core Warning',
            E_COMPILE_ERROR     => 'Compile Error',
            E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Runtime Notice',
            E_RECOVERABLE_ERROR => 'Recover Error',
        );

        return isset($levels[$error_type]) ? $levels[$error_type] : "";
    }

    /**
     * 输出变了内容
     * @param type $var 
     * @param type $name 变量名
     */
    public static function dump($var, $name = "") {
        self::writeLog($name . "\r\n" . var_export($var, 1));
    }

    public static function log($message, $who = "") {

        $file     = ROOT . "data/log_info.log";
        $_message = is_array($message) ? implode("\n", $message) : $message;

        $content = "\n" . date("Y-m-d H:i:s");
        $content .= "\n" . $who;
        $content .= "\n" . $_message . "\n";

        //5M
        if (is_file($file) && filesize($file) < 1024 * 1024 * 5) {
            file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($file, $content, LOCK_EX);
        }
    }

}
