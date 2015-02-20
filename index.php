<?php
	session_start();

	include('include/head.html');
	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');


	// Vérification de l'utilisateur connecté
	if(!isset($_SESSION['user'])){
		$view = new View("layout/accueil.html");
		echo $view->render(array());
	}

	// Deconnexion
	if(isset($_GET['action']) && $_GET['action'] == "logout")
	{
		$user = new User('minicms');
		$user->logout();
	}


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
