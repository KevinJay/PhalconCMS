<?php

/**
 * Phalcon配置扩展类
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 *
 */

namespace Marser\App\Core;

class Config{

    /**
     * 单例对象
     * @var
     */
    private static $_instance;

    /**
     * 配置数组
     * @var array
     */
    private static $configArray;

    /**
     * 配置文件
     * @var string
     */
    private $path;

    /**
     * 单例
     * @param string $path
     * @param string $file
     * @return array
     */
    public static function getInstance($path, $file = null){
        if (!isset(self::$_instance[$path]) || !(self::$_instance[$path] instanceof self)) {
            self::$_instance[$path] = new self($path, $file);
        }
        return self::$_instance[$path];
    }

    /**
     * 构造函数
     * @access private
     * @param string $path
     * @param string $file
     */
    private function __construct($path, $file = null){
        if (!isset(self::$configArray[$path]) || !is_array(self::$configArray[$path]) || count(self::$configArray[$path]) == 0) {
            $configArray = $this->_load_config($path, $file);
            if (is_array($configArray) && count($configArray) > 0) {
                self::$configArray[$path] = $configArray;
            }
        }
        $this->path = $path;
    }

    /**
     * 防止克隆单例对象
     */
    public function __clone(){
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    /**
     * 获取配置（自动匹配运行环境）
     * @access public
     * @param string 接收动态参数，以数组下标的先后顺序传递
     *      eg:get_api_config('CART_CENTER', 'URL', ...);
     *      不传任何参数，即获取整个配置数组
     * @return string $result
     */
    public function get(){
        $result = self::$configArray[$this->path];
        $argsArray = func_get_args();
        foreach ($argsArray as $key => $value) {
            //按下标索引取值
            if (isset($result[$value])) {
                $result = $result[$value];
            } else {
                $result = '';
            }
        }
        return $result;
    }

    /**
     * 加载配置文件
     * @access protected
     * @param string $path
     * @param string $file
     * @return object
     */
    protected function _load_config($path, $file = null){
        empty($file) && $file = $path;
        $configFile = dirname(__DIR__) . "/config/{$path}/{$file}_" . RUNTIME . ".php";
        if (!file_exists($configFile)) {
            throw new \Exception("配置文件：{$configFile}不存在");
        }
        $result = new \Phalcon\Config\Adapter\Php($configFile);
        $result = $result->toArray();
        return $result;
    }
}