<?php
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('APPLICATION_LIB')
    || define('APPLICATION_LIB',
              dirname(getenv('ZF2_PATH')));
// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

date_default_timezone_set('America/Lima');

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
