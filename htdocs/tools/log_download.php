<?php

// determine location of config
$cfgPath = '';
$num = count( explode( '/', $_SERVER['SCRIPT_NAME'] ) );
for( $n = 0; $n < $num-1; $n++)
	$cfgPath .= '../';
define('CONFIG_PATH', $cfgPath .'config.ini');
require_once $cfgPath .'config.php';
$config = Zend_Registry::get('config');

// get connection from Zend/Racenet
$dbhost		= $config->database->params->host;
$dbname		= $config->database->params->dbname;
$dbuser		= $config->database->params->username;
$dbpasswd	= $config->database->params->password;

// Database
Zend_Loader::loadClass( 'Zend_Db' );
$db = Zend_Db::factory($config->database);
$db->query("INSERT INTO log_download ( map_id, created ) VALUES( ". intval( $_GET['id'] ) .", NOW() )");

echo 1;
