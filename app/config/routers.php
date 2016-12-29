<?php

/**
 * 配置路由规则
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 *
 * 实例 ：支持正则
 * $key => array("controller" => "", "action" => "")
 */

return array(
    //后台路由规则
    '/admin/:controller/:action/:params' => array(
        'module' => 'backend',
        'controller'=>1,
        'action'=>2
    ),

    //文章详情路由
    '/article/:int.html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'detail',
        'aid' => 1
    ),

    //搜索路由
    '/search.html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
    ),

    //分类下的文章路由
    '/category/([a-zA-Z0-9\_\-]+).html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
        'category' => 1
    ),

    //标签下的文章路由
    '/tag/([a-zA-Z0-9\_\-]+).html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
        'tag' => 1
    ),

    //404页面路由
    '/404' => array(
        'module' => 'frontend',
        'controller' => 'index',
        'action' => 'notfound',
    )
);