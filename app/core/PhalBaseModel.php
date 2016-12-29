<?php

/**
 * Phalcon模型扩展
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Core;

class PhalBaseModel extends \Phalcon\Mvc\Model implements \Phalcon\Mvc\ModelInterface{

    /**
     * 数据库连接对象
     * @var \Phalcon\Db\Adapter\Pdo\Mysql
     */
    protected $db;

    public function initialize(){
        $this -> db = $this -> getDI() -> get('db');

        /** 不对空字段进行validation校验 */
        self::setup(array(
            'notNullValidations' => false
        ));
    }

    /**
     * 设置表（补上表前缀）
     * @param string $tableName
     * @author Marser
     */
    protected function set_table_source($tableName){
        $prefix = $this -> getDI() -> get('systemConfig') -> database -> prefix;
        $this -> setSource($prefix.$tableName);
    }

    /**
     * 封装phalcon model的update方法，实现仅更新数据变更字段，而非所有字段更新
     * @param array|null $data
     * @param null $whiteList
     * @return bool
     */
    public function iupdate(array $data=null, $whiteList=null){
        if(count($data) > 0){
            $attributes = $this -> getModelsMetaData() -> getAttributes($this);
            $this -> skipAttributesOnUpdate(array_diff($attributes, array_keys($data)));
        }
        return parent::update($data, $whiteList);
    }
}