<?php

namespace marser\app\backend\controllers;

class IndexController extends \Phalcon\Mvc\Controller{

    public function testAction(){
        $this -> view -> pick('index/test');
    }

    public function notfoundAction(){
        echo 'backend 404';exit;
    }
}