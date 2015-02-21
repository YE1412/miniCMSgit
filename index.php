<?php
	session_start();

	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');


	// Vérification de l'utilisateur connecté
	if(isset($_SESSION['user'])){
		$view = new View("layout/accueil.html");
		echo $view->render(array($_SESSION));
	}

	// Deconnexion
	if(isset($_GET['action']) && $_GET['action'] == "logout")
	{
		$user = new User('minicms');
		$user->logout();
	}

	if(isset($_POST['action'])) :
		extract($_POST);
		switch ($_POST['action']) {
			case 'connexion':
				$user = new User('minicms');
				$conn=$user->login($login, $mdp);
				if($conn)
				{ 
					$view = new View("layout/accueil.html");
					$_SESSION['user'] = $conn[0];
					echo $view->render();

					$view = new View("include/header.html");
					echo $view->render($_SESSION);
				}
				else
				{
					$view=new View("include/head.html");
					echo $view->render(array("title"=>"Connexion Partie Administration"));
	
					$view=new View("layout/connexion.html");
					$_POST["connexion"]["fail"]="Combinaison login/mot de passe érronée !";
					echo $view->render($_POST);
				}
				break;

			default:
				$view=new View("layout/accueil.html");
				break;
		}
	else:
		$view=new View("include/head.html");
		echo $view->render(array("title"=>"Connexion Partie Administration"));
	
		$view=new View("layout/connexion.html");
		echo $view->render(array());
	endif;
?>
