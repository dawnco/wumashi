<?php

/**
 *
 * @author  Dawnc
 * @date    2014-04-29
 */
class Upload {

    private $__uploadName = "file";
    private $__allowExt   = "jpg,gif,png";
    private $__maxSize    =  1048576; // 1M

    private $__error      = "";

    private $__UPFILE     = array();

    public function __construct(){

    }

    public function getError(){
        return $this->__error;
    }

    public function setName($name){
        $this->__uploadName = $name;
    }

    public function setAllowExt($ext){
        $this->__allowExt = $ext;
    }

    /**
     * 允许上传大小
     * @param type $size 单位 M
     */
    public function setMaxSize($size){
        $this->__maxSize = $size * 1024 * 1024;
    }

    private function __errorCode($code){
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "上传文件太大";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "上传文件太大";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "文件只有部分被上传";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "没有文件被上传";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "找不到临时文件夹";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "文件写入失败";
                break;
            default:
                $message = "未知错误";
                break;
        }
        return $message;
    }

    private function __init(){
        $_FILES_UP1 = isset($_FILES[$this->__uploadName]) ? $_FILES[$this->__uploadName] : false;



        if (!$_FILES_UP1) {
            $this->__error = "上传文件不存在";
            return false;
        }

        if($_FILES_UP1['error'] != 0){
            $this->__error = $this->__errorCode($_FILES_UP1['error']);
            return false;
        }

        if ($_FILES_UP1['size'] > $this->__maxSize) {
            $this->__error = "文件太大";
            return false;
        }

        //检查扩展名
        $ext = substr($_FILES_UP1['name'], strrpos($_FILES_UP1['name'], ".") + 1);

        $this->__UPFILE['ext']  = $ext;
        $this->__UPFILE['name'] = substr($_FILES_UP1['name'],0 ,strrpos($_FILES_UP1['name'], "."));

        $allow_ext = explode(",", $this->__allowExt);
        if (!in_array($ext, $allow_ext)) {
            $this->__error = "上传类型不允许";
            return false;
        }

        if (!is_uploaded_file($_FILES_UP1['tmp_name'])) {
            $this->__error = "没有选择上传文件";
            return false;
        }

        $this->__UPFILE['tmp_name'] = $_FILES_UP1["tmp_name"];

        return true;
    }


    /**
     * 保存文件到$file
     * @param type $file
     * @return type
     */
    public function create(){
        $isupload =  $this->__init();
        if($isupload){
            $data['ext']    = $this->__UPFILE['ext'];
            $data['name']   = $this->__UPFILE['name'];
            return $data;
        }else{
            return false;
        }
    }

    public function save($file){
        
        if($this->__error){
            return false;
        }
        
        if (!move_uploaded_file($this->__UPFILE['tmp_name'], $file)) {
            $this->__error = "上传失败";
            return false;
        }
        return true;
    }
}
