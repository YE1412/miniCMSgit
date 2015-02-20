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
			return $reponse; 
		}else{
			return false;
		}
	}
}
?>