<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

///////////////////////////////////////////////////////////////////////////////
// zolex racenet
require dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require 'Racenet/Phpbb.php';
// !zolex racenet
///////////////////////////////////////////////////////////////////////////////


// phpBB 2.x auto-generated config file
// Do not change anything in this file!

$dbms = 'mysql';
$table_prefix = 'phpbb_';
define('PHPBB_INSTALLED', true);

?>