<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-24
 */
$pattern ="#^(login/index)$#";
$subject = 'login/index';

$r = preg_match($pattern, $subject, $matches);
var_dump($r);
var_dump($matches);