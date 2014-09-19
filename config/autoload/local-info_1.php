<?php

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
return array(
    'bongo_server' => array(
        'wsdl' => array(
            'transaction' => 'http://api.bongous.com/',
            'api_transaction' => 'http://apitransaction.bongous.dev/',
        ),
        'pagos' => array(
            'adyen' => array(
                'host' => 'https://test.adyen.com/'),
            'ideal' => array(
                'wsdl' => 'https://pal-test.adyen.com/pal/servlet/soap/Ideal?wsdl',
                'location' => 'https://pal-test.adyen.com/pal/servlet/soap/Ideal',
                'login' => 'ws@Company.Bongo',
                'password' => 'laxnum21gs',
                'trace' => 1,
                'soap_version' => SOAP_1_1,
                'style' => SOAP_DOCUMENT,
                'encoding' => SOAP_LITERAL
            ),
            'maxmind' => array(
                'host' => 'http://minfraud2.maxmind.com/',
                'licence_key' => '59EY7QWKwJwN',
            ),
            'centinel' => array(
                'version' => '1.7',
                'processor_id' => '202',
                'merchant_id' => 'bongotest',
                'transaction_pwd' => '1234asdf',
                'maps_url' => 'https://centineltest.cardinalcommerce.com/MAPS/txns.asp',
                'term_url' => 'http://tools.bongous.dev/cardinelecommerce/samplecode/php/samples/3ds/ccAuthenticate.php',
                'notify_url' => 'http://tools.bongous.dev/cardinelecommerce/samplecode/php/samples/3ds/ccNotify.php',
                'timeout_connect' => '5000',
                'timeout_read' => '15000',
                'authentication_messaging' => 'For your security, please fill out the form below to complete your order.</b><br/>Do not click the refresh or back button or this transaction may be interrupted or cancelled.',
                'merchant_logo' => 'https://bongous.com/img/Bongo-Logo.png',
            ),
            'paypal' => array(
                'wsdl' => 'https://pilot-payflowpro.paypal.com',
                'partner' => 'pypal',
                'login' => 'bongo955',
                'password' => 'L4xb0nGo',
            ),
            'bitcoin' => array(
                'wsdl' => 'https://coinbase.com/api/v1/',
                'api_key' => 'HuJzTnidh1X7xnz3',
                'secret_key' => 'on25NhaSMTahheIitb53RFadUx02HXmE',
                'pay_detail' => 'Bongo Order - IdPartner:',
            ),
            'adyen_pay' => array(
                'wsdl' => 'https://pal-test.adyen.com/pal/Payment.wsdl',
                'location' => 'https://pal-test.adyen.com/pal/servlet/soap/Payment',
                'login' => 'ws@Company.Bongo',
                'password' => 'laxnum21gs',
                'trace' => 1,
                'soap_version' => SOAP_1_1,
                'style' => SOAP_DOCUMENT,
                'encoding' => SOAP_LITERAL,
                'demo' => array(
                    '3d' => array(
                        'card_number' => '4212345678901237',
                        'card_cvv2' => '737',
                        'card_expire_month' => '06',
                        'card_expire_year' => '2016'),
                    'default' => array(
                        'card_number' => '4444333322221111',
                        'card_cvv2' => '737',
                        'card_expire_month' => '06',
                        'card_expire_year' => '2016'),                    
                )
            ),
        ),
        'uri_checkout_invalit' => 'http://bongoweb.dev/',
        'proyecto' => 'BongoTool',
        'script_verion' => '1.0.0.1',
        'server' => array(
            'static' => 'http://ztransaction.bongous.dev/',
            'content' => 'http://ztransaction.bongous.dev/',
            'element' => 'http://ztransaction.bongous.dev/',
        ),
        'email_development' => array(
            'likerow@gmail.com',
        ),        
    ),
    'db' => array(
        //this is for primary adapter....
        'username' => 'jared.cusi',
        'password' => 'CruJe4ed',
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=bongo;host=10.50.1.8',
        'profiler' => true,
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'adapters' => array(
            'dbsession' => array(
                'username' => 'jared.cusi',
                'password' => 'CruJe4ed',
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=bongo;host=10.50.1.8',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'db1' => array(
                'username' => 'root',
                'password' => '',
            ),
            'db2' => array(
                'username' => 'other_user',
                'password' => 'other_user_passwd',
            ),
        ),
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
    'module_layouts' => array(
        'Auth' => 'layout/layouta',
    ),
);
