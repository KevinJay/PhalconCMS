<?php

/**
 * DI注册服务配置文件
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Profiler as DbProfiler,
    Phalcon\Mvc\Model\Manager as ModelsManager,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    Phalcon\Session\Adapter\Files as Session;

$di = new FactoryDefault();

/**
 * 设置路由
 */
$di -> set('router', function(){
    $router = new \Phalcon\Mvc\Router();
    $router -> setDefaultModule('frontend');

    $routerRules = new \Phalcon\Config\Adapter\Php(ROOT_PATH . "/app/config/routers.php");
    foreach ($routerRules->toArray() as $key => $value){
        $router->add($key,$value);
    }

    return $router;
});

/**
 * DI注册session服务
 */
$di -> setShared('session', function(){
    $session = new Session();
    $session -> start();
    return $session;
});

/**
 * DI注册cookies服务
 */
$di -> set('cookies', function() {
    $cookies = new \Phalcon\Http\Response\Cookies();
    $cookies -> useEncryption(false);
    return $cookies;
});

/**
 * DI注册url服务
 */
$di -> set('url', function(){
    $url = new \Phalcon\Mvc\Url();
    return $url;
});

/**
 * DI注册DB配置
 */
$di -> set('db', function () use($config) {
    $dbconfig = $config -> database -> db;
    $dbconfig = $dbconfig -> toArray();
    if (!is_array($dbconfig) || count($dbconfig)==0) {
        throw new \Exception("the database config is error");
    }

    if (RUNTIME != 'pro') {
        $eventsManager = new \Phalcon\Events\Manager();
        // 分析底层sql性能，并记录日志
        $profiler = new DbProfiler();
        $eventsManager -> attach('db', function ($event, $connection) use ($profiler) {
            if($event -> getType() == 'beforeQuery'){
                //在sql发送到数据库前启动分析
                $profiler -> startProfile($connection -> getSQLStatement());
            }
            if($event -> getType() == 'afterQuery'){
                //在sql执行完毕后停止分析
                $profiler -> stopProfile();
                //获取分析结果
                $profile = $profiler -> getLastProfile();
                $sql = $profile->getSQLStatement();
                $executeTime = $profile->getTotalElapsedSeconds();
                //日志记录
                $logger = \Marser\App\Core\PhalBaseLogger::getInstance();
                $logger -> write_log("{$sql} {$executeTime}", 'debug');
            }
        });
    }

    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $dbconfig['host'], "port" => $dbconfig['port'],
        "username" => $dbconfig['username'],
        "password" => $dbconfig['password'],
        "dbname" => $dbconfig['dbname'],
        "charset" => $dbconfig['charset'])
    );

    if(RUNTIME != 'pro') {
        /* 注册监听事件 */
        $connection->setEventsManager($eventsManager);
        /* 注册监听事件 */
    }

    return $connection;
});

/**
 * DI注册modelsManager服务
 */
$di -> setShared('modelsManager', function() use($di){
    return new ModelsManager();
});

/**
 * DI注册日志服务
 */
$di -> setShared('logger', function() use($di){
    $logger = \Marser\App\Core\PhalBaseLogger::getInstance();
    return $logger;
});

/**
 * DI注册api配置
 */
$di -> setShared('apiConfig', function() use($di){
    $config = \Marser\App\Core\Config::getInstance('api');
    return $config;
});

/**
 * DI注册system配置
 */
$di -> setShared('systemConfig', function() use($di){
    $config = \Marser\App\Core\Config::getInstance('system');
    return $config;
});

/**
 * DI注册自定义验证器
 */
$di -> setShared('validator', function() use($di){
    $validator = new \Marser\App\Libs\Validator();
    return $validator;
});

