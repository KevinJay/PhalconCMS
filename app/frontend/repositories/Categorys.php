<?php

/**
 * 分类业务仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Repositories;

use \Marser\App\Frontend\Repositories\BaseRepository;

class Categorys extends BaseRepository{

    /**
     * 分类缓存key
     */
    const CATEGORY_TREE_CACHE_KEY = 'category_tree';

    /**
     * 分类缓存周期（秒）
     */
    const CATEGORY_CACHE_TTL = 86400;

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取分类树
     * @return array
     * @throws \Exception
     */
    public function get_category_list(){
        /** 从缓存中读取 */
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::CATEGORY_TREE_CACHE_KEY, self::CATEGORY_CACHE_TTL)){
            $categoryArray = $cache -> get(self::CATEGORY_TREE_CACHE_KEY, self::CATEGORY_CACHE_TTL);
            $categoryArray = json_decode($categoryArray, true);
            if(is_array($categoryArray) && count($categoryArray) > 0){
                return $categoryArray;
            }
        }
        /** 从数据库中读取分类数据 */
        $categoryArray = $this -> get_category_tree_list();
        /** 设置缓存 */
        $cache -> save(self::CATEGORY_TREE_CACHE_KEY, json_encode($categoryArray), self::CATEGORY_CACHE_TTL);
        return $categoryArray;
    }

    /**
     * 获取分类数据
     * @param $cid
     * @return array
     * @throws \Exception
     */
    public function detail($cid){
        $category = $this -> get_model('CategorysModel') -> detail($cid);
        return $category;
    }

    /**
     * 根据缩略名获取分类数据
     * @param $slug
     * @return mixed
     */
    public function get_category_by_slug($slug){
        $category = $this -> get_model('CategorysModel') -> get_category_by_slug($slug);
        return $category;
    }

    /**
     * 统计分类总数
     * @return int
     */
    public function get_count(){
        $count = $this -> get_model('CategorysModel') -> get_count();
        $count = intval($count);
        return $count;
    }

    /**
     * 获取分类列表
     * @return array
     */
    protected function get_category_tree_list(){
        $categoryArray = array();
        /** 获取所有分类数据 */
        $categoryList = $this -> get_model('CategorysModel') -> get_category_for_tree();
        if(!is_array($categoryList) || count($categoryList) == 0){
            return $categoryArray;
        }
        /** 数组索引替换 */
        foreach($categoryList as $clk=>$clv){
            $categoryArray[$clv['cid']] = $clv;
        }
        unset($categoryList);
        /** 迁移数组，形成数组树形结构 */
        foreach($categoryArray as $cak=>&$cav){
            if(isset($categoryArray[$cav['parent_cid']])){
                $categoryArray[$cav['parent_cid']]['son'][$cav['cid']] = $cav;
                unset($categoryArray[$cak]);
            }
        }

        /** 递归简化为二维数组 */
        $categoryArray = $this -> recursive_category_tree($categoryArray);
        return $categoryArray;
    }

    /**
     * 递归分类树转成为二维数组
     * @param array $categoryTree
     * @return mixed
     */
    protected function recursive_category_tree(array $categoryTree){
        static $categoryList = array();
        foreach($categoryTree as $ck=>$cv){
            if(isset($cv['son']) && is_array($cv['son']) && count($cv['son']) > 0){
                $temp = $cv;
                unset($temp['son']);
                $categoryList[$cv['cid']] = $temp;
                $categoryList[$cv['cid']]['leaf_node'] = 0; //是否为叶子节点
                $this -> recursive_category_tree($cv['son']);
            }else{
                $categoryList[$cv['cid']] = $cv;
                $categoryList[$cv['cid']]['leaf_node'] = 1; //是否为叶子节点
            }
        }
        return $categoryList;
    }
}