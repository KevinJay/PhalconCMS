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

class OptionsModel extends BaseModel{

    const TABLE_NAME = 'options';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 更新配置项
     * @param array $data
     * @param $opkey
     * @return int
     * @throws \Exception
     */
    public function update_options(array $data, $opkey){
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
                'conditions' => 'op_key = ?',
                'bind' => array($opkey)
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
