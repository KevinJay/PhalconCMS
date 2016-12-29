<?php

/**
 * 首页
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Controllers;

use \Marser\App\Frontend\Controllers\BaseController;

class IndexController extends BaseController{

    /**
     * 首页跳转
     */
    public function indexAction(){
        $this -> dispatcher -> forward(array(
            'controller' => 'article',
            'action' => 'list',
        ));
    }

    /**
     * 404 not found
     */
    public function notfoundAction(){
        $this -> view -> disableLevel(array(
            /** 关闭分层渲染 */
            \Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT => false,
        ));
        $this -> view -> pick('index/404');
    }

}