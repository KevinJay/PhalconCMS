<?php

/**
 * Phalcon模型扩展
 *
 */

namespace marser\app\core;

class PhalBaseModel extends \Phalcon\Mvc\Model{

    public function onConstruct(){

    }

    /**
     * 设置表（补上表前缀）
     * @param string $tableName
     */
    public function setTableSource($tableName){
        $prefix = $this -> systemConfig -> get('database', 'prefix');
        $this->setSource($prefix.$tableName);
    }
}