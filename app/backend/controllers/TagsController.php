<?php

/**
 * 标签
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;

class TagsController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 标签列表页
     */
    public function indexAction(){
        $tid = intval($this -> request -> get('tid', 'trim'));
        $taginfo = null;
        if($tid > 0){
            $taginfo = $this -> get_repository('Tags') -> detail($tid);
        }
        $tagsList = $this -> get_repository('Tags') -> get_list();

        $this -> view -> setVars(array(
            'taginfo' => $taginfo,
            'tagsList' => $tagsList,
        ));
        $this -> view -> pick('tags/index');
    }

    /**
     * 保存标签（添加、编辑）
     */
    public function saveAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
            }
            $tid = intval($this -> request -> getPost('tid', 'trim'));
            $tagName = $this -> request -> getPost('name', 'trim');
            $slug = $this -> request -> getPost('slug', 'trim');
            /** 添加验证规则 */
            !empty($tid) && $this -> validator -> add_rule('tid', 'required', '系统错误，请刷新页面后重试');
            $this -> validator -> add_rule('name', 'required', '请填写标签名称')
                -> add_rule('name', 'chinese_alpha_numeric_dash', '站点名称由中英文字符、数字和中下划线组成');
            !empty($slug) && $this -> validator -> add_rule('slug', 'alpha_dash', '分类缩略名由英文字符、数字和中下划线组成');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'tid' => $tid,
                'name' => $tagName,
                'slug' => $slug,
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 保存标签 */
            $result = $this -> get_repository('Tags') -> save(array(
                'tag_name' => $tagName,
                'slug' => $slug,
            ), $tid);

            $this -> flashSession -> success('保存标签成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect('tags/index');
    }

    /**
     * 删除标签
     */
    public function deleteAction(){
        try{
            $tid = intval($this -> request -> get('tid', 'trim'));

            $this -> get_repository('Tags') -> delete($tid);

            $this -> flashSession -> success('删除标签成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

}