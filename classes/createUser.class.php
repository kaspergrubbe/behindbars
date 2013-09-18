<?php
require_once('pdoMysql.class.php');

class createUser 
{
	private $db;
	private $wanted_username;
	private $wanted_password;
	private $wanted_email;
	private $wanted_newsletter;
	
	function __construct($username, $password, $email, $newsletter, pdoMysql $db)
	{
		$this->db = $db;
		$this->wanted_username=$username;
		$this->wanted_password=$password;
		$this->wanted_email=$email;
		$this->wanted_newsletter=$newsletter;
		
		$this->checkUsername();
		$this->validateEmail();
		$this->createTheUser();
	}
	function checkUsername()
	{
		if(preg_match('/[^A-ZÆØÅa-zæøå0-9_-]+/', $this->wanted_username))
		{
			throw new Exception("Brugernavnet indeholder ugyldige tegn, det må kun indeholde A-&aring;, 0-9 samt bindestreg (-) og understreg (_).");
		}
		if(strlen($this->wanted_username) > 20)
		{
			throw new Exception("Det valgte brugernavn er for langt, gr&aelig;nsen er på 20 tegn.");
		}
		if(strlen($this->wanted_username) <= 2)
		{
			throw new Exception("Det valgte brugernavn er for kort, gr&aelig;nsen er på minimum 3 tegn.");
		}
		$num = $this->db->fetchRow("SELECT COUNT(*) as varNum FROM bmx_user WHERE username = '$this->wanted_username'");
		if($num['varNum'] > 0)
		{
			throw new Exception("Der er allerede en oprettet bruger med det brugernavn.");
		}
	}
	function validateEmail()
	{
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->wanted_email)) 
		{
			throw new Exception("E-mailen der er angivet er ikke rigtigt indskrevet.");
		}
	}
	function createTheUser()
	{
		if(strlen($this->wanted_password) <= 5)
		{
			throw new Exception("Din kode skal v&aelig;re minimum 6 cifre lang.");
		}
		$password = sha1(md5($this->wanted_password));
		$this->db->insert("INSERT INTO bmx_user (id, username, password, email) VALUES (?,?,?,?)",array('NULL',$this->wanted_username,$password,$this->wanted_email));
	}
}
#try {
#	$db = new pdoMysql();
#	$create = new createMyUser("Testxo","test","lol@lol.dk", $db);
#	echo"Yip";
#} catch (Exception $ex) {
#	echo"Error: ".$ex->getMessage()."\n";
#}
?>
