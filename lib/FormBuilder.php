<?php

namespace wumashi\lib;

/**
 * 表单 html 代码生成
 * @author  Dawnc
 * @date    2014-09-17
 */
class FormBuilder {

    /**
     * 
     * @param type $data //数据 [['name'=>"", "id" => ""]]
     * @param type $name select 的名称
     * @param type $select 选中项
     * @param type $opts  array("pad" => "缩进字符", "default_option" => "第一个选项", ...) ... select 其他属性
     * @return string
     */
    public static function select($data, $name, $select = "", $opts = array()) {
        
        
        if(!$select){
            $select = input($name);
        }
        
        $html = "<select name=\"{$name}\"";

        //占位符
        $pad = isset($opts['pad']) ? $opts['pad'] : "&nbsp;&nbsp;";
        unset($opts['pad']);
        
        if(count($data) > 1){
            //默认值
            if (empty($opts['default_option'])) {
                array_unshift($data, array("id" => "", "name" => "--"));
                unset($opts['default_option']);
            } else {
                array_unshift($data, $opts['default_option']);
            }
        }

        foreach ($opts as $key => $value) {
            $html .= " $key=\"{$value}\" ";
        }
        $html .= ">";
        $html .= self::__selectOption($data, $select, $pad);
        $html .= "</select>";

        return $html;
    }

    private static function __selectOption($data, $select, $pad, $pad_length = 0) {
        $html = "";

        $pad_str = "";
        for ($i = 0; $i < $pad_length; $i++) {
            $pad_str .= $pad;
        }

        foreach ($data as $key => $value) {

            if ($value['id'] == $select) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            $html .= "<option {$selected} value=\"{$value['id']}\">{$pad_str}{$value['name']}</option>\n";
            if (!empty($value['child'])) {
                $html .= self::__selectOption($value['child'], $select, $pad, $pad_length + 1);
            }
        }
        return $html;
    }

    
    public static function checkbox($data = array(), $name = "",$checked = array(), $key_value = "id", $key_name = "name", $type = "checkbox"){
        $tpl = '<label class="check"><input  name="%s" type="%s" %s value="%s">%s</label>';
        
        $html  = "";
        foreach($data as $vo){
            $cked = "";
            if(in_array($vo[$key_value], $checked)){
                $cked = 'checked="checked"';
            }
            $html .= sprintf($tpl, $name, $type, $cked, $vo[$key_value],$vo[$key_name]);
        }
        return $html;
    }
    
    public static function raido($data = array(), $name = "",$checked = "", $key_value = "id", $key_name = "name") {
        return self::checkbox($data, $name, array($checked), $key_value, $key_name, "radio");
    }
    
    
}
