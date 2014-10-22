<?php

/**
 * 目录操作类
 * @author  Dawnc
 * @date    2014-06-24
 */
class Dire {
    
    /**
     * 读取目录下的文件
     * @param type $dir
     * @return type
     */
    public static function read($dir) {
        $files = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..')
                        $files[] = $file;
                }
                closedir($dh);
            }
        }
        return $files;
    }
    
    /**
     *  读取dir下所有文件
     * @staticvar array $allfiles
     * @param type $dir
     * @param type $mask  /(.+)\.css$/
     * @return string 返回含路径的文件名数组
     */
    public static function readIterative($dir, $mask = null) {

        $dir             = rtrim($dir, "/ ");
        static $allfiles = array();
        $d               = opendir($dir);
        while (($file            = readdir($d)) !== false) {

            if ($file == '.' || $file == '..') {
                continue;
            }

            if (is_dir($dir . '/' . $file)) {
                self::readIterative($dir . '/' . $file, $mask);
                continue;
            } else {
                if ($mask && !preg_match($mask, $file)) {
                    continue;
                }
                $allfiles[] = $dir . '/' . $file;
            }
        }
        closedir($d);
        return $allfiles;
    }

    /**
     * 迭代删除目录下的文件
     * @param type $dir
     */
    public static function deleteIterative($dir) {
        $dh   = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::deleteIterative($fullpath);
                }
            }
        }
        closedir($dh);
    }
    
    /**
     * 复制文件到指定路径
     * @param type $src
     * @param type $des
     */
    public static function copy($src, $des) {
        $dir = dirname($des);
        if (!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        return copy($src, $des);
    }
}
