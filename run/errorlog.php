<?php

/**
 * 自定义错误
 * @author: 五马石 <abke@qq.com> 
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-17
 * @version: 1.0
 * @Description 
 */


function error_handler($errno, $errstr, $errfile, $errline) {
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

    if (ENV == "development") {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }else{
        echo 'PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline;
    }


    return true;
}

// Error Handler
set_error_handler('error_handler');