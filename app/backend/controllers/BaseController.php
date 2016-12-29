<?php

/**
 * 后台基类控制器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Core\PhalBaseController,
    \Marser\App\Backend\Repositories\RepositoryFactory;

class BaseController extends PhalBaseController{

    public function initialize(){
        parent::initialize();
        $this -> login_check();
        $this -> set_common_vars();
    }

    /**
     * 设置模块公共变量
     */
    public function set_common_vars(){
        $this -> view -> setVars(array(
            'title' => $this -> systemConfig -> app -> app_name,
            'userinfo' => $this -> session -> get('user'),
            'assetsVersion' => strtotime(date('Y-m-d H', time()) . ":00:00"),
        ));
    }

    /**
     * 登录检测处理
     * @return bool
     */
    public function login_check(){
        if(!$this -> get_repository('Users') -> login_check()){
            return $this -> redirect("passport/index");
        }
        return true;
    }

    /**
     * 获取业务对象
     * @param $repositoryName
     * @return object
     * @throws \Exception
     */
    protected function get_repository($repositoryName){
        return RepositoryFactory::get_repository($repositoryName);
    }

    /**
     * 页面跳转
     * @param null $url
     */
    protected function redirect($url=NULL){
        empty($url) && $url = $this -> request -> getHeader('HTTP_REFERER');
        $this -> response -> redirect($url);
    }
}