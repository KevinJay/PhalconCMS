<?php

/**
 * 服务器探针
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Libs;

class ServerNeedle {

    /**
     * 服务器操作系统名称
     * @return string
     */
    public static function os_name(){
        return PHP_OS;
    }

    /**
     * 服务器版本名称
     * @return string
     */
    public static function os_version(){
        return php_uname('r');
    }

    /**
     * 服务器域名
     * @return mixed
     */
    public static function server_host(){
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * 服务器IP
     * @return mixed
     */
    public static function server_ip(){
        return $_SERVER['SERVER_ADDR'];
    }

    /**
     * web服务器信息
     * @return mixed
     */
    public static function server_software(){
        return $_SERVER['SERVER_SOFTWARE'];
    }

    /**
     * 服务器语言
     * @return string
     */
    public static function accept_language(){
        return getenv("HTTP_ACCEPT_LANGUAGE");
    }

    /**
     * 服务器端口
     * @return string
     */
    public static function server_port(){
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * PHP版本
     * @return string
     */
    public static function php_version(){
        return PHP_VERSION;
    }

    /**
     * PHP运行方式
     * @return string
     */
    public static function php_sapi_name(){
        return strtoupper(php_sapi_name());
    }
}