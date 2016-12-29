<?php

/**
 * phalcon模板扩展类
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Core;

use \Phalcon\Mvc\View\Engine\Volt;

class PhalBaseVolt extends Volt{

    /**
     * 添加扩展函数
     */
    public function initFunction(){
        $compiler = $this->getCompiler();

        /** 添加get_page_url函数 */
        $compiler -> addFunction('get_page_url', function($resolvedArgs, $exprArgs) use ($compiler){
            return '\Marser\App\Helpers\PaginatorHelper::get_page_url(' . $resolvedArgs . ')';
        });

        /** 添加str_repeat函数 */
        $compiler -> addFunction('str_repeat', 'str_repeat');

        /** 添加substr_count函数 */
        $compiler -> addFunction('substr_count', 'substr_count');

        /** 添加explode函数 */
        $compiler -> addFunction('explode', 'explode');

        /** 添加array_rand函数 */
        $compiler -> addFunction('array_rand', 'array_rand');
    }


}



