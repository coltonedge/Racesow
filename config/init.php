<?php

// Autoloader
function __autoload($class)
{
    $include = str_replace('_', DS, $class) . '.php';
    require($include);
}

// Server type
if (isset($_SERVER) && isset($_SERVER['SERVER_TYPE'])) {
    define('SERVER_TYPE', $_SERVER['SERVER_TYPE']);
}

if (!defined('SERVER_TYPE')) {
    define('SERVER_TYPE', 'production');
}

// Paths
define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', dirname(dirname(__FILE__)));
define('PATH_CONFIG', PATH_ROOT . DS . 'config');
define('PATH_CACHE', PATH_ROOT . DS . 'cache');
define('PATH_LIBRARY', PATH_ROOT . DS . 'library');
define('PATH_HTDOCS', PATH_ROOT . DS . 'htdocs');

// Set include Paths 
ini_set('include_path',
    PATH_LIBRARY . PATH_SEPARATOR .
    PATH_ROOT . DS . 'application' . PATH_SEPARATOR .
    PATH_ROOT . DS . 'application' . DS . 'models' . PATH_SEPARATOR .
    PATH_ROOT . DS . 'application' . DS . 'models' . DS . 'generated' . PATH_SEPARATOR .
    ini_get('include_path')
);

// Cached config
$cfgCache = Zend_Cache::factory( 'File', 'File',
    array('master_file' => PATH_CONFIG . DS . 'config.ini', 'automatic_serialization' => true),
    array('cache_dir' => PATH_CACHE)
);

if (!($config = $cfgCache->load('config'))) {

    $config = new Zend_Config_Ini(PATH_CONFIG . DS . 'config.ini', SERVER_TYPE, true);
    $cfgCache->save($config);
}

Zend_Registry::set('config', $config);

// Doctrine database connection
@include('Doctrine.compiled.php'); // If exists, then include it
$connection = Doctrine_Manager::connection($config->database->doctrine->dsn); 
$connection->setCharset('UTF8');
Zend_Registry::set('doctrine', $connection); // make the conenction available for special purposes