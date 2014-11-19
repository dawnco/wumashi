<?php

/**
 * mysqli 数据库
 * @author Dawnc
 * @date   2014-06-09
 */
class Mysqlim extends Db{

//    private static $__instance  = null;

    private $__link = null;
    public $error   = null;
    public $sql     = null;

    public function __construct($conf){

        $hostname = $conf['hostname'];
        $port     = $conf['port'];
        $username = $conf['username'];
        $password = $conf['password'];
        $database = $conf['database'];
        $charset  = $conf['charset'];

        $this->__link = new mysqli($hostname, $username, $password, $database, $port);

        if ($this->__link->connect_error){
            trigger_error("can't connect mysql $hostname", E_USER_ERROR);
        }

        if (!$this->__link->set_charset($charset)){
            trigger_error("error set chartset $charset", E_USER_ERROR);
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
        } elseif ($where){
            $update_where = 'WHERE ' . $this->prepare("id = ?i", $where);
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
        } elseif ($where){
            $delete_where = 'WHERE ' . $this->prepare("id = ?i", $where);
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
        $result = $this->__link->query($query);

        $this->sql[] = $query;

        if ($result === false){
            $this->error = $this->__link->errno . " " . $this->__link->error . " " . $query;
            trigger_error($this->error, E_USER_ERROR);
            return false;
        }
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
        if (!$result){
            return false;
        }
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

}
