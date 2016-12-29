<?php

/**
 * 过滤器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Libs;

class Filter {

    /**
     * 清除xss特殊字符
     * @param $str
     * @return mixed
     */
    public static function remove_xss($str){
        $str = filter_var(trim($str), FILTER_SANITIZE_STRING);
        return $str;
    }
}