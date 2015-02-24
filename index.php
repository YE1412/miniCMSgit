<?php
	session_start();

	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');

	// Deconnexion
		if(isset($_GET['action']) && $_GET['action'] == "logout")
		{
			$user = new User('minicms');
			$user->logout();
		}

	// Vérification de l'utilisateur connecté
	if(isset($_SESSION['user'])){
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array());
		echo $view->renderList($donnees, array("include/header.html", "layout/accueil.html"));
	}

	
	if(isset($_POST['action'])) :
		switch ($_POST['action']) {
			case 'connexion':
				$user = new User('minicms');
				$view = new View("include/head.html");
				$conn=$user->login($_POST['login'], $_POST['mdp']);
				if($conn)
				{ 
					$_SESSION['user'] = $conn[0];
					$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array(), array());
					echo $view->renderList($donnees, array("include/header.html", "layout/aside.html", "layout/accueil.html"));
				}
				else
				{
					$_POST["connexion"]["fail"]="Combinaison login/mot de passe erronée !";
					$donnees=array(array("title"=>"Connexion Partie Administration"), $_POST);
					echo $view->renderList($donnees, array("layout/connexion.html"));					
				}
				break;

			default:
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
