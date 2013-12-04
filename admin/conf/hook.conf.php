<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */

return array(
     ".*" => array(
        "weld" => "pre_control",
        "file" => "app.hook.php",
        "fn"   => "start_session",
    ),
    "(?!login)" => array(
        "weld" => "pre_control",
        "file" => "app.hook.php",
        "fn"   => "hook_auth",
    ),
    
);