<?php

/**
 * 菜单仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Repositories;

use \Marser\App\Frontend\Repositories\BaseRepository,
    \Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Menu extends BaseRepository{

    /**
     * 分类缓存key
     */
    const MENU_TREE_CACHE_KEY = 'menu_tree';

    /**
     * 分类缓存周期（秒） 一个月
     */
    const MENU_CACHE_TTL = 2592000;

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取菜单树
     * @return array
     */
    public function get_menu_list(){
        /** 从缓存中读取菜单数据 */
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::MENU_TREE_CACHE_KEY, self::MENU_CACHE_TTL)){
            $menuList = $cache -> get(self::MENU_TREE_CACHE_KEY, self::MENU_CACHE_TTL);
            $menuList = json_decode($menuList, true);
            if(is_array($menuList) && count($menuList) > 0){
                return $menuList;
            }
        }
        /** 从数据库读取全部菜单数据 */
        $menuList = $this -> get_menu_tree_list();
        /** 设置缓存 */
        $cache -> save(self::MENU_TREE_CACHE_KEY, json_encode($menuList), self::MENU_CACHE_TTL);
        return $menuList;
    }

    /**
     * 获取菜单列表
     * @return array|mixed
     */
    protected function get_menu_tree_list(){
        $menuArray = array();
        /** 获取所有菜单数据 */
        $menuList = $this -> get_model('MenuModel') -> get_menu_for_tree();
        if(!is_array($menuList) || count($menuList) == 0){
            return $menuArray;
        }
        /** 数组索引替换 */
        foreach($menuList as $mk=>$mv){
            $menuArray[$mv['mid']] = $mv;
        }
        unset($menuList);
        /** 迁移数组，形成数组树形结构 */
        foreach($menuArray as $k=>&$v){
            if(isset($menuArray[$v['parent_mid']])){
                $menuArray[$v['parent_mid']]['son'][$v['mid']] = $v;
                unset($menuArray[$k]);
            }
        }
        /** 递归简化为二维数组 */
        $menuArray = $this -> recursive_menu_tree($menuArray);
        return $menuArray;
    }

    /**
     * 递归菜单树转成为二维数组
     * @param array $categoryTree
     * @return mixed
     */
    protected function recursive_menu_tree(array $menuArray){
        static $menuList = array();
        foreach($menuArray as $k=>$v){
            if(isset($v['son']) && is_array($v['son']) && count($v['son']) > 0){
                $temp = $v;
                unset($temp['son']);
                $menuList[$v['mid']] = $temp;
                $menuList[$v['mid']]['leaf_node'] = 0; //是否为叶子节点
                $this -> recursive_menu_tree($v['son']);
            }else{
                $menuList[$v['mid']] = $v;
                $menuList[$v['mid']]['leaf_node'] = 1; //是否为叶子节点
            }
        }
        return $menuList;
    }

}