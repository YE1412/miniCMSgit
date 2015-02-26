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
	
	if(isset($_POST['action'])) :
		$view = new View("include/head.html");
		//Contenu html affiché.
		$aff="";
		switch ($_POST['action']) {
			case 'connexion':
				$user = new User('minicms');
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
				$blocs=array("include/header.html", "layout/aside.html");
				if(array_key_exists("pages", $_POST)){
					$pages=new Page("minicms");
					$tab=$pages->getAllPages();
					$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesPages=array();
					if($tab)
					{
						foreach ($tab as $key => $value) {
							switch($value['published'])
							{
								case 0:
									unset($value['published']);
									$value['publishedno']='selected';
									break;
								case 1:
									unset($value['published']);
									$value['publishedyes']='selected';
									break;
								default:
									break;
							}
							array_push($donneesPages, $value);
						}
						$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
					}
					else
					{
						$aff.="Aucune page ajoutée";
					}
				}
				elseif(array_key_exists("liens", $_POST)){
					$link=new Link("minicms");
					$tab=$link->getAllLinks();
					$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesLiens=array();
					if($tab)
					{
						foreach ($tab as $key => $value) {
							switch($value['published'])
							{
								case 0:
									unset($value['published']);
									$value['publishedno']='selected';
									break;
								case 1:
									unset($value['published']);
									$value['publishedyes']='selected';
									break;
								default:
									break;
							}
							array_push($donneesLiens, $value);
						}
						$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					}
					else
					{
						$aff.="Aucun lien ajouté";
					}
				}
				elseif(array_key_exists("header", $_POST)){
				
				}
				else
				{

				}
				break;
			case 'liens':
				//print_r($_POST);
				$aff="";
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
				$link=new Link("minicms");
				$aff.=$view->renderList($donnees, $blocs);
				if(array_key_exists("delete", $_POST))
				{
					$rep=$link->deleteLink($_POST["delete"]);
					$donneesLiens=$link->getAllLinks();
					$state = $rep != 0 ? "Lien supprimé avec succès !" : "Erreur lors de la suppression du lien !";
					$donneesLiens[0]['state']=$state;
					$aff.= sizeof($donneesLiens[0]) > 1 ? $view->renderListSameFile($donneesLiens, "layout/lien.html") : "Il ne reste plus aucun lien !";
					unset($donneesLiens[0]['state']);
				}
				else
				{
					//var_dump($_POST);
					$rep=$link->updateLink($_POST["edit"], $_POST["url"], $_POST["desc"], $_POST["publised"]);		
					$donneesLiens=$link->getAllLinks();
					$state = $rep != 0 ? "Lien modifié avec succès !" : "Erreur lors de la modification du lien !";
					$donneesLiens[0]['state']=$state;
					foreach ($donneesLiens as $key => $value) {
						switch($value['published'])
						{
							case 0:
								unset($value['published']);
								$donneesLiens[$key]['publishedno']='selected';
								break;
							case 1:
								unset($value['published']);
								$donneesLiens[$key]['publishedyes']='selected';
								break;
							default:
								break;
						}
					}
					$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					unset($donneesLiens[0]['state']);
				}
				break;
			case 'pages':
				$page=new Page("minicms");
				break;
			default:
				break;
		}
		echo $aff;
	else:
		$view = new View("include/head.html");
		// Vérification de l'utilisateur connecté
		if(isset($_SESSION['user'])){
			$donnees=array(array("title"=>"Portail Administration"), $_SESSION, array(), array());
			echo $view->renderList($donnees, array("include/header.html", "layout/aside.html","layout/accueil.html"));
		}
		else
		{
			$donnees=array(array("title"=>"Connexion Partie Administration"), array());
			echo $view->renderList($donnees, array("layout/connexion.html"));
		}
	endif;
?>
