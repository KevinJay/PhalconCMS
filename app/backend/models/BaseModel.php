<?php

/**
 * 基类model
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Models;

use \Marser\App\Core\PhalBaseModel;

class BaseModel extends PhalBaseModel{

    /**
     * 用户session
     */
    protected $_user;

    public function initialize(){
        parent::initialize();
        $this->_user = $this->getDI()->get('session')->get('user');
    }
}
