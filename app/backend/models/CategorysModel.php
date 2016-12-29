<?php

/**
 * 分类模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Backend\Models\BaseModel;

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
     * 	Is executed before the fields are validated for not nulls/empty strings
     *  or foreign keys when an insertion operation is being made
     */
    public function beforeValidationOnCreate(){
        if($this -> sort <= 0 || $this -> sort > 999){
            $this -> sort = 999;
        }
        $this -> create_by = $this->_user['uid'];
        $this -> create_time = date('Y-m-d H:i:s');
        $this -> modify_by = $this->_user['uid'];
        $this -> modify_time = date('Y-m-d H:i:s');
    }

    /**
     * 分类数据入库
     * @param array $data
     * @return bool|int
     * @throws \Exception
     */
    public function insert_record(array $data){
        if(count($data) == 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> create($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $cid = $this -> cid;
        return $cid;
    }

    /**
     * 自定义的update事件
     * @param array $data
     * @return array
     */
    protected function before_update(array $data){
        if(isset($data['sort']) && ($data['sort'] <= 0 || $data['sort'] > 999)){
            $data['sort'] = 999;
        }
        $data['modify_by'] = $this->_user['uid'];
        $data['modify_time'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * 更新分类数据
     * @param array $data
     * @param $cid
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $cid){
        $cid = intval($cid);
        $data = $this -> before_update($data);
        if(count($data) == 0 || $cid <= 0){
            throw new \Exception('参数错误');
        }

        $this -> cid = $cid;
        $result = $this -> iupdate($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }

    /**
     * 更新分类路径（使用的原生PDO处理，所以占位符与phalcon封装的占位符不一致，请注意）
     * @param $newPath
     * @param $oldPath
     * @return int
     */
    public function update_path($newPath, $oldPath){
        if(empty($newPath) || empty($oldPath)){
            throw new \Exception('参数错误');
        }
        $sql = "UPDATE " . $this -> getSource() . " SET path=REPLACE(path, :oldPath, :newPath) ";
        $sql .= ' WHERE `path` like :path AND `status` = :status ';
        $stmt = $this -> db -> prepare($sql);
        $bind = array(
            'oldPath' => "{$oldPath}",
            'newPath' => "{$newPath}",
            'path' => "{$oldPath}%",
            'status' => 1
        );
        $result = $stmt -> execute($bind);
        if(!$result){
            throw new \Exception('更新分类路径失败');
        }
        $affectedRows = $stmt -> rowCount();
        return $affectedRows;
    }

    /**
     * 更新parent_cid
     * @param $newParentcid
     * @param $oldParentcid
     * @return int
     * @throws \Exception
     */
    public function update_parentcid($newParentcid, $oldParentcid){
        $newParentcid = intval($newParentcid);
        $oldParentcid = intval($oldParentcid);
        if($oldParentcid <= 0){
            throw new \Exception('参数错误');
        }

        $result = $this -> db -> update(
            $this -> getSource(),
            array('parent_cid'),
            array($newParentcid),
            array(
                'conditions' => 'parent_cid = ? AND `status` = ? ',
                'bind' => array($oldParentcid, 1)
            )
        );
        if(!$result){
            throw new \Exception('更新父分类ID失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
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

        $result = $this -> findFirst(array(
            'conditions' => 'cid = :cid:',
            'bind' => array(
                'cid' => $cid
            )
        ));
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
     * 根据category_name或slug判断分类是否存在
     * @param null $categoryName ($categoryName与$slug两者传一即可)
     * @param null $slug
     * @param null $cid
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function category_is_exist($categoryName=null, $slug=null, $cid=null){
        if(empty($categoryName) && empty($slug)){
            throw new \Exception('参数错误');
        }
        $params = array();
        if(!empty($categoryName) && !empty($slug)){
            $params['conditions'] = " (category_name = :categoryName: OR slug = :slug:) AND status = 1 ";
            $params['bind']['categoryName'] = $categoryName;
            $params['bind']['slug'] = $slug;
        }else if(!empty($categoryName)){
            $params['conditions'] = " category_name = :categoryName: AND status = 1 ";
            $params['bind']['categoryName'] = $categoryName;
        }else if(!empty($slug)){
            $params['conditions'] = " slug = :slug: AND status = 1 ";
            $params['bind']['slug'] = $slug;
        }
        $cid = intval($cid);
        $cid > 0 && $params['conditions'] .= " AND cid != {$cid} ";

        $result = $this -> find($params);
        return $result;
    }

}
