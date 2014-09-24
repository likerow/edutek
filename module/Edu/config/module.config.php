<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Edu\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            /*'RoutePending' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:pay[/:alias]/pending[/:idPay][/:s][/]',
                    'constraints' => array(
                        'alias' => '[a-zA-Z0-9_-]*',
                        'idPay' => '[a-zA-Z0-9_-]*',
                        's' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Response',
                        'action' => 'pending',
                        //'locale' => 'us',
                    ),
                ),
            ),
            'RouteBlackList' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:pay[/:alias]/blackList[/:idPay][/:s][/]',
                    'constraints' => array(
                        'alias' => '[a-zA-Z0-9_-]*',
                        'idPay' => '[a-zA-Z0-9_-]*',
                        's' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Response',
                        'action' => 'order-black-list',
                        //'locale' => 'us',
                    ),
                ),
            ),
            'RouteOrderComplete' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:pay[/:alias]/orderComplete[/:idPay][/:s][/]',
                    'constraints' => array(
                        'alias' => '[a-zA-Z0-9_-]*',
                        'idPay' => '[a-zA-Z0-9_-]*',
                        's' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Response',
                        'action' => 'order-complete',
                        //'locale' => 'us',
                    ),
                ),
            ),
            
            'RouteOrderFail' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:pay[/:alias]/orderFail[/:idPay][/:s][/]',
                    'constraints' => array(
                        'alias' => '[a-zA-Z0-9_-]*',
                        'idPay' => '[a-zA-Z0-9_-]*',
                        's' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Response',
                        'action' => 'order-fail',
                        //'locale' => 'us',
                    ),
                ),
            ),
            
            'RouteRegexIndex' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '/pay/(.*)/index.php',
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Index',
                        'action' => 'index',
                        //'locale' => 'us',
                    ),
                    'spec' => '/pay/%alias%/%sessionid%',
                ),
            ),
            'RouteRegexVerificard' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '/pay/(.*)/verifyCard.php',
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Response',
                        'action' => 'verify-card',
                        //'locale' => 'us',
                    ),
                    'spec' => '/pay/%alias%/%sessionids%',
                ),
            ),
            'sessionLocale' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:pay[/:alias][/:sessionid][/:locale][/]',
                    'constraints' => array(
                        'alias' => '[a-zA-Z0-9_-]*',
                        'sessionid' => '[a-zA-Z0-9_-]*',
                        'locale' => '[a-z]{2}(-[A-Z]{2}){0,1}',
                    ),
                    'defaults' => array(
                        'controller' => 'Checkout\Controller\Index',
                        'action' => 'index',
                        //'locale' => 'us',
                    ),
                ),
            ),*/
           
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'checkout_route' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/checkout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Checkout\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en',
        'translation_file_patterns' => array(
            array(
                'type' => 'phparray',
                'base_dir' => dirname(__DIR__) . '/language/checkout',
                'pattern' => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Edu\Controller\Index' => 'Edu\Controller\IndexController',
            'Edu\Controller\Chat' => 'Edu\Controller\ChatController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/heada' => __DIR__ . '/../view/layout/head.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            __DIR__ . '/../../../public/static/partners',
        ),
    ),
);