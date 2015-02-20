<?php
	session_start();
	$myUser = $_SESSION['user'] = array();

	include('include/head.html');
	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');


	$view=new View("layout/connexion.html");

	if(isset($_POST['action'])) :
		extract($_POST);
		switch ($_POST['action']) {
			case 'connexion':
				$user = new User('minicms');
				if($user->login($login, $mdp)) $view = new View("layout/accueil.html");
				break;

			default:
				$view=new View("layout/accueil.html");
				break;
		}
		include('include/header.html');
	else:
		echo $view->render(array());
	endif;
?>
