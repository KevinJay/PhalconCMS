<?php

/**
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend;

use \Phalcon\Loader,
    \Phalcon\Mvc\View,
    \Phalcon\DiInterface,
    \Phalcon\Mvc\Dispatcher,
    \Phalcon\Mvc\ModuleDefinitionInterface;

class FrontendModule implements ModuleDefinitionInterface{

    public function registerAutoloaders(DiInterface $di=null){

    }

    public function registerServices(DiInterface $di){
        $systemConfig = $di -> get('systemConfig');

        /**
         * DI注册前台dispatcher
         */
        $di->set('dispatcher', function() use ($systemConfig) {
            $eventsManager = new \Phalcon\Events\Manager();
            $eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {
                if ($event->getType() == 'beforeException') {
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
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace($systemConfig -> get('app', 'frontend', 'controllers_namespace'));
            return $dispatcher;
        }, true);

        /**
         * DI注册前台view
         */
        $di -> set('view', function() use($systemConfig) {
            $view = new \Phalcon\Mvc\View();
            $view -> setViewsDir($systemConfig -> get('app', 'frontend', 'views'));
            $view -> registerEngines(array(
                '.phtml' => function($view, $di) use($systemConfig) {
                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                    $volt -> setOptions(array(
                        'compileAlways' => $systemConfig -> get('app', 'frontend', 'is_compiled'),
                        'compiledPath'  =>  $systemConfig -> get('app', 'frontend', 'compiled_path')
                    ));
                    return $volt;
                },
            ));
            return $view;
        });
    }
}