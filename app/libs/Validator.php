<?php

/**
 * 校验器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Libs;

use \Phalcon\DiInterface;

class Validator {

    /**
     * DI对象
     * @var \Phalcon|DI
     */
    private $_di;

    /**
     * 内部数据
     *
     * @access private
     * @var array
     */
    private $_data;

    /**
     * 当前验证指针
     *
     * @access private
     * @var string
     */
    private $_key;

    /**
     * 验证规则数组
     *
     * @access private
     * @var array
     */
    private $_rules = array();

    /**
     * 中断模式,一旦出现验证错误即抛出而不再继续执行
     *
     * @access private
     * @var boolean
     */
    private $_break = true;

    public function __construct(DiInterface $di){
        $this -> setDI($di);
    }

    /**
     * DI对象赋值
     * @param DiInterface $di
     */
    public function setDI(DiInterface $di){
        $this -> _di = $di;
    }

    /**
     * 获取DI对象
     * @return DI|\Phalcon
     */
    public function getDI(){
        return $this -> _di;
    }

    /**
     * 增加验证规则
     *
     * @access public
     * @param string $key 数值键值
     * @param string $rule 规则名称
     * @param array $exception=array('message'=>'错误信息', 'code'=>'错误码')
     * @return $this
     */
    public function add_rule($key, $rule, $exception){
        is_string($exception) && $exception = array(
            'message' => $exception,
            'code' => 0,
        );
        if (func_num_args() <= 3) {
            $this -> _rules[$key][] = array($rule, $exception);
        } else {
            $params = func_get_args();
            $params = array_splice($params, 3);
            $this -> _rules[$key][] = array_merge(array($rule, $exception), $params);
        }

        return $this;
    }

    /**
     * 开启/关闭中断模式
     *
     * @access public
     * @return void
     */
    public function set_break($break=false){
        $break = boolval($break);
        $this -> _break = $break;
    }

    /**
     *
     * @access	public
     * @param   array $data 需要验证的数据
     * @param   array $rules 验证数据遵循的规则
     * @return	array
     * @throws  Typecho_Validate_Exception
     */
    public function run(array $data, $rules = NULL){
        $result = array();
        $this -> _data = $data;
        $rules = empty($rules) ? $this -> _rules : $rules;

        // Cycle through the rules and test for errors
        foreach ($rules as $key => $rule) {
            $this -> _key = $key;
            $data[$key] = (is_array($data[$key]) ? 0 == count($data[$key]) : 0 == strlen($data[$key])) ? NULL : $data[$key];
            foreach ($rule as $k=>$v) {
                /** 获取校验方法名 */
                $method = $v[0];
                /** 获取校验失败的exception信息(message和code) */
                $exception = $v[1];
                /** 获取需要校验的数据 */
                $v[1] = $data[$key];
                $params = array_slice($v, 1);
                /** 回调执行校验方法 */
                if (!call_user_func_array(is_array($method) ? $method : array($this, $method), $params)) {
                    $result[$key] = array(
                        'message'=>$exception['message'],
                        'code'=> $exception['code'],
                    );
                    break;
                }
            }
            /** 开启中断 */
            if ($this -> _break && $result) {
                break;
            }
        }

        return $result;
    }

    /**
     * 是否为空
     *
     * @access public
     * @param string $str 待处理的字符串
     * @return boolean
     */
    public function required($str){
        return !empty($str);
    }

    /**
     * 验证输入是否一致
     *
     * @access public
     * @param string $str 待处理的字符串
     * @param string $key 需要一致性检查的键值
     * @return boolean
     */
    public function confirm($str, $key){
        return !empty($this->_data[$key]) ? ($str == $this->_data[$key]) : empty($str);
    }

    /**
     * 验证是否相等
     * @param $str
     * @param $cstr
     * @return bool
     */
    public function equals($one, $two){
        return $one == $two;
    }

    /**
     * 验证是否不相等
     * @param $one
     * @param $two
     * @return bool
     */
    public function not_equals($one, $two){
        return $one != $two;
    }

    /**
     * 检测时间格式是否正确
     * @param $str
     * @return bool
     */
    public function check_time($str){
        return strtotime($str) ? true : false;
    }

    /**
     * 枚举类型判断
     *
     * @access public
     * @param string $str 待处理的字符串
     * @param array $params 枚举值
     * @return unknown
     */
    public static function enum($str, array $params)
    {
        $keys = array_flip($params);
        return isset($keys[$str]);
    }

    /**
     * 最大长度
     *
     * @param $str
     * @param $length
     * @return bool
     */
    public static function max_length($str, $length){
        return (mb_strlen($str, 'UTF-8') < $length);
    }

    /**
     * 最小长度
     *
     * @access public
     * @param string $str 待处理的字符串
     * @param integer $length 最小长度
     * @return boolean
     */
    public static function min_length($str, $length){
        return (mb_strlen($str, 'UTF-8') >= $length);
    }

    /**
     * 邮箱地址校验
     *
     * @access public
     * @param string
     * @return boolean
     */
    public static function email($str){
        return preg_match("/^[_a-z0-9-\.]+@([-a-z0-9]+\.)+[a-z]{2,}$/i", $str);
    }

    /**
     * 验证是否为网址
     *
     * @access public
     * @param string $str
     * @return boolean
     */
    public static function url($str){
        $parts = parse_url($str);
        if (!$parts) {
            return false;
        }

        return isset($parts['scheme']) &&
        in_array($parts['scheme'], array('http', 'https', 'ftp')) &&
        !preg_match('/(\(|\)|\\\|"|<|>|[\x00-\x08]|[\x0b-\x0c]|[\x0e-\x19])/', $str);
    }

    /**
     * 英文字符
     *
     * @access public
     * @param string
     * @return boolean
     */
    public static function alpha($str){
        return preg_match("/^([a-z])+$/i", $str) ? true : false;
    }

    /**
     * 英文字符和数字
     *
     * @access public
     * @param string
     * @return boolean
     */
    public static function alpha_numeric($str){
        return preg_match("/^([a-z0-9])+$/i", $str);
    }

    /**
     * 英文字符、数字、中下划线
     *
     * @access public
     * @param string
     * @return boolean
     */
    public static function alpha_dash($str){
        return preg_match("/^([_a-z0-9-])+$/i", $str) ? true : false;
    }

    /**
     * 中英文字符、数据、中下划线
     * @param $str
     * @return int
     */
    public function chinese_alpha_numeric_dash($str){
        return preg_match('/^[a-zA-Z0-9_\-\x{4e00}-\x{9fa5}]+$/u', $str);
    }

    /**
     * 对xss字符串的检测
     *
     * @access public
     * @param string $str
     * @return boolean
     */
    public static function xss_check($str){
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';

        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

            // &#x0040 @ search for the hex values
            $str = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $str); // with a ;
            // &#00064 @ 0{0,7} matches '0' zero to seven times
            $str = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $str); // with a ;
        }

        return !preg_match('/(\(|\)|\\\|"|<|>|[\x00-\x08]|[\x0b-\x0c]|[\x0e-\x19]|' . "\r|\n|\t" . ')/', $str);
    }

    /**
     * 是否为浮点数据
     *
     * @access public
     * @param integer
     * @return boolean
     */
    public static function is_float($str){
        return is_float($str);
    }

    /**
     * 是否为整型数据
     *
     * @access public
     * @param string
     * @return boolean
     */
    public static function is_integer($str){
        return is_numeric($str);
    }
}