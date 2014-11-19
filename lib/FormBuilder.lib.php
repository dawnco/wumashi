<?php

/**
 * 表单 html 代码生成
 * @author  Dawnc
 * @date    2014-09-17
 */
class FormBuilder {

    /**
     * 
     * @param type $data //数据 [['name'=>"", "value" => ""]]
     * @param type $name select 的名称
     * @param type $select 选中项
     * @param type $opts  array("pad" => "缩进字符", "default_option" => "第一个选项", ...) ... select 其他属性
     * @return string
     */
    static function select($data, $name, $select = "", $opts = array()) {
        $html = "<select name=\"{$name}\"";

        //占位符
        $pad = isset($opts['pad']) ? $opts['pad'] : "&nbsp;&nbsp;";
        unset($opts['pad']);
        
        if(count($data) > 1){
            //默认值
            if (empty($opts['default_option'])) {
                array_unshift($data, array("value" => "", "name" => "--"));
                unset($opts['default_option']);
            } else {
                array_unshift($data, $opts['default_option']);
            }
        }

        foreach ($opts as $key => $value) {
            $html .= " $key=\"{$value}\" ";
        }
        $html .= ">";
        $html .= self::selectOption($data, $select, $pad);
        $html .= "</select>";

        return $html;
    }

    static function selectOption($data, $select, $pad, $pad_length = 0) {
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
                $html .= self::selectOption($value['child'], $select, $pad, $pad_length + 1);
            }
        }
        return $html;
    }

}
