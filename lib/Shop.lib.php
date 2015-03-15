<?php

/**
 * 网店相关
 * @author  Dawnc
 * @date    2015-03-06
 */
class Shop {

    private static $__currencyData = array();
    //订单状态
    private static $__orderStatusData = array();

    public static function zoneId2Code($id) {
        $db = Db::getInstance();
        return $db->getVar("SELECT code FROM shop_zone WHERE id = ?i", $id);
    }
    
    public static function zoneId2Name($id) {
        $db = Db::getInstance();
        return $db->getVar("SELECT name FROM shop_zone WHERE id = ?i", $id);
    }
    
    public static function countryId2Name($id) {
        $db = Db::getInstance();
        return $db->getVar("SELECT name FROM shop_country WHERE id = ?i", $id);
    }
    
    public static function countryId2Iso2($id) {
        $db = Db::getInstance();
        return $db->getVar("SELECT iso_code_2 FROM shop_country WHERE id = ?i", $id);
    }

    private static function __init() {
        if (!self::$__currencyData) {
            $tmp = Db::getInstance()->getData("SELECT * FROM shop_currency");
            foreach ($tmp as $v) {
                self::$__currencyData[$v['code']] = $v;
            }
        }
    }

    /**
     * 转化汇率
     * @param type $value
     * @param type $code 货币代码
     * @param type $rate 给定汇率
     */
    public static function currencyValue($value, $code = "", $rate = 0) {
        self::__init();
        
        if(!$code){
            $code = self::getCurrentCurrencyCode();
        }
        
        if (!$rate) {
            $rate = self::$__currencyData[$code]['value'];
        }

        return round($value * $rate);
    }

    /**
     * 显示汇率
     */
    public static function currencyDisplay($value, $code = "", $rate = 0) {
        
        if(!$code){
            $code = self::getCurrentCurrencyCode();
        }
        
    
        $val = self::currencyValue($value, $code, $rate);
        return self::$__currencyData[$code]['symbol_left'] . " " . $val . " " . self::$__currencyData[$code]['symbol_right'];
    }

    /**
     * 订单状态名称
     * @param type $order_status_id
     * @return type
     */
    public static function orderStatusName($order_status_id) {
        if (!self::$__orderStatusData) {
            $tmp = Db::getInstance()->getData("SELECT * FROM shop_order_status");
            foreach ($tmp as $v) {
                self::$__orderStatusData[$v['id']] = $v['name'];
            }
        }

        if ($order_status_id === false) {
            $tmp = array();
            foreach (self::$__orderStatusData as $k => $v) {
                $tmp[] = array("id" => $k, "name" => $v);
            }
            return $tmp;
        } else {
            return self::$__orderStatusData[$order_status_id];
        }
    }

    /**
     * 获取当前货币代码
     * @return type
     */
    public static function getCurrentCurrencyCode() {
        self::__init();
        $code = input("currency") ? input("currency") : (Cookie::get("currency") ? Cookie::get("currency") : Conf::get("app", "default_currency_code"));
 
        if (!isset(self::$__currencyData[$code])) {
            $code = Conf::get("app", "default_currency_code");
        }
        return $code;
    }
    
 
    /**
     * 设置当前货币代码
     * @param type $code
     */
    public static function setCurrentCurrencyCode($code) {
        self::__init();
        if (!isset(self::$__currencyData[$code])) {
            $code = Conf::get("app", "default_currency_code");
        }

        Cookie::set("currency", $code, 3600 * 24 * 7);
    }

    /**
     * 全部货币
     * @return type
     */
    public static function getAllCurrency() {
        self::__init();
        return self::$__currencyData;
    }
}
