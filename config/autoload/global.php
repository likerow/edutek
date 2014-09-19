<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'bongo_server' => array(
        'proyecto' => 'BongoTool',
        'script_verion' => '1.0.0.1',
        'email_development' => array(
            'likerow@gmail.com'
        ),
        'session' => array(
            'sessionConfig' => array(
                'cache_expire' => 86400,
                'cookie_domain' => 'bongo1.com',
                'name' => 'BONGOUS_SESSID_CHECKOUT',
                'cookie_lifetime' => 190000,
                'gc_maxlifetime' => 190000,
                'cookie_path' => '/',
                'cookie_secure' => False,
                'remember_me_seconds' => 3600,
                'use_cookies' => true,
            )
        ),       
        'logger' => array(
            'writers' => array(
                'stream' => array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => 'data/log/application.log'
                    ),
                    'filters' => array(
                        'priority' => 7
                    ),
                    'formatter' => array(
                        'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                        'dateTimeFormat' => 'Y-m-d H:i:s'
                    )
                )
            )
        ),
    ),
    'module_layouts' => array(
        'Checkout' => 'layout/layout',
    ),
    'locales' => array(
        'default' => array('code' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : 'en'),
        'list' => array(
            'en' => array('code' => 'en', 'name' => 'United Kingdom / English', 'index' => 0),
            'ar' => array('code' => 'ar', 'name' => 'Lebanon/ Arabe', 'index' => 1),
            'ru' => array('code' => 'ru', 'name' => 'Russia / Russian', 'index' => 2),
            'de' => array('code' => 'de', 'name' => 'Deutschland / Deutsch', 'index' => 3),
            'es' => array('code' => 'es', 'name' => 'Espa&ntilde;a / Espa&ntilde;ol', 'index' => 4),
            'fr' => array('code' => 'fr', 'name' => 'France / Fran&ccedil;ais', 'index' => 5),
            'it' => array('code' => 'it', 'name' => 'Italia / Italiano', 'index' => 6),
            'pt' => array('code' => 'pt', 'name' => 'Portugal / Portugu&ecirc;s', 'index' => 7),
            'zh' => array('code' => 'zh', 'name' => 'China / Chinese', 'index' => 8),
        )
    ),
    'service_manager' => array(
        // for primary db adapter that called
        // by $sm->get('Zend\Db\Adapter\Adapter')
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        // to allow other adapter to be called by
        // $sm->get('db1') or $sm->get('db2') based on the adapters config.
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
);
