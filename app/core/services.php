<?php

/**
 * DI注册服务配置文件
 * @package app/core
 * @version $Id
 */

use Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Url as UrlResolver,
    Phalcon\Db\Profiler as DbProfiler,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine;

$di = new FactoryDefault();

/**
 * 设置路由
 */
$di->set('router', function(){
    $router = new \Phalcon\Mvc\Router();
    $router -> setDefaultModule('frontend');

    $routerRules = new \Phalcon\Config\Adapter\Php(ROOT_PATH . "/app/config/routers.php");
    foreach ($routerRules->toArray() as $key => $value){
        $router->add($key,$value);
    }

    return $router;
});

/**
 * DI注入cookies服务
 */
$di->set('cookies', function() {
    $cookies = new \Phalcon\Http\Response\Cookies();
    $cookies -> useEncryption(false);
    return $cookies;
});

/**
 * DI注入url服务
 */
$di -> set('url', function(){
    $url = new \Phalcon\Mvc\Url();
    return $url;
});

/**
 * DI注入DB配置
 */
$di->set('db', function () use($config) {
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
                $logger = \marser\app\core\PhalBaseLogger::getInstance();
                $logger -> debug_log("{$sql} {$executeTime}");
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
 * DI注入日志服务
 */
$di -> setShared('logger', function() use($di){
    $logger = \marser\app\core\PhalBaseLogger::getInstance();
    return $logger;
});

/**
 * DI注入api配置
 */
$di -> setShared('apiConfig', function() use($di){
    $config = \marser\app\core\Config::getInstance('api');
    return $config;
});

/**
 * DI注入system配置
 */
$di -> setShared('systemConfig', function() use($di){
    $config = \marser\app\core\Config::getInstance('system');
    return $config;
});
