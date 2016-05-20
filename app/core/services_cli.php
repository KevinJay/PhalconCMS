<?php

/**
 * DI注册服务配置文件
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

use Phalcon\DI\FactoryDefault\CLI,
    Phalcon\Db\Profiler as DbProfiler,
    Phalcon\Db\Adapter\Pdo\Mysql;

$di = new CLI();

/**
 * DI注入DB配置
 */
$di->setShared('db', function () use ($config) {
    $dbconfig = $config -> database -> db;
    $dbconfig = $dbconfig -> toArray();
    if (!is_array($dbconfig) || count($dbconfig) == 0) {
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
            "host" => $dbconfig['host'],
            "port" => $dbconfig['port'],
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
$di->setShared('logger', function () use ($di) {
    $logger = \Marser\App\Core\PhalBaseLogger::getInstance();
    return $logger;
});

/**
 * DI注入api配置
 */
$di->setShared('apiConfig', function () use ($di) {
    $config = \Marser\App\Core\Config::getInstance('api');
    $config -> set_run_time(RUNTIME);
    return $config;
});

/**
 * DI注入system配置
 */
$di->setShared('systemConfig', function () use ($di) {
    $config = \Marser\App\Core\Config::getInstance('system');
    $config -> set_run_time(RUNTIME);
    return $config;
});
