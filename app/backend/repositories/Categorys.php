<?php

/**
 * 分类业务仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Repositories;

use \Marser\App\Backend\Repositories\BaseRepository,
    \Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

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
     * 删除分类树缓存
     * @return array
     */
    public function delete_category_list_cache(){
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::CATEGORY_TREE_CACHE_KEY, self::CATEGORY_CACHE_TTL)){
            return $cache -> delete(self::CATEGORY_TREE_CACHE_KEY);
        }
        return true;
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
     * 统计分类总数
     * @return mixed
     */
    public function get_count(){
        $count = $this -> get_model('CategorysModel') -> get_count();
        return $count;
     }

    /**
     * 保存分类数据
     * @param array $data
     * @param $cid
     * @return int|mixed
     * @throws \Exception
     */
    public function save(array $data, $cid){
        $cid = intval($cid);
        if($cid <= 0){
            /** 添加分类 */
            $this -> create($data);
        }else{
            /** 更新分类 */
            $this -> update($data, $cid);
        }
    }

    /**
     * 删除分类记录（软删除）
     * @param $cid
     * @return bool
     */
    public function delete($cid){
        $cid = intval($cid);
        if($cid <= 0){
            throw new \Exception('请选择需要删除的分类');
        }
        $categorysModel = $this->get_model('CategorysModel');
        $categoryArray = $this -> get_category_list();
        try {
            /** 创建单独的事务管理器，并请求一个事务 */
            $transactionManager = new TransactionManager();
            $transaction = $transactionManager -> get();

            $categorysModel -> setTransaction($transaction);
            $affectedRows = $categorysModel -> update_record(array(
                'status' => 0
            ), $cid);
            if ($affectedRows <= 0) {
                $transaction -> rollback('删除分类失败');
            }
            /** 若当前cid不是叶子节点，更新其子分类的parent_cid和path */
            if (isset($categoryArray[$cid]) && $categoryArray[$cid]['leaf_node'] == 0) {
                $affectedRowsPath = $categorysModel -> update_path($categoryArray[$cid]['path'], "{$categoryArray[$cid]['path']}{$cid}/");
                if ($affectedRowsPath <= 0) {
                    $transaction -> rollback('更新分类路径失败，影响行数为0');
                }
                $affectedRowsParentcid = $categorysModel -> update_parentcid($categoryArray[$cid]['parent_cid'], $cid);
                if ($affectedRowsParentcid <= 0) {
                    $transaction -> rollback('更新子分类parent_cid失败，影响行为为0');
                }
            }
            /** 事务提交 */
            $transaction -> commit();
            /** 清除分类缓存 */
            $this -> delete_category_list_cache();
        }catch(\Phalcon\Mvc\Model\TransportException $e){
            throw new \Exception($e -> getMessage());
        }
    }

    /**
     * 更新分类排序
     * @param $sort
     * @param $cid
     * @return mixed
     * @throws \Exception
     */
    public function update_sort($sort, $cid){
        $sort = intval($sort);
        $cid = intval($cid);
        if($cid <= 0){
            throw new \Exception('参数错误');
        }
        /** 更新分类排序 */
        $affectedRows = $this -> get_model('CategorysModel') -> update_record(array(
            'sort' => $sort
        ), $cid);
        if(!$affectedRows){
            throw new \Exception('更新分类排序失败');
        }
        /** 清空分类缓存 */
        $this -> delete_category_list_cache();

        return $affectedRows;
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

    /**
     * 新增分类
     * @param array $data
     * @return int
     * @throws \Exception
     */
    protected function create(array $data){
        /** 检测分类名称或者缩略名是否已存在 */
        $isExist = $this -> get_model('CategorysModel') -> category_is_exist($data['category_name'], $data['slug']);
        if($isExist && $isExist -> count() > 0){
            throw new \Exception('分类名称或缩略名已存在');
        }
        /** 获取分类路径 */
        $path = '/0/';
        if(isset($data['parent_cid']) && !empty($data['parent_cid'])){
            $path = $this -> get_path_by_parentcid($data['parent_cid']);
            if(empty($path)){
                throw new \Exception('获取分类路径失败');
            }
            $path .= "{$data['parent_cid']}/";
        }
        $data['path'] = $path;
        /** 分类数据入库 */
        $cid = $this -> get_model('CategorysModel') -> insert_record($data);
        $cid = intval($cid);
        if($cid <= 0){
            throw new \Exception('获取新增分类ID失败');
        }
        /** 清除分类缓存 */
        $this -> delete_category_list_cache();

        return $cid;
    }

    /**
     * 更新分类
     * @param array $data
     * @param $cid
     * @return mixed
     * @throws \Exception
     */
    protected function update(array $data, $cid){
        $cid = intval($cid);
        if($cid <= 0 || count($data) == 0){
            throw new \Exception('参数错误');
        }
        if(isset($data['parent_cid']) && $data['parent_cid'] == $cid){
            throw new \Exception('不能选择本分类为父分类');
        }
        /** 检测分类名称或者缩略名是否已存在 */
        $isExist = $this -> get_model('CategorysModel') -> category_is_exist($data['category_name'], $data['slug'], $cid);
        if($isExist && $isExist -> count() > 0){
            throw new \Exception('分类名称或缩略名已存在');
        }
        /** 获取分类路径 */
        $path = '/0/';
        if(isset($data['parent_cid']) && $data['parent_cid'] > 0){
            $path = $this -> get_path_by_parentcid($data['parent_cid']);
            if(empty($path)){
                throw new \Exception('获取分类路径失败');
            }
            $path .= "{$data['parent_cid']}/";
        }
        $data['path'] = $path;
        /** 更新分类数据 */
        $affectedRows = $this -> get_model('CategorysModel') -> update_record($data, $cid);
        if(!$affectedRows){
            throw new \Exception('更新失败');
        }
        /** 清除分类缓存 */
        $this -> delete_category_list_cache();

        return $affectedRows;
    }

    /**
     * 根据parent_cid获取path
     * @param $cid
     * @return string
     */
    protected function get_path_by_parentcid($cid){
        $path = '';
        $category = $this -> get_model('CategorysModel') -> detail($cid);
        if(isset($category['path']) && !empty($category['path'])){
            $path = $category['path'];
        }
        return $path;
    }
}