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
							$value['buttontext']='Modifier';
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
						$donneesPages[sizeof($tab)]['buttontext']='Ajouter';
						$donneesPages[sizeof($tab)]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
					}
					else
					{
						$aff.="Aucune page ajoutée";
						$donneesPages[0]['state']="Aucune page ajoutée";
						$donneesPages[0]['buttontext']="Ajouter";
						$donneesPages[0]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
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
							$value['buttontext']='Modifier';
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
						$donneesLiens[sizeof($tab)]['buttontext']='Ajouter';
						$donneesLiens[sizeof($tab)]['buttonsuppstate']='disabled';
						//print_r($donneesLiens);
						$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					}
					else
					{
						$donneesLiens[0]['state']="Aucun lien ajouté";
						$donneesLiens[0]['buttontext']="Ajouter";
						$donneesLiens[0]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
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
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
				$link=new Link("minicms");
				$aff.=$view->renderList($donnees, $blocs);
				if(array_key_exists("delete", $_POST))
				{
					$rep=$link->deleteLink($_POST["delete"]);
					$tab=$link->getAllLinks();
					$donneesLiens=array();
					if($tab):
						$state = $rep != 0 ? "Lien supprimé avec succès !" : "Erreur lors de la suppression du lien !";
						foreach ($tab as $key => $value) {
							if($key==0)
							{
								$value['state']=$state;
							}
							$value['buttontext']='Modifier';
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
							$donneesLiens[$key]=$value;
						}
						$donneesLiens[sizeof($tab)]['buttontext']='Ajouter';
						$donneesLiens[sizeof($tab)]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
						unset($tab[0]['state']);
					else:
						$donneesLiens[0]['buttontext']='Ajouter';
						$donneesLiens[0]['state']='Il ne reste plus aucun lien !';
						$donneesLiens[0]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					endif;	
				}
				elseif(array_key_exists("Modifier", $_POST))
				{
					//var_dump($_POST);
					$rep=$link->updateLink($_POST["Modifier"], $_POST["url"], $_POST["desc"], $_POST["published"]);		
					$tab=$link->getAllLinks();
					$state = $rep != 0 ? "Lien modifié avec succès !" : "Erreur lors de la modification du lien !";
					$donneesLiens=array();
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
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
						$donneesLiens[$key]=$value;
					}
					$donneesLiens[sizeof($tab)]['buttontext']='Ajouter';
					$donneesLiens[sizeof($tab)]['buttonsuppstate']='disabled';
					$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					unset($donneesLiens[0]['state']);
				}
				else
				{
					$rep=$link->insertNewLink($_POST["url"], $_POST["desc"], $_POST["published"]);		
					$tab=$link->getAllLinks();
					$state = $rep != 0 ? "Lien ajouté avec succès !" : "Erreur lors de l'ajout du lien !";
					$donneesLiens=array();
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
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
						$donneesLiens[$key]=$value;
					}
					$donneesLiens[sizeof($tab)]['buttontext']='Ajouter';
					$donneesLiens[sizeof($tab)]['buttonsuppstate']='disabled';
					$aff.=$view->renderListSameFile($donneesLiens, "layout/lien.html");
					unset($donneesLiens[0]['state']);
				}
				break;
			case 'pages':
				$page=new Page("minicms");
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				//print_r($_POST);
				if(array_key_exists("delete", $_POST)):
					$rep=$page->deletePage($_POST["delete"]);
					$tab=$page->getAllPages();
					$donneesPages=array();
					if($tab)
					{
						$state = $rep != 0 ? "Page supprimée avec succès !" : "Erreur lors de la suppression de la page !";
						foreach ($tab as $key => $value) {
							if($key==0)
							{
								$value['state']=$state;
							}
							$value['buttontext']='Modifier';
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
							$donneesPages[$key]=$value;
						}
						$donneesPages[sizeof($tab)]['buttontext']='Ajouter';
						$donneesPages[sizeof($tab)]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
						unset($tab[0]['state']);
					}
					else
					{
						$donneesPages[0]['buttontext']='Ajouter';
						$donneesPages[0]['state']='Il ne reste plus aucune page !';
						$donneesPages[0]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
					}	
				elseif(array_key_exists("Modifier", $_POST)):
					$rep=$page->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["url"], $_POST["published"]);		
					$tab=$page->getAllPages();
					$state = $rep != 0 ? "Page modifiée avec succès !" : "Erreur lors de la modification de la page !";
					$donneesPages=array();
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
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
						$donneesPages[$key]=$value;
					}
					$donneesPages[sizeof($tab)]['buttontext']='Ajouter';
					$donneesPages[sizeof($tab)]['buttonsuppstate']='disabled';
					$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
					unset($donneesPages[0]['state']);
				else:
					$rep=$page->insertNewPage($_POST["title"], $_POST["name"], $_POST['url'], $_POST["published"]);		
					$tab=$page->getAllPages();
					$state = $rep != 0 ? "Page ajoutée avec succès !" : "Erreur lors de l'ajout de la page !";
					$donneesPages=array();
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
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
						$donneesPages[$key]=$value;
					}
					$donneesPages[sizeof($tab)]['buttontext']='Ajouter';
					$donneesPages[sizeof($tab)]['buttonsuppstate']='disabled';
					$aff.=$view->renderListSameFile($donneesPages, "layout/page.html");
					unset($donneesPages[0]['state']);
				endif;
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
