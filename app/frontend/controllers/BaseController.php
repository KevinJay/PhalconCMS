<?php

/**
 * 前台基类控制器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Controllers;

use \Marser\App\Core\PhalBaseController,
    \Marser\App\Frontend\Repositories\RepositoryFactory;

class BaseController extends PhalBaseController{

    public function initialize(){
        parent::initialize();
        $this -> set_common_vars();
    }

    /**
     * 设置模块公共变量
     */
    public function set_common_vars(){
        $this -> view -> setVars(array(
            'siteName' => $this -> get_repository('Options') -> get_option('site_name'),
            'siteTitle' => $this -> get_repository('Options') -> get_option('site_title'),
            'siteUrl' => rtrim($this -> get_repository('Options') -> get_option('site_url'), '/'),
            'siteDescription' => $this -> get_repository('Options') -> get_option('site_description'),
            'siteKeywords' => $this -> get_repository('Options') -> get_option('site_keywords'),
            'menuList' => $this -> get_repository('Menu') -> get_menu_list(),

            'assetsVersion' => strtotime(date('Y-m-d H', time()) . ":00:00"),
        ));

        $staticUrl = $this -> get_repository('Options') -> get_option('cdn_url');
        empty($staticUrl) && $staticUrl = $this->systemConfig->app->frontend->assets_url;
        $this -> url -> setStaticBaseUri($staticUrl);
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