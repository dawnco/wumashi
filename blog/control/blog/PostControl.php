<?php

/**
 * @link    http://blog.wumashi.com
 * @author  Dawnc <abke@qq.com>
 * @date    2014-05-02
 */
class PostControl {

    public function detail($post_id){
        $db = Db::getInstance();
        $data               = $db->getLineBy("post", $post_id);
        $data['content']    = $db->getVar("SELECT content FROM post_content WHERE post_id = ?i", $post_id);
        $data['tag']        = $db->getData("SELECT t.id, t.name FROM tag t LEFT JOIN post_tag pt ON pt.tag_id = t.id WHERE pt.post_id = ?i", $post_id);

        $data['meta']['title'] = $data['title'];
        View::layout("blog/detail", $data);
    }
}
