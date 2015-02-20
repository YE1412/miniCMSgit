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
			$this->data = $reponse[0];
			$myUser['id'] = $reponse[0]['id'];
			$_SESSION['user'] = $myUser['id'];
			return $reponse;
		}else{
			return false;
		}
	}

	public function logout()
	{
		$_SESSION = array();
		session_destroy();
		return "Ok";
	}
	// public function loadDatas(){
	// 	$this->datas = parent::select('users');
	// }
	// public function display()
	// {
	// 	print_r($this->datas);
	// }
	// public function test()
	// {
	// 	return parent::select('users');
	// }


	// public function setPassword($id, $password)
	// {
	// 	if(!$password)
	// 		return;
	// 	$req=$this->bdd->prepare("UPDATE users SET password = :pass WHERE id =:id LIMIT 1;");
	// 	$req->execute(array(
	// 		"id" =>  $id,
	// 		"pass" =>  $password
	// 	));
	// }

	// public function getLogin()
	// {
	// 	return $this->login;
	// }
}
?>
