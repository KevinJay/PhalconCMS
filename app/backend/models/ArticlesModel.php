<?php

/**
 * 文章模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Backend\Models\BaseModel,
    \Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class ArticlesModel extends BaseModel{

    const TABLE_NAME = 'articles';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 获取文章列表
     * @param int $page
     * @param array $ext
     * @return mixed
     * @throws \Exception
     */
    public function get_list($page, $pagesize=10, array $ext=array()){
        $page = intval($page);
        $page <= 0 && $page = 1;
        $pagesize = intval($pagesize);
        ($pagesize <= 0 || $pagesize > 20) && $pagesize = 10;

        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('a' => __CLASS__));
        $builder->columns(array(
            'a.aid', 'a.title', 'a.status', 'a.view_number', 'a.is_recommend', 'a.is_top', 'a.create_time',
            'a.modify_by', 'a.modify_time'
        ));
        $builder->where('a.status > :status:', array('status' => 0));
        if(isset($ext['cid']) && $ext['cid'] > 0){
            $builder->addFrom(__NAMESPACE__ . '\\ArticlesCategorysModel', 'ac');
            $builder->andWhere("ac.cid = :cid:", array('cid' => $ext['cid']));
            $builder->andWhere("ac.aid = a.aid");
        }
        if(isset($ext['keyword']) && !empty($ext['keyword'])){
            $builder->andWhere("a.title like :title:", array('title' => "%{$ext['keyword']}%"));
        }
        $builder->orderBy('a.create_time DESC');

        $paginator = new PaginatorQueryBuilder(array(
            'builder' => $builder,
            'limit' => $pagesize,
            'page' => $page,
        ));
        $result = $paginator->getPaginate();
        return $result;
    }

    /**
     * 获取文章数据
     * @param $aid
     * @return mixed
     * @throws Exception
     */
    public function detail($aid){
        $aid = intval($aid);
        if($aid <= 0){
            throw new Exception('参数错误');
        }
        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('a' => __CLASS__));
        $builder->addFrom(__NAMESPACE__ . '\\ContentsModel', 'c');
        $builder->columns(array(
            'a.aid', 'a.title', 'a.status', 'a.create_time', 'a.modify_by', 'a.modify_time', 'c.markdown'
        ));
        $result = $builder->where("a.status > :status:", array('status' => 0))
            ->andWhere("a.aid = :aid:", array('aid' => $aid))
            ->andWhere("c.relateid = a.aid")
            ->limit(1)
            ->getQuery()
            ->execute();
        if(!$result){
            throw new \Exception('获取文章数据失败');
        }
        return $result;
    }

    /**
     * 	Is executed before the fields are validated for not nulls/empty strings
     *  or foreign keys when an insertion operation is being made
     */
    public function beforeValidationOnCreate(){
        $this -> create_by = $this -> _user['uid'];
        if(empty($this -> create_time) || !strtotime($this -> create_time)){
            $this -> create_time = date('Y-m-d H:i:s');
        }
        $this -> modify_by = $this -> _user['uid'];
        $this -> modify_time = date('Y-m-d H:i:s');
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
        $result = $this -> create($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $aid = $this -> aid;
        return $aid;
    }

    /**
     * 自定义的update事件
     * @param array $data
     * @return array
     */
    protected function before_update(array $data){
        $data['modify_by'] = $this -> _user['uid'];
        $data['modify_time'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * 更新记录
     * @param array $data
     * @param $aid
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $aid){
        $aid = intval($aid);
        if($aid <= 0 || !is_array($data) || count($data) == 0){
            throw new \Exception('参数错误');
        }
        $data = $this -> before_update($data);

        $this -> aid = $aid;
        $result = $this -> iupdate($data);
        if(!$result){
            throw new \Exception('更新失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }

    /**
     * 统计数量
     * @param int $status
     * @return mixed
     */
    public function get_count($status=1){
        $status = intval($status);
        $count = $this -> count(array(
            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => $status
            ),
        ));
        return $count;
    }
}