<?php

/**
 * 文章模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Models;

use \Marser\App\Frontend\Models\BaseModel,
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
    public function get_list($page, $pagesize=10, array $ext=array())
    {
        $page = intval($page);
        $page <= 0 && $page = 1;
        $pagesize = intval($pagesize);
        ($pagesize <= 0 || $pagesize > 20) && $pagesize = 10;

        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('a' => __CLASS__));
        $builder->columns(array(
            'a.aid', 'a.title', 'a.introduce', 'a.status', 'a.view_number', 'a.create_time', 'a.modify_by', 'a.modify_time'
        ));
        $builder->where('a.status > :status:', array('status' => 0));
        if (isset($ext['cid']) && $ext['cid'] > 0) {
            $builder->addFrom(__NAMESPACE__ . '\\ArticlesCategorysModel', 'ac');
            $builder->andWhere("ac.cid = :cid:", array('cid' => $ext['cid']));
            $builder->andWhere("ac.aid = a.aid");
        }
        if(isset($ext['tid']) && $ext['tid'] > 0){
            $builder->addFrom(__NAMESPACE__ . '\\ArticlesTagsModel', 'at');
            $builder->andWhere("at.tid = :tid:", array('tid' => $ext['tid']));
            $builder->andWhere("at.aid = a.aid");
        }
        if (isset($ext['keyword']) && !empty($ext['keyword'])) {
            $builder->addFrom(__NAMESPACE__ . '\\ContentsModel', 'c');
            $builder->andWhere("a.title like :title: OR c.content like :content:", array(
                'title' => "%{$ext['keyword']}%",
                'content' => "%{$ext['keyword']}%",
            ));
            $builder->andWhere("a.aid = c.relateid");
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
     * 获取置顶文章
     * @param int $pagesize
     * @return \Phalcon\Mvc\ResultsetInterface
     * @throws \Exception
     */
    public function get_top_articles($pagesize=2){
        $pagesize = intval($pagesize);
        $pagesize <= 0 && $pagesize = 2;
        $result = $this -> find(array(
            'conditions' => 'is_top = :top: AND status = :status:',
            'bind' => array(
                'top' => 1,
                'status' => 1,
            ),
            'order' => 'create_time DESC',
            'limit' => $pagesize
        ));
        if(!$result){
            throw new \Exception('获取置顶文章失败');
        }
        return $result;
    }

    /**
     * 获取推荐阅读文章
     * @param int $pagesize
     * @return \Phalcon\Mvc\ResultsetInterface
     * @throws \Exception
     */
    public function get_recommend_articles($pagesize=10){
        $pagesize = intval($pagesize);
        $pagesize <= 0 && $pagesize = 10;
        $result = $this -> find(array(
            'conditions' => 'is_recommend = :recommend: AND status = :status:',
            'bind' => array(
                'recommend' => 1,
                'status' => 1,
            ),
            'order' => 'create_time DESC',
            'limit' => $pagesize
        ));
        if(!$result){
            throw new \Exception('获取推荐文章失败');
        }
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
            throw new \Exception('参数错误');
        }
        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('a' => __CLASS__));
        $builder->addFrom(__NAMESPACE__ . '\\ContentsModel', 'c');
        $builder->columns(array(
            'a.aid', 'a.title', 'a.status', 'a.view_number', 'a.create_time', 'a.modify_by', 'a.modify_time', 'c.content'
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
     * 获取前一篇文章
     * @param $aid
     * @return static
     * @throws \Exception
     */
    public function get_prev_article($aid){
        $aid = intval($aid);
        if($aid <= 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> find(array(
            'conditions' => 'aid < :aid:',
            'bind' => array(
                'aid' => $aid
            ),
            'order' => 'aid desc',
            'limit' => 1
        ));
        if(!$result){
            throw new \Exception('获取前一篇文章失败');
        }
        return $result;
    }

    /**
     * 获取后一篇文章
     * @param $aid
     * @return \Phalcon\Mvc\ResultsetInterface
     * @throws \Exception
     */
    public function get_next_article($aid){
        $aid = intval($aid);
        if($aid <= 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> find(array(
            'conditions' => 'aid > :aid:',
            'bind' => array(
                'aid' => $aid,
            ),
            'order' => 'aid asc',
            'limit' => 1
        ));
        if(!$result){
            throw new \Exception('获取前一篇文章失败');
        }
        return $result;
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

    /**
     * 更新浏览数
     * @param $aid
     * @return int
     * @throws \Exception
     */
    public function update_views($aid){
        $aid = intval($aid);
        if($aid <= 0){
            throw new \Exception('参数错误');
        }

        $phql = "UPDATE " . __CLASS__ . " SET view_number = view_number + 1 WHERE aid = :aid:";
        $result = $this -> getModelsManager() -> executeQuery($phql, array(
            'aid' => $aid
        ));
        if(!$result){
            throw new \Exception('更新失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }
}