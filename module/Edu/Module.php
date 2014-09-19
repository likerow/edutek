<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Edu;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Bongo\Model;
use Zend\Mvc\ModuleRouteListener;
use Checkout;

class Module {

    public function onBootstrap(EventInterface $e) {
        $application = $e->getTarget();
        $eventManager = $application->getEventManager();
        $services = $application->getServiceManager();
        $services->get('Server');
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);

        $eventManager->attach('route', function ($event) {
            $sm = $event->getApplication()->getServiceManager();
            $config = $event->getApplication()->getServiceManager()->get('Config');
            $localesConfig = $config['locales'];
            $locales = $localesConfig['list'];
            $locale = $event->getRouteMatch()->getParam('locale', null);
            if (empty($locale)) {
                $locale = isset($_COOKIE['locale']) ? $_COOKIE['locale'] : $localesConfig['default']['code'];
            }
            if (in_array($locale, array_keys($locales))) {
                $locale = $locales[$locale]['code'];
            } else {
                $locale = $localesConfig['default']['code'];
            }
            $translator = $sm->get('translator');
            $translator->setLocale($locale);
            $httpServer = "";
            if (isset($_SERVER['HTTP_HOST'])) {
                $httpServer = $_SERVER['HTTP_HOST'];
            }
            setcookie('locale', $locale, time() + 3600, '/', $httpServer);
        }, -10);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach('dispatch.error', function ($event) use($services) {
            $event->getResult()->setTerminal(TRUE);
            $exception = $event->getResult()->exception;
            $error = $event->getError();
            if (!$exception && !$error) {
                return;
            }
            $service = $services->get('Likerow\ErrorHandler');
            if ($exception) {
                $service->logException($exception, $services->get('Mail'));
            }

            if ($error) {
                $service->logError('Dispatch ERROR: ' . $error, $services->get('Mail'));
            }
        });

        $storage = $e->getApplication()->getServiceManager()->get('Likerow\Storage\DBStorage');
        $storage->setSessionStorage('checkout');
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        $services = new \Likerow\ServiceManager\ServiceManagerConfig();
        $services->setService(array(
            'Checkout\Model\CheckoutDb' => function($sm) {
        return new \Checkout\Model\CheckoutDb($sm->get('Zend\Db\Adapter\Adapter'), $sm);
    }, 'Bongo\Model\BpOrder' => function($sm) {
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        return new Model\BpOrder($adapter, $sm);
    }));

        return $services;
    }

}

function _dump($value) {
    error_log(print_r($value, true));
}
