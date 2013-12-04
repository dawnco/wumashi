<?php

/**
 * 表单验证类
 * @author bing.peng & dawn
 *
 */
class FormValidator {

    const VALIDATE_TIME_EXIST = 1; //存在值才验证
    const VALIDATE_TIME_BOTH = 2; // 都验证

    private $validatorList; // 验证列表
    private $post;   // $_POST拷贝
    private $errors = array();  // 错误提示信息
    private $faild;   // 已验证的，数据表单字段
    private static $instance;

    static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->validatorList = array();
        $this->post = $_POST ? $_POST : $_GET;
        $this->data = array();
        $this->faild = array();
    }

    /**
     * 根据函数过滤内容
     * @param type $val
     * @param type $fns
     * @return type
     */
    private function __filter($val, $fns = null) {
        if (!$fns) {
            return $val;
        }
        if (is_array($fns)) {
            foreach ($fns as $fn) {
                $val = $fn($val);
            }
        } else {
            $val = $fns($val);
        }
        return $val;
    }

    /**
     * 添加表单验证字段，规则，提示信息
     * @param string $field 字段名
     * @param string $validator 验证器名称及参数字符串
     * @param string $errmsg 错误提示信息
     */
    public function add($field, $validator, $errmsg, $fn = '', $time = self::VALIDATE_TIME_BOTH) {
        $data = array(
            'field' => $field,
            'validator' => $validator,
            'errmsg' => $errmsg,
            'fn' => $fn, //过滤的函数,
            'time' => $time, //验证时间
        );
        $this->validatorList[] = $data;
    }

    /**
     * 获取验证过的数据 不获取 re开头的
     * @return array 
     */
    public function data() {
        $data = array();
        foreach ($this->validatorList as $d) {
            $field = $d['field'];
            if (!isset($data[$field]) && strpos($field, "repassword") !== 0 && strpos($field, "repeat") !== 0) {
                $data[$field] = $this->post[$field];
            }
        }
        return $data;
    }

    /**
     * 表单验证
     * @return boolean
     */
    public function validate() {
        $faildCount = 0; // 未通过验证的字段数

        foreach ($this->validatorList as $d) {
            $field = $d['field'];
            //过滤数据
            $this->post[$field] = $this->__filter(isset($this->post[$field]) ? $this->post[$field] : false, $d['fn']);
            // 如果一个字段有多个验规，其中一个规则验证失败后，其它规则将不再验证
            if (!in_array($field, $this->errors)) {
                $errmsg = $d['errmsg']; // 错误提示信息
                $arr = explode(':', $d['validator']);
                $fn = $arr[0]; // 验证方法

                $params = array(); // 参数
                $params[] = isset($this->post[$field]) ? $this->post[$field] : NULL; // 表单字段值
                // 用户参数
                if (isset($arr[1])) {
                    if ($fn == 'rangelen' || $fn == 'range') {
                        $params = array_merge($params, explode(',', $arr[1]));
                    } else {
                        $params[] = $arr[1];
                    }
                }

                // 执行具体的验证方法
                //验证时间
                switch ($d['time']) {
                    case self::VALIDATE_TIME_EXIST:
                        //值存在才验证
                        if (!$this->post[$field]) {
                            $result = true;
                        } else {
                            $result = call_user_func_array(array($this, $fn), $params);
                        }
                     
                        break;
                    default :
                        $result = call_user_func_array(array($this, $fn), $params);
                        break;
                }


                if (!$result) {
                    $faildCount++;
                    $this->errors[$field] = $errmsg;
                }
            }
        }

        if ($faildCount <= 0) {
            return true;
        } else {
            $GLOBALS['form_errors'] = $this->errors;
            return false;
        }
    }

    /**
     * 根据表单字段名获取，验证错误信息，如果没有错误信息返回空字符串
     * @param string $field
     */
    public function error($field) {
        if (isset($this->errors[$field])) {
            return $this->errors[$field];
        } else {
            return '';
        }
    }

    /**
     * 获取所有验证错误信息
     * @return array()
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * 验证必须，不为空
     * @param string $val
     * @return boolean
     */
    public function required($val) {
        return ($val == null || trim($val) == '' || strlen($val) <= 0) ? false : true;
    }

    /**
     * 验证是否匹配给定正则表达式
     * @param string $val
     * @param string $pattern
     * @return boolean
     */
    public function reg($val, $pattern) {
        $pattern = str_replace("@", ":", $pattern);

        return ( preg_match($pattern, $val) > 0 ) ? TRUE : FALSE;
    }

    /**
     * 验证是否是合法的email格式
     * @param string $val
     * @return boolean
     */
    public function mail($val) {
        return ( preg_match('/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/', $val) > 0 ) ? TRUE : FALSE;
    }

    /**
     * 验证是否是合法的URL
     * @param string $val
     * @return boolean
     */
    public function url($val) {
        return ( preg_match('/^https?:\/\/[\d\-a-zA-Z]+(\.[\d\-a-zA-Z]+)*\/?$/', $val) > 0 ) ? TRUE : FALSE;
    }

    /**
     * 验证字符串是否小于等于给定长度
     * @param string $val
     * @param int $len
     * @return boolean
     */
    public function maxlen($val, $max) {
        return ( strlen($val) <= $max ) ? TRUE : FALSE;
    }

    /**
     * 验证字符串大于等于给定长度
     * @param string $val
     * @param int $min
     * @return boolean
     */
    public function minlen($val, $min) {
        return ( strlen($val) >= $min ) ? TRUE : FALSE;
    }

    /**
     * 验证字符串长度在给定范围内
     * @param string $val
     * @param int $min
     * @param int $max
     * @return boolean
     */
    public function rangelen($val, $min, $max) {
        return ( strlen($val) >= $min && strlen($val) <= $max ) ? TRUE : FALSE;
    }

    /**
     * 验证数值不大于给定值
     * @param int $val
     * @param int $max
     * @return boolean
     */
    public function max($val, $max) {
        return ( $val <= $max ) ? TRUE : FALSE;
    }

    /**
     * 验证数值不小于给定值
     * @param string $val
     * @param int $min
     * @return boolean
     */
    public function min($val, $min) {
        return ( $val >= $min ) ? TRUE : FALSE;
    }

    /**
     * 验证数值在给定范围内
     * @param int $val
     * @param int $min
     * @param int $max
     * @return boolean
     */
    public function range($val, $min, $max) {
        return ( $val >= $min && $val <= $max ) ? TRUE : FALSE;
    }

    /**
     * 验证是否为数字
     * @param int $val
     * @return boolean
     */
    public function digits($val) {
        return ( preg_match('/^[0-9]*[1-9][0-9]*$/', $val) > 0 ) ? TRUE : FALSE;
    }

    /**
     * 验证是否与另一个域值相等
     * @param string $val
     * @param string $field
     * @return boolean
     */
    public function equalTo($val, $field) {
        if (isset($this->post[$field])) {
            return $this->post[$field] == $val;
        } else {
            return FALSE;
        }
    }

    /**
     * 判断用户名是否已经存在
     * @param type $val
     * @return boolean
     */
    public function notExistUser($val) {
        $var = get_var(prepare("SELECT id FROM member WHERE user = ?s", array($val)));
        if ($var) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 是否是手机号码
     * @param type $val
     * @return type
     */
    public function mobile($val) {
        return ( preg_match('/^1[0-9]{10,10}$/', $val) > 0 ) ? true : false;
    }

    public function noValidate($val) {
        return true;
    }

}
