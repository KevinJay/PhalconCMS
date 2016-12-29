<?php

/**
 * 文章-标签关联模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Backend\Models\BaseModel,
    \Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class ArticlesTagsModel extends BaseModel{

    const TABLE_NAME = 'articles_tags';

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
     * 删除标签关联关系（物理删除）
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
     * 根据文章ID获取标签数据
     * @param $aid
     * @return mixed
     * @throws \Exception
     */
    public function get_tags_by_aids(array $aids){
        if(!is_array($aids) || count($aids) == 0){
            throw new \Exception('参数错误');
        }
        $builder = $this->getModelsManager()->createBuilder();
        $builder->columns(array(
            'atags.aid', 't.tid', 't.tag_name'
        ));
        $builder->from(array('atags' => __CLASS__));
        $builder->addFrom(__NAMESPACE__ . '\\TagsModel', 't');
        $result = $builder->where("atags.aid IN ({aid:array})", array('aid' => $aids))
            ->andWhere("atags.tid = t.tid")
            ->andWhere("t.status = 1")
            ->getQuery()
            ->execute();
        if(!$result){
            throw new \Exception('获取文章关联的标签数据失败');
        }
        return $result;
    }
}
