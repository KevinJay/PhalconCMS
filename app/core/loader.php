<?php

/**
 * 注册命文件
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

$loader = new \Phalcon\Loader();

/**
 * 注册命名空间
 */
$loader -> registerNamespaces(array(
    'Marser' => ROOT_PATH,

    'Marser\App\Core' => ROOT_PATH . '/app/core',
    'Marser\App\Helpers' => ROOT_PATH . '/app/helpers',
    'Marser\App\Libs' => ROOT_PATH . '/app/libs',
    'Marser\App\Service' => ROOT_PATH . '/app/service',
    'Marser\App\Tasks' => ROOT_PATH . '/app/tasks',

    'Marser\App\Frontend\Controllers' => ROOT_PATH . '/app/frontend/controllers',
    'Marser\App\Frontend\Models' => ROOT_PATH . '/app/frontend/models',
    'Marser\App\Frontend\Repositories' => ROOT_PATH . '/app/frontend/repositories',

    'Marser\App\Backend\Controllers' => ROOT_PATH . '/app/backend/controllers',
    'Marser\App\Backend\Models' => ROOT_PATH . '/app/backend/models',
    'Marser\App\Backend\Repositories' => ROOT_PATH . '/app/backend/repositories',
)) -> register();