<?php

/**
 * phalcon扩展过滤器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Core;

use \Phalcon\Filter;

class PhalBaseFilter extends Filter{

    /**
     * 自定义初始化函数
     */
    public function init(){
        /** 添加remove_xss过滤器 */
        $this -> add('remove_xss', function($value){
            return \Marser\App\Libs\Filter::remove_xss($value);
        });
    }

}
