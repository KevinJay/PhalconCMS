<?php

/**
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Controllers;

class IndexController extends \Phalcon\Mvc\Controller{

    public function indexAction(){
        $this->view->setVars(
            array(
                'title'         =>  'fronted_title',
                'content'       =>  'fronted_content',
                'description'   =>  'description',
                'my_team'       =>  'zwz,www.marser.cn',
                'team_desc'     =>  'fuck fuck fuck',
                'classfy'       =>  array(
                    'one'   =>  123,
                    'two'   =>  456,
                    'three' =>  789
                ),
            )
        );
    }

    public function testAction(){
        $this -> view -> title = 'index/test';
        $this -> view -> description = 'fuck fuck fuck ';
        $this -> view -> pick('index/test');
    }
}