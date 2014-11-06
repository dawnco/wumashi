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
    * 根据条件拼接sql where片段
    * 主要解决前台可选一项或多项条件进行查询时的sql拼接
    * 
    * 拼接规则：
    * 's'=>sql，必须，伪sql片段，$1..$n为反向引用，引用后面的值数组
    * 'v'=>值缩写，必须，sql片段中要填充的值
    * 'c'=>条件，选填，默认判断不为空，如果设置了条件则用所设置的条件
    * 
    * $factor_list = array(
    * 		array('s'=>'and a.id=?i', 'v'=>12 ),
    * 		array('s'=>"and a.name like '%$p'", 'v'=>'peng'),
    * 		array('s'=>'and a.age > ?i', 'v'=>18),
    * 		array('s'=>'or (a.time > ?s and a.time < ?s )', 'v'=>array('2014', '2015'), 'c'=>(1==1) )
    * );
    * @param array $factor_list
    */
    public function where($factor_list) {
        $where_sql = ' 1=1';
        foreach ($factor_list as $factor) {
            // 如果用户没有设置条件，默认条件为填充值不能为空
            // 如果用户设置了条件，则使用用户所设置的条件
            $condition = isset($factor['c']) ? $factor['c'] : !empty($factor['v']);
            
            if ($condition) {
                $where_sql .= " " . $this->prepare($factor['s'], $factor['v']);
            }
        }
        return $where_sql;
    }
    
        /**
     * 预编译sql语句 ?i 表示int ?s 字符串 ?p 原始sql
     * @param string $query
     * @param array string $data
     * @return type
     */
    public function prepare($query, $data = null){
        if($data === null){
            return $query;
        }elseif (!is_array($data)){
            $data  = func_get_args();
            $query = array_shift($data);
        }

        $query = str_replace(array('?i', '?s', '?p'), array('%d', '"%s"', '%s'), $query);
        foreach ($data as $k => $v){
            $data[$k] = $this->escape($v);
        }
        return vsprintf($query, $data);
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
     * 执行sql
     * @param type $query
     * @param type $bind
     * @return boolean
     */
    abstract function exec($query, $bind = null);
}
