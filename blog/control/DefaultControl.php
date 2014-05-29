<?php


/**
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-13
 * @version: 1.0
 * @Description
 */



class DefaultControl {

    public function __construct(){
        $this->_db = Db::getInstance();
    }
    public function index(){

        $data['meta']['title'] = "新的博客";

        $where = "1=1";
        $total      = $this->_db->getVar("SELECT count(*) FROM `post` WHERE $where");
        $pagination = new Pagination(array("total"=> $total,"size"=>10));

        $data['total']       = $total;
        $data['pagination']  = $pagination->html();
        $lists               = $this->_db->getData("SELECT * FROM `post` WHERE $where ORDER BY id DESC ". $pagination->limit());
        $data['lists']       = $lists ? $lists : array();

        View::layout("index", $data);
    }

    public function test() {
        $s = Session::get("username");;
        var_dump($s);
    }

}
