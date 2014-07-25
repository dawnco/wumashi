<?php

/**
 * 数据库抽象类
 * 扩展数据库继承该类
 * @author Dawnc <abke@qq.com>
 * @date 2013-11-23
 */
abstract class Db {

    private static $__instance = null;
    
    /**
     *
     * @var 执行过的sql 
     */
    public $sql     = null;
    /**
     * 获取Db实例
     * @param type $conf 配置名
     * @param type $type 数据库类型
     * @return Db
     */
    public static function getInstance($conf = "default"){
        
        if (!isset(self::$__instance[$conf])) {
            $option = Conf::get("db", $conf);
            $type   = isset($option['driver']) ?  $option['driver'] : "Mysql";
            self::$__instance[$conf] = new $type($option);
        }
        return self::$__instance[$conf];
    }

    /**
     * 关闭数据库
     * @param string $conf
     * @param string $type
     */
    public static function shut($conf = "default"){
        if (isset(self::$__instance[$conf])) {
            self::$__instance[$conf]->close();
            self::$__instance[$conf] = null;
        }
    }
    
    public function debug() {
        var_dump($this->sql);
    }

    /**
     * 获取一个值
     * @param type $query
     * @param array $bind 预定义参数
     */
    abstract function getVar($query, $bind = null);

     /**
     * 获取一行数据
     * @param type $query
     * @param array $bind 预定义参数
     */
    abstract function getLine($query, $bind = null);

    /**
     * 获取数据
     * @param type $query
     * @param array $bind 预定义参数
     * @return array
     */
    abstract function getData($query, $bind = null);

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
     * @param type $bind
     * @return boolean
     */
    abstract function exec($query, $bind = null);
}
