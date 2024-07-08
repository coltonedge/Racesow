<form method="POST">
<table border="0">
<tr><td>Topic:</td><td><input type="text" name="topic" /></td></tr>
<tr><td>Message:</td><td><textarea name="msg"></textarea></td></tr>
</table>
<input type="submit" value="send">
</form>

<?php

if (count($_POST)) {

	require('../../library/SAM/php_sam.php');
	 
	//create a new connection object
	$conn = new SAMConnection();

	//start initialise the connection
	$conn->Connect(SAM_MQTT, array(
		SAM_HOST => '127.0.0.1',
		SAM_PORT => 1883
	));
	 
	 $conn->Send('topic://'. $_POST['topic'], (object)array('body' => $_POST['msg']));
	 $conn->Disconnect();
 
 }