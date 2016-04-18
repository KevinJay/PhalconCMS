<?php

namespace marser\app\helpers;

use \Phalcon\Di;

class LoggerHelper {

    /**
     * 日志记录
     * @param $log
     * @param string $level
     */
    public static function write_log($log, $level=''){
        if(is_array($log)){
            $log = json_encode($log);
        }
        $logger = Di::getDefault() -> getLogger();
        $logger -> write_log($log);
    }

    /**
     * 异常日志
     * @param $exception
     */
    public static function exception_log($exception){
        $log['file'] = $exception -> getFile();
        $log['line'] = $exception -> getLine();
        $log['code'] = $exception -> getCode();
        $log['msg'] = $exception -> getMessage();
        $log['trace'] = $exception -> getTraceAsString();

        $logger = Di::getDefault() -> getLogger();
        $logger -> write_log($log);
    }
}