<?php

/**
 * 系统配置--开发环境
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

return array(
    'app' => array(
        //项目名称
        'app_name' => 'PhalconCMS',

        //版本号
        'version' => '1.0',

        //根命名空间
        'root_namespace' => 'Marser',

        //前台配置
        'frontend' => array(
            //模块在URL中的pathinfo路径名
            'module_pathinfo' => '/',

            //控制器路径
            'controllers' => ROOT_PATH . '/app/frontend/controllers/',

            //视图路径
            'views' => ROOT_PATH . '/app/frontend/views/',

            //是否实时编译模板
            'is_compiled' => true,

            //模板路径
            'compiled_path' => ROOT_PATH . '/app/cache/compiled/frontend/',

            //前台静态资源URL
            'assets_url' => '/home/',
        ),

        //后台配置
        'backend' => array(
            //模块在URL中的pathinfo路径名
            'module_pathinfo' => '/admin/',

            //控制器路径
            'controllers' => ROOT_PATH . '/app/backend/controllers/',

            //视图路径
            'views' => ROOT_PATH . '/app/backend/views/',

            //是否实时编译模板
            'is_compiled' => true,

            //模板路径
            'compiled_path' => ROOT_PATH . '/app/cache/compiled/backend/',

            //后台静态资源URL
            'assets_url' => '/admin/',
        ),

        //类库路径
        'libs' => ROOT_PATH . '/app/libs/',

        //日志根目录
        'log_path' => ROOT_PATH . '/app/cache/logs/',

        //缓存路径
        'cache_path' => ROOT_PATH . '/app/cache/data/',
    ),

    //数据库表配置
    'database' => array(
        //数据库连接信息
        'db' => array(
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'admin',
            'password' => 'admin',
            'dbname' => 'test',
            'charset' => 'utf8',
        ),

        //表前缀
        'prefix' => 'marser_',
    ),
);