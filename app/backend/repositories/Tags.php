<?php

/**
 * 标签业务仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Repositories;

use \Marser\App\Backend\Repositories\BaseRepository;

class Tags extends BaseRepository{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 标签列表
     * @param int $status
     * @param array $ext
     * @return array
     * @throws \Exception
     */
    public function get_list(){
        $tagsList = $this -> get_model('TagsModel') -> get_list();
        return $tagsList;
    }

    /**
     * 统计数量
     * @return mixed
     */
    public function get_count(){
        $count = $this -> get_model('TagsModel') -> get_count();
        return $count;
    }

    /**
     * 获取标签数据
     * @param $tid
     * @return array
     * @throws \Exception
     */
    public function detail($tid){
        $tag = $this -> get_model('TagsModel') -> detail($tid);
        return $tag;
    }

    /**
     * 保存标签
     * @param array $data
     * @param $tid
     * @return bool|int
     */
    public function save(array $data, $tid){
        $tid = intval($tid);
        if($tid <= 0){
            /** 添加标签 */
            $this -> create($data);
        }else{
            /** 更新标签 */
            $this -> update($data, $tid);
        }
    }

    /**
     * 删除标签（软删除）
     * @param $tid
     * @return mixed
     * @throws \Exception
     */
    public function delete($tid){
        $tid = intval($tid);
        if($tid <= 0){
            throw new \Exception('请选择需要删除的标签');
        }
        $affectedRows = $this -> get_model('TagsModel') -> update_record(array(
            'status' => 0
        ), $tid);
        if($affectedRows <= 0){
            throw new \Exception('删除标签失败');
        }
        return $affectedRows;
    }

    /**
     * 标签数据入库
     * @param array $data
     * @return int
     * @throws \Exception
     */
    protected function create(array $data){
        /** 判断标签是否已存在 */
        $isExist = $this -> get_model('TagsModel') -> tag_is_exist($data['tag_name'], $data['slug']);
        if($isExist && $isExist -> count() > 0){
            throw new \Exception('标签名称或缩略名已存在');
        }
        /** 添加标签 */
        $tid = $this -> get_model('TagsModel') -> insert_record($data);
        $tid = intval($tid);
        if($tid <= 0){
            throw new \Exception('标签数据入库失败');
        }
        return $tid;
    }

    /**
     * 更新标签数据
     * @param array $data
     * @param $tid
     * @return mixed
     * @throws \Exception
     */
    protected function update(array $data, $tid){
        /** 判断标签是否已存在 */
        $isExist = $this -> get_model('TagsModel') -> tag_is_exist($data['tag_name'], $data['slug'], $tid);
        if($isExist && $isExist -> count() > 0){
            throw new \Exception('标签名称或缩略名已存在');
        }
        /** 更新标签 */
        $affectedRows = $this -> get_model('TagsModel') -> update_record($data, $tid);
        if($affectedRows <= 0){
            throw new \Exception('更新标签失败');
        }
        return $affectedRows;
    }

}