<?php

/**
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

try {

    $runtime = 'dev';
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
            'className' => 'Marser\App\Frontend\FrontendModule',
            'path' => ROOT_PATH . '/app/frontend/FrontendModule.php',
        ),
        'backend' => array(
            'className' => 'Marser\App\Backend\BackendModule',
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





