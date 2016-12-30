<?php

/**
 * 设置
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;

class OptionsController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 站点基础配置
     */
    public function baseAction(){
        try {
            $options = $this -> get_repository('Options') -> get_options_list();

            $this -> view -> setVars(array(
                'options' => $options,
            ));
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        $this -> view -> pick('options/base');
    }

    /**
     * 站点基础配置变更
     */
    public function savebaseAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
            }
            $siteName = $this -> request -> getPost('siteName', 'trim');
            $siteTitle = $this -> request -> getPost('siteTitle', 'remove_xss');
            $siteUrl = $this -> request -> getPost('siteUrl', 'trim');
            $cdnUrl = $this -> request -> getPost('cdnUrl', 'trim');
            $keywords = $this -> request -> getPost('keywords', 'remove_xss');
            $description = $this -> request -> getPost('description', 'remove_xss');
            /** 添加验证规则 */
            $this -> validator -> add_rule('siteName', 'required', '请填写站点名称')
                -> add_rule('siteName', 'chinese_alpha_numeric_dash', '站点名称由中英文字符、数字和中下划线组成');
            $this -> validator -> add_rule('siteTitle', 'required', '请填写站点标题');
            !empty($siteUrl) && $this -> validator -> add_rule('siteUrl', 'url', '请填写一个合法的URL地址');
            !empty($cdnUrl) && $this -> validator -> add_rule('cdnUrl', 'url', '请填写一个合法的URL地址');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'siteName' => $siteName,
                'siteTitle' => $siteTitle,
                'siteUrl' => $siteUrl,
                'cdnUrl' => $cdnUrl,
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 保存配置项 */
            $data = array(
                'site_name' => $siteName,
                'site_title' => $siteTitle,
                'site_url' => $siteUrl,
                'cdn_url' => $cdnUrl,
                'site_description' => $description,
                'site_keywords' => $keywords,
            );
            $this -> get_repository('Options') -> update($data);

            $this -> flashSession -> success('更新成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

    /**
     * 阅读设置
     */
    public function readAction(){
        try {
            $options = $this -> get_repository('Options') -> get_options_list();

            $this -> view -> setVars(array(
                'options' => $options,
            ));
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        $this -> view -> pick('options/read');
    }

    /**
     * 保存阅读相关配置
     */
    public function savereadAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
            }
            $pageNum = intval($this -> request -> getPost('pageNum', 'trim'));
            $recommendNum = intval($this -> request -> getPost('recommendNum', 'trim'));
            $relateNum = intval($this -> request -> getPost('relateNum', 'trim'));
            /** 添加验证规则 */
            $this -> validator -> add_rule('pageNum', 'required', '请填写每页文章数量');
            $this -> validator -> add_rule('recommendNum', 'required', '请填写推荐阅读显示的文章列表数目');
            $this -> validator -> add_rule('relateNum', 'required', '请填写关联的文章列表数目');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'pageNum' => $pageNum,
                'recommendNum' => $recommendNum,
                'relateNum' => $relateNum,
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 保存配置项 */
            $data = array(
                'page_article_number' => $pageNum,
                'recommend_article_number' => $recommendNum,
                'relate_article_number' => $relateNum,
            );
            $this -> get_repository('Options') -> update($data);

            $this -> flashSession -> success('更新成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }
}
