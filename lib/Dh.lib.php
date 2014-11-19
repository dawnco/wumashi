<?php

/**
 * Mysql Database Handle 快捷插入和更新
 * @author Dawnc
 * @date   2014-11-19
 */
class Dh{

    //缓存的表 字段 array ("field"=>字段数组 , "pri" => 主键名)
    private static $__field = array();
    
    /**
     * 获取表信息
     * @param type $table
     */
    private static function __initTableInfo($table){
        
        if(!isset(self::$__field[$table])){
            
            $db = Db::getInstance();
            
            self::$__field[$table]['field'] = array();
            
            $data = $db->getData("desc `$table`");
            foreach($data as $vo){
                self::$__field[$table]['field'][] = $vo['Field'];
                if($vo['Key'] == 'PRI'){
                    self::$__field[$table]['pri'] = $vo['Field'];
                }
            }
            
        }
    }
    
    /**
     * 获取字段名
     * @param string $table
     * @return array
     */
    public static function field($table){
        self::__initTableInfo($table);
        return self::$__field[$table]['filed'];
    }
    
    /**
     * 获取主键名
     * @param string $table
     * @return string
     */
    public static function primary($table){
        self::__initTableInfo($table);
        return self::$__field[$table]['pri'];
    }

    /**
     * 只保留表字段数据
     * @param string $table
     * @param array $data
     * @return array
     */
    public static function filter($table, $data){
        $field = self::field($table);
        
        $filter = array();
        foreach($field as $f){
            isset($data[$f]) && $filter[$f] = $data[$f];
        }
        
        return $filter;
    }

    /**
     * 插入或者更新一条记录 $data 含有主键则更新 
     * @param type $table
     * @param type $data  null 获取 $_POST 中的值
     * @param type $key
     * @return string 主键值
     */
    public static function save($table, $data = null){
        
        if($data == null){
            $data = self::filter($table, $_POST);
        }else{
            $data = self::filter($table, $data);
        }
        
        $db  = Db::getInstance();
        $pri = self::primary($table);
        
        if(isset($data[$pri])){
            $db->update($table, $data, array($pri => $data[$pri]));
            return $data[$pri];
        }
        
        return $db->insert($table, $data);
    }
    
    
}
