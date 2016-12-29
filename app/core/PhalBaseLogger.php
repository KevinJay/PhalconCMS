<?php

/**
 * Phalcon日志扩展
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Core;

use \Phalcon\DI;

class PhalBaseLogger {

    private static $_instance;

    private static $_logger;

    /**
     * 禁止克隆
     */
    public function __clone() {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }
    /**
     * 取得单例logs的实例
     * @param string $config
     * @return BbgLogs
     */
    public static function getInstance($file=null) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($file);
        }
        return self::$_instance;
    }

    public function __construct($file=null){
        if(!empty($file)){
            $logFile = $file;
        }else{
            $fileName = date('YmdH', time());
            $systemConfig = DI::getDefault() -> get('systemConfig');
            $logPath = $systemConfig -> app -> log_path;
            $logFile = "{$logPath}/{$fileName}.log";
        }

        $logger = new \Phalcon\Logger\Adapter\File($logFile);
        self::$_logger = $logger;
    }

    /**
     * 日志记录
     * @param $log
     * @param $level 日志等级
     * @link https://docs.phalconphp.com/zh/latest/reference/logging.html
     */
    public function write_log($log, $level=''){
        if(is_array($log)){
            $log = json_encode($log);
        }
        empty($level) && $level = 'error';
        $level = strtolower($level);
        self::$_logger -> $level($log);
    }
}