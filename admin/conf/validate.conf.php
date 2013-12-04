<?php

/**
 *
 * @author WuMaShi.com
 * @date 2013-11-23
 */

/**
 * 基于控制器验证
 * @author Dawn.co

 *  * 验证规则 格式
 * array(
 *   "验证入口( 控制器.方法 不区分大小写)" => array(
 *          array("验证字段","规则", "验证不通过的错误信息"),
 *    )
 * )  
 */
return array(
    'Login.index' => array(
        array('username', "rangelen:1,100", "账号不能为空"),
        array('password', 'rangelen:1,100', '密码不能为空'),
    ),
);
