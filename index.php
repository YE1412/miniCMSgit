<?php
	session_start();

	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');	
	include('module/class/Page.class.php');
	include('module/class/Link.class.php');
	

	// Deconnexion
	if(isset($_GET['action']) && $_GET['action'] == "logout")
	{
		$user = new User('minicms');
		$user->logout();
	}

	// Vérification de l'utilisateur connecté
	/*if(isset($_SESSION['user'])){
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array(), array());
		echo $view->renderList($donnees, array("include/header.html", "layout/aside.html", "layout/accueil.html"));
	}*/

	
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
			case 'menu':
				//var_dump($_POST);
				$tab=array();
				$view = new View("include/head.html");
				$blocs=array("include/header.html", "layout/aside.html");
				$aff="";
				if(array_key_exists("pages", $_POST)){
					$pages=new Page("minicms");
					$tab=$pages->getAllPages();
					$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesPages=array();
					foreach ($tab as $key => $value) {
						switch($value['published'])
						{
							case 0:
								$value['published']='Non';
								break;
							case 1:
								$value['published']='Oui';
								break;
							default:
								break;
						}
						array_push($donneesPages, $value);
						//array_push($blocs, "layout/page.html");
					}
					$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
				}
				elseif(array_key_exists("liens", $_POST)){
					$link=new Link("minicms");
					$tab=$link->getAllLinks();
					$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesLiens=array();
					foreach ($tab as $key => $value) {
						switch($value['published'])
						{
							case 0:
								$value['published']='Non';
								break;
							case 1:
								$value['published']='Oui';
								break;
							default:
								break;
						}
						array_push($donneesLiens, $value);
					}
					$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
				}
				elseif(array_key_exists("header", $_POST)){

				}
				else
				{

				}
				echo $aff;
				break;
			default:
				break;
		}
	else:
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Connexion Partie Administration"), array());
		echo $view->renderList($donnees, array("layout/connexion.html"));
	endif;
?>
