<?php

/**
 * 数据库配置
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-23
 */

if(defined("SAE_MYSQL_HOST_M")){
    return array(
            "default" => array(
                "hostname"          => SAE_MYSQL_HOST_M,
                "username"          => SAE_MYSQL_USER,
                "password"          => SAE_MYSQL_PASS,
                "database"          => SAE_MYSQL_DB,
                "port"              => SAE_MYSQL_PORT,
                "charset"           => "UTF8",
    ),
        );
}else{

return array(
     "default" => array(
        "hostname"          => "127.0.0.1",
        "username"          => "root",
        "password"          => "123456",
        "database"          => "blog",
        "port"              => "3306",
        "charset"           => "UTF8",
    )
);

}