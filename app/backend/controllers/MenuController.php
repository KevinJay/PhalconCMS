<?php

/**
 * 菜单管理
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace  Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;

class MenuController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 菜单列表
     */
    public function indexAction(){
        try{
            /** 获取菜单列表 */
            $menuList = $this -> get_repository('Menu') -> get_menu_list();

            $this -> view -> setVars(array(
                'menuList' => $menuList,
            ));
            $this -> view -> pick('menu/index');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect();
        }
    }

    /**
     * 添加 / 编辑菜单
     */
    public function writeAction(){
        try{
            $mid = intval($this -> request -> get('mid', 'trim'));
            $parentmid = intval($this -> request -> get('parentmid', 'trim'));
            $menu = array();
            if($mid > 0){
                /** 获取菜单数据 */
                $menu = $this -> get_repository('Menu') -> detail($mid);
            }
            /** 获取菜单树 */
            $menuList = $this -> get_repository('Menu') -> get_menu_list();

            $this -> view -> setVars(array(
                'menu' => $menu,
                'parentmid' => $parentmid,
                'menuList' => $menuList,
            ));
            $this -> view -> pick('menu/write');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect();
        }
    }

    /**
     * 保存菜单数据
     */
    public function saveAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
            }
            $mid = intval($this -> request -> getPost('mid', 'trim'));
            $name = $this -> request -> getPost('name', 'remove_xss');
            $url = $this -> request -> getPost('url', 'remove_xss');
            $parentmid = intval($this -> request -> getPost('parentmid', 'trim'));
            $sort = intval($this -> request -> getPost('sort', 'trim'));
            /** 添加验证规则 */
            $this -> validator -> add_rule('name', 'required', '请填写菜单名称');
            $this -> validator -> add_rule('url', 'required', '请填写菜单链接');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'name' => $name,
                'url' => $url,
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 菜单数据入库 */
            $this -> get_repository('Menu') -> save(array(
                'menu_name' => $name,
                'menu_url' => $url,
                'parent_mid' => $parentmid,
                'sort' => $sort
            ), $mid);
            $this -> flashSession -> success('保存菜单成功');

            return $this -> redirect('menu/index');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            $url = 'menu/write';
            !empty($mid) && $url .= "?mid={$mid}";
            return $this -> redirect($url);
        }
    }

    /**
     * 清除菜单缓存
     */
    public function refreshAction(){
        if($this -> get_repository('Menu') -> delete_menu_list_cache()){
            $this -> flashSession -> success('清除菜单缓存成功');
        }else{
            $this -> flashSession -> error('清除菜单缓存失败');
        }
        return $this -> redirect();
    }

    /**
     * 更新菜单排序
     */
    public function savesortAction(){
        try{
            $mid = intval($this -> request -> get('mid', 'trim'));
            $sort = intval($this -> request -> get('sort', 'trim'));

            $affectedRows = $this -> get_repository('Menu') -> update_record(array(
                'sort' => $sort,
            ), $mid);
            if(!$affectedRows){
                throw new \Exception('更新菜单排序失败');
            }

            $this -> ajax_return('更新菜单排序成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> ajax_return($e -> getMessage());
        }
        $this -> view -> disable();
    }

    /**
     * 删除菜单
     */
    public  function deleteAction(){
        try{
            $mid = intval($this -> request -> get('mid', 'trim'));
            $this -> get_repository('Menu') -> delete($mid);

            $this -> flashSession -> success('删除菜单成功');
        }catch (\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

}