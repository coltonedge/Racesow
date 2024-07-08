<?php

$writer = new XMLWriter('1.0', 'utf-8');
$writer->openURI('php://output');
$writer->setIndent(false); 
$writer->startDocument();

if (count($_POST) && array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {

	try {

		$dbh = new PDO('mysql:host=localhost;dbname=warsow_racenet', 'root', 'deluxe85*!');		
		$identity = Racenet_Auth_Remote::factory($dbh)
			->setUsername($_POST['username'])
			->setPassword($_POST['password'])
			->authenticate()
			->getIdentity();
		
		$writer->startElement('identity');
		
		$writer->startElement('id');
		$writer->startCData();
		$writer->text($identity->user_id);
		$writer->endCData();
		$writer->endElement();
		
		$writer->startElement('name');
		$writer->startCData();
		$writer->text($identity->username);
		$writer->endCData();
		$writer->endElement();
		
		$writer->startElement('flags');
		$writer->startCData();
		$writer->text($identity->racenet_flags);
		$writer->endCData();
		$writer->endElement();
			
		$writer->endElement();
			
	} catch (Exception $e) {

		$writer->startElement('error');
		$writer->startCData();
		$writer->text($e->getMessage());
		$writer->endCData();
		$writer->endElement();
	}

} else {

	$writer->startElement('error');
	$writer->startCData();
	$writer->text("Invalid request");
	$writer->endCData();
	$writer->endElement();
}

$writer->endDocument();
echo $writer->outputMemory();

class Racenet_Auth_Remote
{
	protected $_dbh;

	public static function factory($dbh)
	{
		return new self($dbh);
	}
	
	protected function __construct($dbh)
	{
		$this->_dbh = $dbh;
	}
	
	public function setUsername($name)
	{
		$this->_username = $name;
		return $this;
	}
	
	public function setPassword($pass)
	{
		$this->_password = $pass;
		return $this;
	}
	
	public function authenticate()
	{
		$stmt = $this->_dbh->prepare("SELECT * FROM `phpbb_users` WHERE `username` = :username AND `user_password` = MD5(:password) LIMIT 1;");
		$stmt->bindValue(':username', $this->_username, PDO::PARAM_STR);
		$stmt->bindValue(':password', $this->_password, PDO::PARAM_STR);
		
		if (!$stmt->execute()) {
		
			throw new Exception("Internal error. Please try again later.");
		}
		
		if (!$identity = $stmt->fetchObject()) {
		
			throw new Exception("Wrong username and/or password.");
		}
		
		if ($identity->user_active == 0) {
		
			throw new Exception("Account is inactive.");
		}
		
		$this->_identity = $identity;
		return $this;
	}
	
	public function getIdentity()
	{
		return $this->_identity;
	}
}