<?php

/**
 * 业务仓库基类
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Repositories;

use \Phalcon\Di,
    \Phalcon\DiInterface,
    \Marser\App\Backend\Models\ModelFactory;

class BaseRepository {

    /**
     * DI容器
     * @var \Phalcon\Di
     */
    private $_di;

    public function __construct(DiInterface $di = null){
        $this -> setDI($di);
    }

    /**
     * 设置DI容器
     * @param DiInterface|null $di
     */
    public function setDI(DiInterface $di = null){
        empty($di) && $di = Di::getDefault();
        $this -> _di = $di;
    }

    /**
     * 获取DI容器
     * @return Di
     */
    public function getDI(){
        return $this -> _di;
    }

    /**
     * 获取模型对象
     * @param $modelName
     * @return mixed
     * @throws \Exception
     */
    protected function get_model($modelName){
        return ModelFactory::get_model($modelName);
    }
}
