<?php

require_once dirname(dirname(__FILE__)) . '/config/init.php';
$pdo = Zend_Registry::get('doctrine')->getDbh();

$serverConf = new Zend_Config_Ini('/home/racesow/servers.ini');
$confPath = '/home/racesow/warsow-0.6/racesow/cfgs/';

foreach ($serverConf as $server) {

	if ($server->enabled && !$server->web) {
	
		$query = "SELECT `name`
				FROM `map`
				WHERE `status` = 'enabled'
				AND (`freestyle` = '". ($server->gametype == 'freestyle' ? 'true' : 'false') ."'
				OR `freestyle` = '". ($server->gametype == 'freestyle' ? '1' : '0') ."')
				ORDER BY RAND()
				LIMIT 1;";
		$stmt = $pdo->query($query);
		$map = $stmt->fetchColumn();
	
		$filename = $confPath . 'port_' . $server->port . '_defaultmap.cfg';
		
		$handle = fopen($filename, "w");
		fwrite($handle, "set sv_defaultmap $map");
		fclose($handle);
	}
}