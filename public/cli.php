<?php

/**
 * 定时任务
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

try {

    if (strpos(php_sapi_name(), 'cli') === false) {
        throw new \Exception('403 Forbidden');
    }

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
    include ROOT_PATH . '/app/core/services_cli.php';

    /**
     * 处理请求
     */
    $console = new \Phalcon\CLI\Console();
    $console->setDI($di);

    $url = $argv[1];
    $urlArray = parse_url($url);
    if(!is_array($urlArray) || count($urlArray) == 0){
        throw new \Exception('url parse error');
    }
    if(!isset($urlArray['path']) || empty($urlArray['path'])){
        throw new \Exception('url parse error');
    }
    $path = trim($urlArray['path']);
    $query = isset($urlArray['query']) ? trim($urlArray['query']) : '';
    $path = trim($path, '/');
    $pathArray = explode('/', $path);
    if(!isset($pathArray[0]) || empty($pathArray[0])){
        throw new \Exception('task name is empty');
    }
    if(!isset($pathArray[1]) || empty($pathArray[1])){
        throw new \Exception('action name is empty');
    }
    $taskName = '\albatross\app\tasks\\' . ucwords($pathArray[0]);
    $actionName = $pathArray[1];

    $arguments = array(
        'task' => $taskName,
        'action' => $actionName,
        'params' => $query
    );
    $console -> handle($arguments);

} catch (\Exception $e) {
    echo $e->getMessage();
}
