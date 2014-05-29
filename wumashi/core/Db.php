<?php

/**
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-15
 * @version: 1.0
 * @Description
 */
abstract class Db {

    private static $__instance = null;

    /**
     * 获取Db实例
     * @param type $conf 配置名
     * @param type $type 数据库类型
     * @return Db
     */
    public static function getInstance($conf = "default", $type = "Mysql"){
        if (!isset(self::$__instance[$type][$conf])) {
            self::$__instance[$type][$conf] = new $type(Conf::get("db", $conf));
        }
        return self::$__instance[$type][$conf];
    }

    /**
     * 关闭数据库
     * @param string $conf
     * @param string $type
     */
    public function close($conf = "default", $type = "Mysql"){
        if (isset(self::$__instance[$type][$conf])) {
            self::$__instance[$type][$conf]->close();
            self::$__instance[$type][$conf] = null;
        }
    }

    /**
     * 获取一个值
     * @param type $query
     * @param array $data 预定义参数
     */
    abstract function getVar($query, $data = null);

     /**
     * 获取一行数据
     * @param type $query
     * @param array $data 预定义参数
     */
    abstract function getLine($query, $data = null);

    /**
     * 获取数据
     * @param type $query
     * @param array $data 预定义参数
     */
    abstract function getData($query, $data = null);

    /**
     * 快捷查询
     * @param string $table
     * @param string $value
     * @param string $index
     * @param string $field
     */
    abstract function getLineBy($table, $value, $index = "id", $field = "*");

    /**
     * 插入sql
     * @param string $table
     * @param array $data
     * @return type
     */
    abstract function insert($table, $data);

    /**
     * 更新sql
     * @param string $table
     * @param array $data
     * @param mix $where  数组 或者 字符串  字符串则表示ID
     * @return type
     */
	abstract function update($table, $data, $where);

    /**
     * 删除
     * @param string $table 表名
     * @param mix $where 条件 或者 字符串  字符串则表示ID
     */
    abstract function delete($table, $where);

     /**
     * 转义安全字符
     * @param string $val
     * @return type
     */
    abstract function escape($val);

    /**
     * 预编译sql语句 ?i 表示int ?s 字符串
     * @param string $query
     * @param array string $data
     * @return type
     */
    abstract function prepare($query, $data = null);

    /**
     * 执行sql
     * @param type $query
     * @param type $data
     * @return boolean
     */
    abstract function exec($query, $data = null);
}
