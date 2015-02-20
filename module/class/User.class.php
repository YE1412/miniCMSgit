<?php 	
/**
* 
*/
class User 
{
	private $datas;
	private $host='localhost';
	private $bdd;
	
	public function __construct($dbName, $dbLogin, $dbPass)
	{
		$this->loadDatas($dbName, $dbLogin, $dbPass);
	}

	public function login($login, $password)
	{
		$req=$this->bdd->prepare("SELECT id, login, password FROM users WHERE login=:login AND password=:pass LIMIT 1");
		$req->execute(array(
			"login" =>  $login,
			"pass" =>  $password
		));
		$reponse = $req->fetch();
		if(sizeof($reponse)!=0)
		{
			return $reponse; 
		}
		else
		{
			return false;
		}
	}

	public function loadDatas($dbName, $dbLogin, $dbPass){
		$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$dbName, $dbLogin, $dbPass, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			));
	}
	public function display()
	{
		print_r($this->datas);
	}
	
	public function setPassword($id, $password)
	{
		if(!$password)
			return;
		$req=$this->bdd->prepare("UPDATE users SET password = :pass WHERE id =:id LIMIT 1;");
		$req->execute(array(
			"id" =>  $id,
			"pass" =>  $password
		));
	}

	public function getLogin()
	{
		return $this->login;
	}
}
?>