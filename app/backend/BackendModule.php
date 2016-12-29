<?php

/**
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend;

use \Phalcon\Loader,
    \Phalcon\Mvc\View,
    \Phalcon\DiInterface,
    \Phalcon\Mvc\Dispatcher,
    \Phalcon\Mvc\ModuleDefinitionInterface;

class BackendModule implements ModuleDefinitionInterface{

    public function registerAutoloaders(DiInterface $di=null){

    }

    /**
     * DI注册相关服务
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di){
        /** DI注册dispatcher服务 */
        $this -> registerDispatcherService($di);
        /** DI注册url服务 */
        $this -> registerUrlService($di);
        /** DI注册view服务 */
        $this -> registerViewService($di);
    }

    /**
     * DI注册dispatcher服务
     * @param DiInterface $di
     */
    protected function registerDispatcherService(DiInterface $di){
        $systemConfig = $di -> get('systemConfig');
        $di->set('dispatcher', function() use ($systemConfig) {
            $eventsManager = new \Phalcon\Events\Manager();
            $eventsManager -> attach("dispatch:beforeException", function($event, $dispatcher, $exception) {
                if ($event -> getType() == 'beforeException') {
                    switch ($exception->getCode()) {
                        case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            $dispatcher->forward(array(
                                'controller' => 'Index',
                                'action' => 'notfound'
                            ));
                            return false;
                        case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'controller' => 'Index',
                                'action' => 'notfound'
                            ));
                            return false;
                    }
                }
            });
            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher -> setEventsManager($eventsManager);
            //默认设置为后台的调度器
            $dispatcher -> setDefaultNamespace($systemConfig -> app -> root_namespace . '\\App\\Backend\\Controllers');
            return $dispatcher;
        }, true);
    }

    /**
     * DI注册url服务
     * @param DiInterface $di
     */
    protected function registerUrlService(DiInterface $di){
        $systemConfig = $di -> get('systemConfig');
        $di -> setShared('url', function() use($systemConfig){
            $url = new \Phalcon\Mvc\Url();
            $url -> setBaseUri($systemConfig -> app -> backend -> module_pathinfo);
            $url -> setStaticBaseUri($systemConfig -> app -> backend -> assets_url);
            return $url;
        });
    }

    /**
     * DI注册view服务
     * @param DiInterface $di
     */
    protected function registerViewService(DiInterface $di){
        $systemConfig = $di -> get('systemConfig');
        $di -> setShared('view', function() use($systemConfig) {
            $view = new \Phalcon\Mvc\View();
            $view -> setViewsDir($systemConfig -> app -> backend -> views);
            $view -> registerEngines(array(
                '.phtml' => function($view, $di) use($systemConfig) {
                    $volt = new \Marser\App\Core\PhalBaseVolt($view, $di);
                    $volt -> setOptions(array(
                        'compileAlways' => $systemConfig -> app -> backend -> is_compiled,
                        'compiledPath'  =>  $systemConfig -> app -> backend -> compiled_path
                    ));
                    $volt -> initFunction();
                    return $volt;
                },
            ));
            return $view;
        });
    }
}