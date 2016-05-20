<?php

/**
 * Phalcon模型扩展
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Core;

class PhalBaseModel extends \Phalcon\Mvc\Model{

    /**
     * 数据库连接对象
     * @var \Phalcon\Db\Adapter\Pdo\Mysql
     */
    protected $db;

    public function initialize(){
        $this -> db = $this -> getDI() -> get('db');
    }

    /**
     * 设置表（补上表前缀）
     * @param string $tableName
     * @author Marser
     */
    public function set_table_source($tableName){
        $prefix = $this -> getDI() -> get('systemConfig') -> get('database', 'prefix');
        $this -> setSource($prefix.$tableName);
    }

}