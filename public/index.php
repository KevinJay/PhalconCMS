<?php

try {

    $runtime = get_cfg_var('marser.runtime');
    empty($runtime) && $runtime = 'dev';
    define('RUNTIME', $runtime);
    define('ROOT_PATH', dirname(__DIR__));

    $config = new \Phalcon\Config\Adapter\Php(ROOT_PATH . "/app/config/system/system_{$runtime}.php");

    /**
     * 引入loader.php
     */
    include ROOT_PATH . '/app/core/loader.php';

    /**
     * 引入services.php
     */
    include ROOT_PATH . '/app/core/services.php';

    /**
     * 处理请求
     */
    $application = new \Phalcon\Mvc\Application($di);

    $application -> registerModules(array(
        'frontend' => array(
            'className' => 'marser\app\frontend\FrontendModule',
            'path' => ROOT_PATH . '/app/frontend/FrontendModule.php',
        ),
        'backend' => array(
            'className' => 'marser\app\backend\BackendModule',
            'path' => ROOT_PATH . '/app/backend/BackendModule.php',
        ),
    ));

    echo $application->handle()->getContent();

}catch (\Exception $e) {
    $log = array(
        'file' => $e -> getFile(),
        'line' => $e -> getLine(),
        'code' => $e -> getCode(),
        'msg' => $e -> getMessage(),
        'trace' => $e -> getTraceAsString(),
    );

    $date = date('Ymd');
    $logger = new \Phalcon\Logger\Adapter\File(ROOT_PATH."/app/cache/logs/crash_{$date}.log");
    $logger -> error(json_encode($log));
}





