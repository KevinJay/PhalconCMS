<?php

/**
 *
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;
use \Marser\App\Backend\Models\BaseModel;

class UsersModel extends BaseModel{

    const TABLE_NAME = 'users';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 获取用户详细数据
     * @param $username
     * @param array $ext
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function user_detail($username, array $ext=array()){
        if(empty($username)){
            throw new \Exception('参数错误');
        }
        $params = array(
            'conditions' => 'username=:username:',
            'bind' => [
                'username' => $username,
            ],
        );
        if(isset($ext['columns']) && !empty($ext['columns'])){
            $params['columns'] = $ext['columns'];
        }
        $result = $this -> findFirst($params);
        return $result;
    }

    /**
     * 更新个人设置数据
     * @param array $data
     * @param $username
     * @return int
     * @throws \Exception
     */
    public function update_user(array $data, $username){
        $data = array_filter($data);
        $data = array_unique($data);
        if(!is_array($data) || count($data) == 0){
            throw new \Exception('参数错误');
        }
        $keys = array_keys($data);
        $values = array_values($data);
        $result = $this -> db -> update(
            $this->getSource(),
            $keys,
            $values,
            array(
                'conditions' => 'username = ?',
                'bind' => array($username)
                //'bindTypes' => array(\PDO::PARAM_STR)
            )
        );
        if(!$result){
            throw new \Exception('更新失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }

}