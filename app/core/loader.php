<?php

/**
 * 注册路由文件
 */

$loader = new \Phalcon\Loader();

/**
 * 注册命名空间
 */
$loader -> registerNamespaces(array(
    'marser' => ROOT_PATH,
)) -> register();