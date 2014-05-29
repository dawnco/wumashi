<?php

/**
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-13
 * @version: 1.0
 * @Description
 */

return array(
    "default"                       => array('c' => "Default"),
    "index.html"                    => array('c' => "Default"),
    "blog/archives/(\d+).html"      => array('c' => "blog/Post", "m" => "detail"),
    "blog/tag/(.+)"                 => array('c' => "blog/Tag", ),
);