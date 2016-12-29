<?php

/**
 * 分类模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Models;

use \Marser\App\Frontend\Models\BaseModel;

class CategorysModel extends BaseModel{

    const TABLE_NAME = 'categorys';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 统计分类总数
     * @param int $status
     * @return mixed
     */
    public function get_count($status=1){
        $status = intval($status);
        $count = $this -> count(array(
            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => $status,
            ),
        ));
        return $count;
    }

    /**
     * 获取分类数据
     * @param $cid
     * @return array
     * @throws \Exception
     */
    public function detail($cid){
        $category = array();
        $cid = intval($cid);
        if($cid < 0){
            throw new \Exception('参数错误');
        }

        $params = array(
            'conditions' => 'cid = :cid:',
            'bind' => array(
                'cid' => $cid
            )
        );
        $result = $this -> findFirst($params);
        if($result){
            $category = $result -> toArray();
        }
        return $category;
    }

    /**
     * 获取分类树
     * @param int $status
     * @return array
     */
    public function get_category_for_tree($status=1){
        $categoryList = array();
        $status = intval($status);
        $result = $this -> find(array(
            'columns' => 'cid, category_name, slug, parent_cid, path, sort, modify_time',
            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => $status,
            ),
            'order' => 'LENGTH(path) DESC, parent_cid DESC, sort asc',
        ));
        if($result){
            $categoryList = $result -> toArray();
        }
        return $categoryList;
    }

    /**
     * 根据缩略名获取分类数据
     * @param $slug
     * @return static
     * @throws \Exception
     */
    public function get_category_by_slug($slug){
        if(empty($slug)){
            throw new \Exception('参数错误');
        }
        $result = $this -> findFirst(array(
            'conditions' => 'slug = :slug:',
            'bind' => array(
                'slug' => $slug
            )
        ));
        if(!$result){
            throw new \Exception('获取分类数据失败');
        }
        return $result;
    }
}
