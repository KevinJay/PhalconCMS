<?php

/**
 * 菜单模型
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Models;

use \Marser\App\Frontend\Models\BaseModel;

class MenuModel extends BaseModel{

    const TABLE_NAME = 'menu';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
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
}