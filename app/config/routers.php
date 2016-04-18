<?php

/**
 * 配置路由规则
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

);