<?php

/**
 * 文章-分类关联模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Backend\Models\BaseModel;

class ArticlesCategorysModel extends BaseModel{

    const TABLE_NAME = 'articles_categorys';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 插入记录
     * @param array $data
     * @return bool|int
     * @throws \Exception
     */
    public function insert_record(array $data){
        if(!is_array($data) || count($data) == 0){
            throw new \Exception('参数错误');
        }
        $clone = clone $this;
        $result = $clone -> create($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $id = $clone -> id;
        return $id;
    }

    /**
     * 删除文章和分类的关联记录（物理删除）
     * @param $aid
     * @return bool
     * @throws \Exception
     */
    public function delete_record($aid){
        $aid = intval($aid);
        if($aid <= 0){
            throw new \Exception('参数错误');
        }
        $phql = "DELETE FROM " . __CLASS__ . " WHERE aid = :aid: ";
        $result = $this -> getModelsManager() -> executeQuery($phql, array(
            'aid' => $aid
        ));
        if(!$result->success()){
            throw new \Exception(implode(',', $result->getMessages()));
        }
        return $result;
    }

    /**
     * 获取文章所属分类
     * @param array $aids
     * @return mixed
     * @throws \Exception
     */
    public function get_categorys_by_aids(array $aids){
        if(!is_array($aids) || count($aids) == 0){
            throw new \Exception('参数错误');
        }
        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('ac' => __CLASS__));
        $builder->addFrom(__NAMESPACE__.'\CategorysModel', 'c');
        $builder->columns(array(
            'ac.aid', 'c.cid', 'c.category_name'
        ));
        $result = $builder->where('ac.aid IN ({aid:array})', array('aid'=>$aids))
            ->andWhere('ac.cid = c.cid')
            ->andWhere('c.status = :status:', array('status' => 1))
            ->getQuery()
            ->execute();
        if(!$result){
            throw new \Exception('获取文章所属分类失败');
        }
        return $result;
    }
}