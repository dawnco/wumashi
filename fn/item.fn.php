<?php

/**
 * @author  Dawnc
 * @date    2015-09-02
 */
function master_site_url($uri = "", $param = []) {
    return site_url($uri, $param, Conf::get("app", "master_base_url"));
}

function user_page_url($id = "", $param = []) {
    if(!$id){
        return "#";
    }
    return site_url("home/$id/", $param, Conf::get("app", "uc_base_url"));
}

function m_site_url($uri = "", $param = []) {
    return site_url($uri, $param, Conf::get("app", "m_base_url"));
}

function uc_site_url($uri = "", $param = []) {
    return site_url($uri, $param, Conf::get("app", "uc_base_url"));
}

function user_name($id = "") {
    return "网友";
}

function ask_site_url($uri, $param = []){
    return site_url($uri, $param, Conf::get("app", "ask_base_url"));
}
