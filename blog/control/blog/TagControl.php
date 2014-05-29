<?php

/**
 * @link    http://blog.wumashi.com
 * @author  Dawnc <abke@qq.com>
 * @date    2014-05-02
 */
class TagControl {

    public function index($tag_name){

        $db = Db::getInstance();
        $tag_name               = s_value($tag_name);
        $data['meta']['title']  = "关于标签 $tag_name 的内容";;
        $data['tag_name']       = $tag_name;

        $tag_id = $db->getLineBy("tag", $tag_name, "name");
        if(!$tag_id){
            show_404("tag 不存在");
        }

        $data['lists'] = $db->getData("SELECT p.* FROM post_tag pt INNER JOIN post p ON p.id = pt.post_id WHERE pt.tag_id = ?i", $tag_id);
        var_dump($db->sql);

        View::layout("blog/tag", $data);
    }
}
