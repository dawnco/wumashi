<?php

/**
 *
 * @author  Dawnc
 * @date    2014-04-29
 */
class UploadControl {

    public function index(){
        View::render("upload");
    }

    public function upload(){
        $upload    = new Upload();
        $upload->setMaxSize(0.1);
        $save_file = ROOT . "static/upload/xxx.jpg";
        if(!is_dir(dirname($save_file))){
            mkdir(dirname($save_file), 0777, true);
        }
        $r = $upload->save($save_file);
        var_dump($r);
        if(!$r){
            echo $upload->getError();
        }
    }

}
