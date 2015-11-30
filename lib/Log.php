<?php

namespace wumashi\lib;

/**
 * @author  Dawnc
 * @date    2015-05-06
 */
class Log {

    public static function debug($message) {
        self::__write("DEBUG", $message);
    }

    public static function error($message) {
        self::__write("DEBUG", $message);
    }

    public static function info($message) {
        self::__write("INFO", $message);
    }

    private static function __write($type, $message) {

        $log_file = ROOT . "data/log.log";

        $fp       = @fopen($log_file, 'a');

        if (!$fp) {
            return false;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, date("Y-m-d H:i:s") . " " . $type . " " . $message . "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}
