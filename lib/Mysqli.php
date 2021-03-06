<?php

namespace wumashi\lib;

use wumashi\core\Exception;
/**
 * mysqli 数据库
 * @author Dawnc
 * @date   2014-06-09
 */
class Mysqli extends \wumashi\core\Db{

//    private static $__instance  = null;

    private $__link = null;
    public $error   = [];
    public $sql     = null;

    public function __construct($conf){

        $hostname = $conf['hostname'];
        $port     = $conf['port'];
        $username = $conf['username'];
        $password = $conf['password'];
        $database = $conf['database'];
        $charset  = $conf['charset'];

        $this->__link = new \mysqli($hostname, $username, $password, $database, $port);

        if ($this->__link->connect_error){
            throw new Exception("can't connect mysql $hostname", 500);
        }

        if (!$this->__link->set_charset($charset)){
            throw new Exception("error set chartset $charset", 500);
        }
    }

    /**
     * 获取一行数据
     * @param type $query
     * @param type $bind
     * @return boolean
     */
    public function getLine($query, $bind = null){

        $query  = $this->prepare($query, $bind);
        $result = $this->__exec($query);

        if (!$result){
            return false;
        }
        $row = $result->fetch_assoc();
        $result->free();

        return $row;
    }

    /**
     * 快捷查询
     * @param string $table
     * @param string $value
     * @param string $index
     * @param string $field
     */
    public function getLineBy($table, $value, $index = "id", $field = "*"){
        $query = "SELECT $field FROM `$table` WHERE `$index` = ?s ";
        return $this->getLine($this->prepare($query, array($value)));
    }

    /**
     * 获取一个值
     * @param type $query
     * @param type $bind
     * @return type
     */
    public function getVar($query, $bind = null){
        $query = $this->prepare($query, $bind);
        $line  = $this->getLine($query);
        return $line ? array_shift($line) : false;
    }

    /**
     * 获取数据
     * @param type $query
     * @param type $bind
     * @return array
     */
    public function getData($query, $bind = null){
        $data = array();

        $query  = $this->prepare($query, $bind);
        $result = $this->__exec($query, $this->__link);
        if (!$result){
            return $data;
        }
       
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        $result->free();

        return $data;
    }

    /**
     * 插入sql
     * @param type $table
     * @param type $data
     * @return type
     */
    public function insert($table, $data){
        $insert_fileds = array();
        $insert_data   = array();
        foreach ($data as $field => $value){
            array_push($insert_fileds, "`{$field}`");
            array_push($insert_data, '"' . $this->escape($value) . '"');
        }
        $insert_fileds = implode(', ', $insert_fileds);
        $insert_data   = implode(', ', $insert_data);
        $query         = "INSERT INTO `{$table}` ({$insert_fileds}) values ({$insert_data});";
        $result        = $this->__exec($query);

        if ($result){
            return $this->__link->insert_id;
        }

        return $result;
    }
    
    /**
     * 更新或者添加一条数据
     * @param type $table
     * @param type $data
     * @param type $value
     * @param type $field
     * @return type
     */
    public function upsert($table, $data, $value, $field = "id") {
        if($value && $this->getVar("SELECT id FROM `$table` WHERE `$field` = ?s", $value)){
            return $this->update($table, $data, array($field => $value));
        }else{
            return $this->insert($table, $data);
        }
    }

    /**
     * 批量插入
     * @param type $table
     * @param type $data
     */
    public function insertBatch($table, $data){
        $insert_fileds = array();
        foreach ($data as $value){
            foreach ($value as $field => $row){
                array_push($insert_fileds, "`{$field}`");
            }
            break;
        }
        $insert_fileds = implode(', ', $insert_fileds);


        foreach ($data as $field => $value){
            $insert_data = array();
            foreach ($value as $row){
                array_push($insert_data, '"' . $this->escape($row) . '"');
            }
            $insert_data_str[] = "(" . implode(', ', $insert_data) . ")";
        }

        $query  = "INSERT INTO `{$table}` ({$insert_fileds}) values " . implode(",", $insert_data_str) . ";";
        $result = $this->__exec($query);
    }

    /**
     * 更新sql
     * @param type $table
     * @param type $data
     * @param type $where
     * @return type
     */
    public function update($table, $data, $where){
        $update_data  = array();
        $update_where = array();
        foreach ($data as $field => $value){
            array_push($update_data, sprintf('`%s` = "%s"', $field, $this->escape($value)));
        }
        $update_data = implode(', ', $update_data);

        if (is_array($where)){
            foreach ($where as $field => $value){
                array_push($update_where, sprintf('`%s` = "%s"', $field, $this->escape($value)));
            }
            $update_where = 'WHERE ' . implode(' AND ', $update_where);
        } elseif (is_numeric($where)){
            $update_where = 'WHERE ' . $this->prepare("id = ?i", $where);
        } else {
            throw new Exception("Db Not Specified Where" , 500);
        }
        $query = "UPDATE `{$table}` SET {$update_data} {$update_where}";

        return $this->__exec($query);
    }

    public function delete($table, $where){

        if (is_array($where)){
            $delete_where = array();
            foreach ($where as $field => $value){
                array_push($delete_where, sprintf('`%s` = "%s"', $field, $this->escape($value)));
            }
            $delete_where = 'WHERE ' . implode(' AND ', $delete_where);
        } elseif (is_numeric($where)) {
            $delete_where = 'WHERE ' . $this->prepare("id = ?i", $where);
        } else {
            throw new Exception("Db Not Specified Where", 500);
        }

        $query = "DELETE FROM `$table` $delete_where";
        return $this->__exec($query);
    }

    /**
     * 执行sql
     * @param type $query
     * @return boolean
     */
    private function __exec($query){
        $start  = microtime(true);
        $result = $this->__link->query($query);
        if ($result === false){
            $error         = sprintf("%s : %s [%s]", $this->__link->errno, $this->__link->error, $query);
            $this->error[] =  $error;
            throw new Exception($error, 500);
//            return false;
        }
        $end     = microtime(true);
        $this->sql[] = "[" . substr(($end - $start) * 1000, 0, 5) . "ms] " . $query;
        return $result;
    }

    /**
     * 执行sql
     * @param type $query
     * @param type $bind
     * @return boolean
     */
    public function exec($query, $bind = null){
        $query  = $this->prepare($query, $bind);
        $result = $this->__exec($query);
        return $result;
    }

    /**
     * 转义安全字符
     * @param type $val
     * @return type
     */
    public function escape($val){
        return $this->__link->real_escape_string($val);
    }

    /**
     * 关闭数据库
     * @param string $conf
     * @param string $type
     */
    public function close(){
        return $this->__link->close();
    }

    /**
     * 开启事物
     * @return type
     */
    public function begin() {
        $this->sql[] = "begin";
        return $this->__link->autocommit(false);
    }

    /**
     * 提交事物
     * @return type
     */
    public function commit() {
        $this->sql[] = "commit";
        $result = $this->__link->commit();
        $this->__link->autocommit(true);
        return $result;
    }

    /**
     * 回滚
     * @return type
     */
    public function rollback() {
        $this->sql[] = "rollback";
        $result = $this->__link->rollback();
        return $result;
    }

}
