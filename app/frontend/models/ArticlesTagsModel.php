<?php

/**
 * 文章-标签关联模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Models;

use \Marser\App\Frontend\Models\BaseModel,
    \Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class ArticlesTagsModel extends BaseModel{

    const TABLE_NAME = 'articles_tags';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
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

    /**
     * 根据标签获取猜你喜欢的文章
     * @param array $tids
     * @param int $aid
     * @param int $pagesize
     * @return mixed
     * @throws \Exception
     */
    public function guess_you_like(array $tids, $aid, $pagesize=10){
        $pagesize = intval($pagesize);
        $pagesize <= 0 && $pagesize = 10;
        if(count($tids) == 0){
            throw new \Exception('参数错误');
        }
        $builder = $this->getModelsManager()->createBuilder();
        $builder->from(array('ats' => __CLASS__));
        $builder->addFrom(__NAMESPACE__.'\\ArticlesModel', 'a');
        $builder->columns(array(
            'a.aid', 'a.title'
        ));
        $result = $builder->where('ats.tid IN ({tid:array})', array('tid'=>$tids))
            ->andWhere('ats.aid != :aid:', array('aid' => $aid))
            ->andWhere('ats.aid = a.aid')
            ->andWhere('a.status = :status:', array('status' => 1))
            ->orderBy("a.view_number ASC")
            ->groupBy("a.aid")
            ->limit($pagesize)
            ->getQuery()
            ->execute();
        if(!$result){
            throw new \Exception('获取数据失败');
        }
        return $result;
    }
}
