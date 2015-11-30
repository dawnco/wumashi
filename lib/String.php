<?php

namespace wumashi\lib;

/**
 *
 * @author Dawnc
 * @date   2014-09-30
 */
class String{
    
    
    /**
     * 获取 $text 中  $start 开始到  $end 结束 之间的 字符串
     * @param string $text
     * @param string $start
     * @param string $end
     * @return string
     */
    public static function substr($text, $start, $end){
        $index_start = strpos($text, $start);
        $index_end   = strpos($text, $end);
        return substr($text, $index_start + strlen($start), $index_end - $index_start);
    }
    
}
