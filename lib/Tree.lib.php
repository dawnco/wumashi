<?php

/**
 * 树操作
 * @author  Dawnc
 * @date    2014-06-27
 */
class Tree {
    
    
    public $__data      = null; //原始格式化好的数据
    public $__tree      = null; //普通树
    public $parentIds = array(); //父节点ID
    public $childIds  = array(); //子节点ID

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
     * 获取树
     * @return type
     */
    public function getTree() {
        return isset($this->__tree[0]['child']) ? $this->__tree[0]['child'] : array() ;
    }

    /**
     * 获取数据
     * @return type
     */
    public function getData() {
        return $this->__data;
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
     * @retrun array
     */
    public function findParnetIds($current_id) {
        $pid = 0;
        foreach ($this->__data as $id => $vo) {
            if ($current_id == $id) {
                $pid = $vo['pid'];
                $this->parentIds[] = $pid;
                break;
            }
        }
        
        if($pid !=0){
            $this->findParnetIds($pid);
        }
    }

    /**
     * 找到 id 节点下的树
     * @param type $tree
     * @param type $id
     */
    public function getChild($id = 0) {
        
        if(!$this->parentIds){
            $this->findParnetIds($id);
        }
        $ids = array_reverse($this->childIds);
        $ids[] = $id;
        
        return $this->getChildByParentIds($ids);
        
    }
    
    /**
     * 通过父id获取子节点
     * @param array $ids
     * @return type
     */
    public function getChildByParentIds($ids = array()) {
        $child = $this->__tree;
        foreach ($ids as $vo) {
            $child = isset($child[$vo]['child']) ? $child[$vo]['child'] : array();
        }
        return $child;
    }
    
    /**
     * 获取id下所有子id
     * @param type $id
     * @param type $include 是否包含$id 本身
     * @return array
     */
    public function getChildIds($id = 0, $include = true) {
        $child = $this->getChild($id);
        $this->__getChildId($child);
        
        $return = $this->childIds;
        if($include){
            $return[] = $id;
        }
        return $return;
    }
    
    private function __getChildId($child){
        foreach ($child as $vo){
            $this->childIds[] = $vo['id'];
            if(isset($child['child'])){
                $this->__getChildId($child['child']);
            }
        }
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
        if(!$this->parentIds){
            $this->findParnetIds($id);
        }
        
        foreach ($this->__data as $k=>$vo) {
            $this->__data[$k]['active'] = false;
        }
        
        $ids = array_merge($this->parentIds, array($id));
        foreach ($ids as $i) {
            $this->__data[$i]['active'] = true;
        }
        
        $this->__tree = $this->__toTree($this->__data);
        
    }

}
