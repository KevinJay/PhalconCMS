<?php

namespace marser\app\frontend\controllers;

class IndexController extends \Phalcon\Mvc\Controller{

    public function testAction(){
        $this -> view -> pick('index/test');
    }

    public function notfoundAction(){
        echo 'frontend - 404';
    }
}