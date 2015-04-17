<?php

namespace wumashi\lib;

/**
 * 树操作
 * @author  Dawnc
 * @date    2014-06-27
 */
class Tree {
    
    
    /** 原始格式化好的数据 */
    private $__data      = null;
    /** 普通树 */
    private $__tree      = null;
    
    private $__parentIds = array(); //父节点ID
    private $__childIds  = array(); //子节点ID

    /**
     * 
     * @param type $data 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 设置数据 数据格式 
     * array(array("id","pid",..) , array("id","pid",..) ..)
     * @param type $data
     */
    public function setData($data) {
        $this->__data   = $this->__format($data);
        $this->__tree   = $this->__toTree($this->__data);
    }
    
      
    
    /**
     * 获取id下所有子id
     * @param type $id
     * @param type $include 是否包含$id 本身
     * @return array
     */
    public function getChildIds($id = 0, $include = true) {
        
        $this->__childIds = array();
        $child = $this->getChilds($id);
        $this->__findChildId($child);
        
        $return = $this->__childIds;
        if($include){
            $return[] = $id;
        }
        return $return;
    }
    
    /**
     * 获取节点
     * @param type $id
     */
    public function getNode($id = 0) {
        return isset($this->__data[$id]) ? $this->__data[$id] : array();
    }
    
    /**
     * 设置active属性 
     * 默认 active 为false
     * 如果子类 active 为true 父类active 也为true 
     * @param type $id
     */
    public function setActive($id){
     
        $parentIds = $this->getParentIds($id);
        
        foreach ($this->__data as $k=>$vo) {
            $this->__data[$k]['active'] = false;
        }
        
        $ids = array_merge($parentIds, array($id));
        foreach ($ids as $i) {
            if(isset($this->__data[$i])){
                $this->__data[$i]['active'] = true;
            }
        }
        
        $this->__tree = $this->__toTree($this->__data);
        
    }
    
    /**
     * 获取树
     * @return type
     */
    public function getTree($id = 0) {
        return isset($this->__tree[$id]['child']) ? $this->__tree[$id]['child'] : array() ;
    }

    /**
     * 获取数据
     * @return type
     */
    public function getData() {
        return $this->__data;
    }

    /**
     * 获取父ID
     * @param type $id
     * @return Array 
     */
    public function getParentIds($id) {
        $this->__parentIds = array();
        $this->__findParentIds($id);
        return array_reverse($this->__parentIds);
    }
    
    /**
     * 获取父类节点
     * @param type $id
     */
    public function getParents($id) {
        $ids = $this->getParentIds($id);
        $data = $this->__data;
        $nodes = array();
   
        foreach($ids as $id){
            if(isset($data[$id])){
                $nodes[] = $data[$id];
            }
        }
        return $nodes;
    }
    /**
     * 找到 id 节点下的树
     * @param type $tree
     * @param type $id
     */
    public function getChilds($id = 0) {
        
        if($id != 0){
            $ids = $this->getParentIds($id);
            $ids[] = $id;
        }else{
            $ids[] = $id;
        }
        return $this->__findChildByParentIds($ids);
    }
    
    
    /**
     * 获取同级数据
     * @param type $id
     */
    public function getSiblings($id) {
        $data = array();
        $pid  = $this->__data[$id]['pid'];
        foreach($this->__data as $vo){
            if($vo['pid'] == $pid){
                $data[] = $vo;
            }
        }
        return $data;
    }
    
    /**
     * 通过父id获取子节点
     * @param array $ids
     * @return type
     */
    private function __findChildByParentIds($ids = array()) {
        $child = $this->__tree;
        foreach ($ids as $vo) {
            $child = isset($child[$vo]['child']) ? $child[$vo]['child'] : array();
        }
        return $child;
    }
    
    
    private function __format($items){
        $tmp = array();
        foreach ($items as $vo) {
            $tmp[$vo['id']] = $vo;
        }
        return $tmp;
    }
    /**
     *  格式化树
     * @param type $items
     * @return type
     */
    private function __toTree($items) {
        foreach ($items as $item) {
            $items[$item['pid']]['child'][$item['id']] = &$items[$item['id']];
        }
        return isset($items[0]['child']) ? $items : array();
    }
    
    /*
     * 树转换为 预排序树
     * @see http://blog.csdn.net/wisewillpower/article/details/2306461
     */
    private function __toMptree() {
       
    }
    
    /**
     * 获取父ID
     * @param type $current_id
     */
    private function __findParentIds($current_id) {
        $pid = 0;
        foreach ($this->__data as $id => $vo) {
            if ($current_id == $id) {
                $pid = $vo['pid'];
                $this->__parentIds[] = $pid;
                break;
            }
        }
        
        if($pid !=0){
            $this->__findParentIds($pid);
        }
    }
    
    /**
     * 找子ID
     * @param type $child
     */
    private function __findChildId($child){
        foreach ($child as $vo){
            $this->__childIds[] = $vo['id'];
            if(isset($vo['child'])){
                $this->__findChildId($vo['child']);
            }
        }
    }
  

}
