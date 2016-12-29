<?php

/**
 * 控制面板
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController,
    \Marser\App\Libs\ServerNeedle;

class DashboardController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 控制台页面
     */
    public function indexAction(){
        /** 统计文章数 */
        $articlesCount = $this -> get_repository('Articles') -> get_count();
        /** 统计分类数 */
        $categorysCount = $this -> get_repository('Categorys') -> get_count();
        /** 统计标签数 */
        $tagsCount = $this -> get_repository('Tags') -> get_count();
        /** 获取服务器信息 */
        $systemInfo = array(
            'osName' => ServerNeedle::os_name(),
            'osVersion' => ServerNeedle::os_version(),
            'serverName' => ServerNeedle::server_host(),
            'serverIp' => ServerNeedle::server_ip(),
            'serverSoftware' => ServerNeedle::server_software(),
            'serverLanguage' => ServerNeedle::accept_language(),
            'serverPort' => ServerNeedle::server_port(),
            'phpVersion' => ServerNeedle::php_version(),
            'phpSapi' => ServerNeedle::php_sapi_name(),
        );

        $this -> view -> setVars(array(
            'articlesCount' => $articlesCount,
            'categorysCount' => $categorysCount,
            'tagsCount' => $tagsCount,
            'appVersion' => $this -> systemConfig -> app -> version,
            'systemInfo' => $systemInfo,
        ));
        $this -> view -> pick('dashboard/index');
    }
}
