<?php
/**
*
*/
class User extends DB
{
	private $datas;
	private $host='localhost';

	public function __construct($db)
	{
		parent:: __construct ($db);
	}

	public function login($login, $password)
	{
		$reponse = parent::select( 'users',array('login'=>addslashes($login),'password'=>addslashes(md5($password)) ) );
		if(count($reponse)>0){
			return $reponse;
		}else{
			return false;
		}
	}

	public function logout()
	{
		$_SESSION = array();
		session_destroy();
	}

	public function insertNewUser($login, $pass, $email){
		$param=array("login"=>$login, "password"=>md5($pass), "email"=>$email);
	    parent::insert("users", $param);
	}

	public function updateUser($login, $pass, $email)
	{
		$clause=array('id'=>$_SESSION['user']['id']);
		$param=array("login"=>$login, "password"=>md5($pass), "email"=>$email);
		parent::update("users", $clause, $param);
	}
}
?>
