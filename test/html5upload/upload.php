<?php

error_reporting(E_ALL ^ E_WARNING ^E_NOTICE);

require 'Upload.lib.php';


function input($key){
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : false;
}


/**
 * 统一文件上传控制器
 * @author lanlin
 * @change 2013-10-30
 */
class UploadControl  {

    private $__session = null;
    /**
     * 上传处理
     *
     * $_REUQST updir          保存的文件夹
     * $_REUQST file         $_FILE 的名称
     * $_REUQST maxWidth     允许最大宽度
     * $_REUQST maxHeight    允许最大高度
     * $_REUQST resizeHeight 压缩高度
     * $_REUQST resizeWidth  压缩宽度
     * $_REUQST thumbWidth   缩略图宽度
     * $_REUQST thumbHeight  说略图高度
     *
     * output json
     *
      $out['error'] = ''; 错误信息
      $out['url'] = $url; url
      $out['uri'] = $uri; uri
      $out['dir'] = $dir; 存放目录
      $out['file'] = $file; 保存的文件名称

     */
    public function romeoUpload() {
        $this->uploadPic();
    }

    public function __construct() {
 //       $session_id = input('SESSID');

//        $this->__session = SessionRedis::createSession($session_id);
//        //验证登陆
//        if(!$this->__session->get('user','id')){
//            $this->_error("您未登陆");
//        }
        $this->dir = "images";
        $this->inputName = 'imgFile';

        //初始参数
        $this->allowFile = array('image/jpeg'); //允许上传的类型
        $this->maxWidth = 1024; //图片最大宽度
        $this->maxHeight = 1024; //图片最大高度

        $this->resizeWidth = 0; //调整图片的宽度
        $this->resizeHeight = 0; //调整图片的高度

        $this->thumbWidth = 0;  // 缩略图宽度
        $this->thumbHeight = 0; // 缩略图宽度

        $this->domain = "7808.cn";
    }

    /** 用户项目图片上传 */
    public function itemPic() {
        $this->dir = "item_album/" . date("Ymd");
        $this->inputName = "imgFile";
        $this->allowFile = array('image/jpeg'); //允许上传的类型
        $this->resizeWidth = 376;
        $this->resizeHeight = 316;
        $this->thumbWidth = 64;
        $this->thumbHeight = 54;
        $out = $this->_image();
        echo json_encode($out);
        exit;
    }

    /** 编辑器上传 */
    public function descPic() {
        $this->dir = "item_image/" . date("Ymd");
        $this->inputName = "imgFile";
        $this->allowFile = array('image/jpeg'); //允许上传的类型
        $this->maxWidth = 600; //图片最大宽度
        $this->maxHeight = 600; //图片最大高度
        $out = $this->_image();
        $this->_crossOut($out);
    }

    /**
     * 处理跨域问题json输出
     * 使用 kindeditor 上传插件处理
     * 在要使用的页面加上js
     * @param type $array
     */
    protected function _crossOut($array) {
        echo '<html><body><script type="text/javascript">try {document.domain = "' . $this->domain . '";}catch(e){}</script><div id="packRemoteJsonData">' . json_encode($array) . '</div></body></html>';
        exit;
    }

    /**
     * romeo 上传图片
     */
    public function uploadPic() {

        $dir = trim(input('updir'), " /");
        $this->dir = $dir ? $dir . "/" . date('Ymd') : "other/" . date('Ymd');
        $this->inputName = input('file') ? input('file') : 'imgFile'; // $_FILE 名称
        //初始参数
        $this->allowFile = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png'); //允许上传的类型
        $this->maxWidth = intval(input('maxWidth') ? input('maxWidth') : 1024); //图片最大宽度
        $this->maxHeight = intval(input('maxHeight') ? input('maxHeight') : 1024); //图片最大高度
        //调整图片的高度
        $this->resizeWidth = intval(input('resizeWidth')); //调整图片的宽度
        $this->resizeHeight = intval(input('resizeHeight'));

        // 缩略图宽度
        $this->thumbWidth = intval(input('thumbWidth'));
        // 缩略图宽度
        $this->thumbHeight = intval(input('thumbHeight'));

        //最小限制
        $this->minWidth = intval(input('minWidth'));
        $this->minHeight = intval(input('minHeight'));

        $out = $this->_image();

        if (input('editor')) {
            $this->_crossOut($out);
        } else {
            echo json_encode($out);
        }

        // $this->_crossOut($out);
    }

    protected function _error($msg) {
        echo '{"error":"' . $msg . '"}';
        exit;
    }

    protected function _success($uri, $url, $file, $dir) {
        $out['error'] = '';
        $out['url'] = $url;
        $out['uri'] = $uri;
        $out['dir'] = $dir;
        $out['file'] = $file;
        echo json_encode($out);
    }

    /**
     * 上传图片
     * @return array
     */
    protected function _image() {
        /**
        $msg = array(
            'url' => conf('app', 'img') . '/item_image/20131126/abc.jpg',
            'thumbUrl' => conf('app', 'img') . '/item_image/20131126/abc.jpg'
        );
        return $msg;**/

        if (!preg_match("/^[a-z_0-9\/]{1,50}$/", $this->dir)) {
            $msg['error'] = "上传路径不正确 $this->dir";
            return $msg;
        }
        if (!$_FILES[$this->inputName]) {
            $msg['error'] = "没有选择上传文件";
            return $msg;
        }

        $des_dir =dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR;

        if (!is_dir($des_dir)) {
            @mkdir($des_dir, 0777, true);
        }

        $handle = new Upload($_FILES[$this->inputName], 'zh_CN');

        // 允许上传的文件类型
        $handle->allowed = $this->allowFile;

        // 最大尺寸限制
        if ($this->maxWidth && $this->maxHeight) {
            $handle->image_max_width = $this->maxWidth;
            $handle->image_max_height = $this->maxHeight;
        }

        //最小尺寸
        if ($this->minWidth && $this->minHeight) {
            $handle->image_min_width = $this->minWidth;
            $handle->image_min_height = $this->minHeight;
        }

        $timestamp = microtime(true)*10000;
       
        if ($handle->uploaded) {
            // 处理图片

            $handle->file_new_name_body = $timestamp ;
            //压缩图片
            if ($this->resizeWidth && $this->resizeHeight) {
                $handle->image_resize = true;
                $handle->image_x = $this->resizeWidth; // width
                $handle->image_y = $this->resizeHeight; // height
            }
            $handle->process($des_dir);

            if ($handle->error) {
                $msg['error'] = $handle->error;
                return $msg;
            }

            if ($handle->processed) {
                $filename = $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
                $saveUrl  = $this->dir . "/" . $filename;
                //chmod($saveUrl, 0777);
            }

            //处理缩略图
            if ($this->thumbWidth && $this->thumbHeight) {
                $handle->file_new_name_body = $timestamp . '.thumb';
                $handle->image_resize = true;
                $handle->image_x = $this->thumbWidth;  // width
                $handle->image_y = $this->thumbHeight;  // height
                $handle->process($des_dir);
                $thumb = $this->dir . "/" . $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
               // chmod($thumb, 0777);
            }
            $handle->clean();

            $msg = array(
                'error'     => '',
                'url'       =>   $saveUrl,
                'uri'       => $saveUrl,
                'thumb'     => isset($thumb) ? $thumb : "", //缩略图相对url
                'thumbUrl'  => isset($thumb) ? $thumb : "", //缩略图绝对url
                'dir'       => $this->dir,
                'file'      => $filename,
             //   "SESSID"    => $this->__session->getSessionID(),
            );
            return $msg;
        } else {
            $msg['error'] = $handle->error;
            return $msg;
        }
    }

}

$cls = new UploadControl();

$cls->romeoUpload();