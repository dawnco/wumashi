<?php

/**
 * @author  Dawnc
 * @date    2015-11-16
 */

/**
 * 用户头像 
 * @param type $uid
 * @param type $type
 * @return type
 */
function avatar_url($uid, $type = "") {
    return wumashi\core\Conf::get("app", "avatar_url") . "?uid=$uid&size=$type";
}


function wms_date($time, $format = "Y-m-d H:i:s"){
    return date($format, $time);
}