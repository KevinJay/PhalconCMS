<?php

/**
 * 标签模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Models;

use \Marser\App\Frontend\Models\BaseModel;

class TagsModel extends BaseModel{

    const TABLE_NAME = 'tags';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 标签列表
     * @param array $ext
     * @return array
     * @throws \Exception
     */
    public function get_list(array $ext=array()){
        $result = $this -> find(array(
            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => 1
            )
        ));
        if(!$result){
            throw new \Exception('查询数据失败');
        }
        $tagsList = $result -> toArray();
        return $tagsList;
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
            )
        ));
        return $count;
    }

    /**
     * 根据缩略名获取标签数据
     * @param $slug
     * @return static
     * @throws \Exception
     */
    public function get_tag_by_slug($slug){
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
            throw new \Exception('获取标签数据失败');
        }
        return $result;
    }
}