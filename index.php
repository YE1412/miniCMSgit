<?php
	session_start();

	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');


	// Vérification de l'utilisateur connecté
	if(isset($_SESSION['user'])){
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array());
		echo $view->renderList($donnees, array("include/header.html", "layout/accueil.html"));
	}

	// Deconnexion
	if(isset($_GET['action']) && $_GET['action'] == "logout")
	{
		$user = new User('minicms');
		$user->logout();
	}

	if(isset($_POST['action'])) :
		switch ($_POST['action']) {
			case 'connexion':
				$user = new User('minicms');
				$conn=$user->login($_POST['login'], $_POST['mdp']);
				if($conn)
				{ 
					$_SESSION['user'] = $conn[0];
					$view = new View("include/head.html");
					$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array());
					echo $view->renderList($donnees, array("include/header.html", "layout/accueil.html"));
				}
				else
				{
					$view=new View("include/head.html");
					echo $view->render(array("title"=>"Connexion Partie Administration"));
	
					$view=new View("layout/connexion.html");
					$_POST["connexion"]["fail"]="Combinaison login/mot de passe erronée !";
					echo $view->render($_POST);
				}
				break;

			default:
				$view=new View("layout/accueil.html");
				break;
		}
	else:
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Connexion Partie Administration"), array());
		echo $view->renderList($donnees, array("layout/connexion.html"));
	endif;

/*	$db=new DB("minicms");
	echo $db->delete("users", array("id"=>5));
	*/
?>
