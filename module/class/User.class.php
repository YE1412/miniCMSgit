<?php 	
/**
* 
*/
class User extends DB
{
	private $datas;
	private $host='localhost';
	private $bdd;
	
	public function __construct($db)
	{
		parent:: __construct ($db);
	}

	public function login($login, $password)
	{
		// $reponse = parent::select('users', array('login'=>$login, 'password'=>md5($password)));
		$reponse = parent::select( 'users',array('login'=>$login,'password'=>md5($password) ) );
		return $reponse;
		// if(count($reponse)>0){
		// 	return $reponse; 
		// }else{
		// 	return false;
		// }
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