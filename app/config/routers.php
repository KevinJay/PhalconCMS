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

);