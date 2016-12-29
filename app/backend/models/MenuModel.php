<?php

/**
 * 菜单模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Backend\Models\BaseModel;

class MenuModel extends BaseModel{

    const TABLE_NAME = 'menu';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
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
     * 添加菜单数据
     * @param array $data
     * @return \Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function insert_record(array $data){
        if(!is_array($data) || count($data)==0){
            throw new \Exception('参数错误');
        }
        $result = $this -> create($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $mid = $this -> mid;
        return $mid;
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
        $data['modify_by'] = $this -> _user['uid'];
        $data['modify_time'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * 更新菜单数据
     * @param array $data
     * @param $mid
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $mid){
        $mid = intval($mid);
        if(count($data) == 0 || $mid <= 0){
            throw new \Exception('参数错误');
        }
        $data = $this -> before_update($data);

        $this -> mid = $mid;
        $result = $this -> iupdate($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $affectedRows = $this -> db -> affectedRows();
        $affectedRows = intval($affectedRows);
        return $affectedRows;
    }

    /**
     * 获取菜单数据
     * @param $mid
     * @return array
     * @throws \Exception
     */
    public function detail($mid){
        $mid = intval($mid);
        if($mid <= 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> findFirst(array(
            'conditions' => 'mid = :mid: AND status = :status:',
            'bind' => array(
                'mid' => $mid,
                'status' => 1
            ),
        ));
        $menu = array();
        if($result){
            $menu = $result -> toArray();
        }
        return $menu;
    }

    /**
     * 获取菜单树
     * @param int $status
     * @return array
     */
    public function get_menu_for_tree($status=1){
        $menuList = array();
        $status = intval($status);
        $result = $this -> find(array(
            'columns' => 'mid, menu_name, menu_url, parent_mid, path, sort, modify_time',
            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => $status,
            ),
            'order' => 'LENGTH(path) DESC, parent_mid DESC, sort asc',
        ));
        if($result){
            $menuList = $result -> toArray();
        }
        return $menuList;
    }

    /**
     * 更新菜单路径（使用的原生PDO处理，所以占位符与phalcon封装的占位符不一致，请注意）
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
            throw new \Exception('更新菜单路径失败');
        }
        $affectedRows = $stmt -> rowCount();
        return $affectedRows;
    }

    /**
     * 更新parent_mid
     * @param $newParentmid
     * @param $oldParentmid
     * @return int
     * @throws \Exception
     */
    public function update_parentmid($newParentmid, $oldParentmid){
        $newParentmid = intval($newParentmid);
        $oldParentmid = intval($oldParentmid);
        if($oldParentmid <= 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> db -> update(
            $this -> getSource(),
            array('parent_mid'),
            array($newParentmid),
            array(
                'conditions' => 'parent_mid = ? AND `status` = ? ',
                'bind' => array($oldParentmid, 1)
            )
        );
        if(!$result){
            throw new \Exception('更新父菜单ID失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }
}