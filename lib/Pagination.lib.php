<?php

/**
 * 分页类
 * @author: 五马石 <abke@qq.com>
 * @link: http://blog.wumashi.com
 * @datetime: 2014-4-17
 * @version: 1.0
 * @Description
 */
class Pagination{

    private $__total; //总记录数
    private $__totalPage; //总页数
    private $__size; //每页记录数
    private $__currentPage ; //当前页码
    private $__page    = 0; //指定的当前页
    private $__pageTag = "{page}"; //页面变量模板
    private $__pageVar = "page"; //page 参数变量
    private $__showNum = 10; //显示多少个页码
    private $__pageUrl = null; // 分页url 模板
    private $__startNum;
    private $__endNum;

    public function __construct($option){

        foreach ($option as $key => $value) {
            $val        = "__" . $key;
            $this->$val = $value;
        }

        $this->__getPageUrl();

        $this->__totalPage   = ceil(($this->__total ? $this->__total : 1 ) / $this->__size);
        
        if($this->__page){
            //指定的页码
            $this->__currentPage = $this->__page;
        }else{
            $this->__currentPage = input($this->__pageVar) ? $this->__pageVar : 1;
        }
        
        if ($this->__currentPage > $this->__totalPage) {
            $this->__currentPage = $this->__totalPage;
        }

        $this->__calcPageNum();
    }

    /**
     * 获取分类URL
     */
    private function __getPageUrl(){
        if ($this->__pageUrl == null){
            $uri          = str_replace($_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']);
            $uri          = trim($uri, "?");
            $get          = $_GET;
            unset($get[$this->__pageVar]);
            $query_string = "";
            foreach ($get as $key => $vo){
                $query_string .= $key . "=" . $vo . "&";
            }
            $this->__pageUrl = $uri . "?" . $query_string . "page={page}";
        }
    }

    /**
     * 计算偏移量
     */
    private function __calcPageNum(){

        //显示几个
        $length = ceil($this->__showNum / 2);
      
        if ($this->__currentPage <= $length){
            //前4页
            $this->__startNum = 1; //起始页
            $this->__endNum   = $this->__showNum < $this->__totalPage ? $this->__showNum : $this->__totalPage;
        } elseif ($this->__currentPage >= $this->__totalPage - $length){
            //最有4页
            $this->__endNum   = $this->__totalPage;
            $start            = $this->__endNum - $this->__showNum + 1;
            $this->__startNum = $start > 1 ? $start : 1; //起始页
        } else{
            $start = $this->__currentPage - $length + 1;
            $end   = $start + $this->__showNum - 1;

            if ($start == 0){
                $start = 1;
                $end   = $this->__showNum;
            }

            $this->__startNum = $start; //起始页
            $this->__endNum   = $end < $this->__totalPage ? $end : $this->__totalPage;
        }
        
    }

    /**
     * 生成url
     * @param type $number
     * @return type
     */
    private function __url($number){
        return str_replace($this->__pageTag, $number, $this->__pageUrl);
    }

    /**
     * 产生分页html;
     * @return string
     */
    public function html(){
        $str = "";

        //只有一页
        if ($this->__totalPage == 1){
            return $str;
        }

        if ($this->__currentPage == 1){
            $str .= "<span class=\"active first\">首页</span>";
        } else{
            $str .= "<a href=\"" . $this->__url(1) . "\">首页</a>";
        }

        for ($i = $this->__startNum; $i <= $this->__endNum; $i++){
            if ($i == $this->__currentPage){
                $str .= "<span class=\"active\">" . $i . "</span>";
            } else{
                $str .= "<a href=\"" . $this->__url($i) . "\">" . $i . "</a>";
            }
        }

        if ($this->__currentPage == $this->__totalPage){
            $str .= "<span class=\"active last\">末页</span>";
        } else{
            $str .= "<a href=\"" . $this->__url($this->__totalPage) . "\">末页</a>";
        }

        return $str;
    }

    /**
     * mysql LIMIT 部分数据
     * @return type
     */
    public function limit(){
        return " LIMIT " . (($this->__currentPage - 1) * $this->__size) . "," . $this->__size . " ";
    }

}
