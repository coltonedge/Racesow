<?php

error_reporting(E_ALL);

require('SAM/php_sam.php');
require('Racenet/IngameColors.php');

$dbHost = 'localhost';
$dbName = 'warsow_racenet';
$dbUser = 'root';
$dbPass = 'deluxe85*!';
$dbOptions = array(PDO::ATTR_PERSISTENT => true);
$dbh = new PDO('mysql:host='. $dbHost . ';dbname='. $dbName, $dbUser, $dbPass, $dbOptions);
$stmt = $dbh->prepare('SELECT u.user_id AS id, u.username, p.id AS player_id, p.name, p.simplified FROM phpbb_users u LEFT JOIN player_phpbbuser pu ON pu.user_id = u.user_id LEFT JOIN player p ON p.id = pu.player_id WHERE u.user_id = :user_id');
$connectedUsers = array();

if (!$handle = popen('mosquitto 2>&1', 'r')) {

	die('could not start mosquitto');
}

function usersToXML($users) {

	$xml = '<?xml version="1.0"?><userlist>';
	foreach($users as $user) {
	
		$xml .= '<user>' .
			'<id><![CDATA['. $user->id .']]></id>' .
			'<username><![CDATA['. $user->username .']]></username>' .
			'<player_id><![CDATA['. $user->player_id .']]></player_id>' .
			'<name><![CDATA['. new Racenet_IngameColors($user->name, null, true) .']]></name>' .
			'<simplified><![CDATA['. $user->simplified .']]></simplified>' .
			'</user>';
	}
	
	$xml .= '</userlist>';
	return $xml;
}

function updateBroadcast($users) {

	sleep(1);
	ob_start();
	$conn = new SAMConnection();
	$conn->Connect(SAM_MQTT, array(
		SAM_HOST => '127.0.0.1',
		SAM_PORT => 1883
	));
	 
	 $conn->Send('topic://broadcast', (object)array('body' => usersToXML($users)));
	 $conn->Disconnect();
	 ob_end_clean();
}

while($line = fread($handle, 2096)) {

	echo $line;
	if (preg_match('/New client connected from .+ as user_(\d+)./', $line, $regs)) {

		$stmt->bindValue(':user_id', $regs[1]);
		$stmt->execute();
		if ($user = $stmt->fetchObject()) {
		
			$connectedUsers[$user->id] = $user;
			updateBroadcast($connectedUsers);
		}
	} else if (preg_match('/Received DISCONNECT from user_(\d+)/', $line, $regs) ||
		preg_match('/Client user_(\d+) has exceeded timeout, disconnecting./', $line, $regs) ||
		preg_match('/Socket read error on client user_(\d+), disconnecting./', $line, $regs)) {
	
		if (isset($connectedUsers[$regs[1]])) {
		
			unset($connectedUsers[$regs[1]]);
			updateBroadcast($connectedUsers);
		}
	}
}

pclose($handle);
