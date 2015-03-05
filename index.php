<?php
	session_start();

	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');
	include('module/class/Page.class.php');
	include('module/class/Link.class.php');
	include('module/class/Header.class.php');
	include('module/class/Footer.class.php');
	include('module/modelise.lib.php');

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
				$tab=array();
				$blocs=array("include/header.html", "layout/aside.html");
				$pages=new Page("minicms");
				if(array_key_exists("pages", $_POST)){
					//Cas au clic sur la partie Gestion des Pages		
					$tab=$pages->getAllPages();
					$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesPages=array();
					//$donneesFooters=array();
					//Récupération de tous les éléments présent dans la base		
					$tabLinks=$pages->getLinks();
					$tabHeaders=$pages->getHeaders();
					$tabFooters=$pages->getFooters();
					if($tab)
					{
						foreach ($tab as $key => $value) {
							
							//$tabFooters=$pages->getLinks();

							//Dans la boule les élément existent dans ne peuvent être que modifiés
							$value['buttontext']='Modifier';
							
							//Cas du publié ou non
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
							
							$aff.=getPageView($view, $pages, $value, $tabLinks, $tabHeaders, $tabFooters);
						}
						//Hors de la boucle, les éléments n'existent pas
						//donc doivent être édités.
						$donneesPages['buttontext']='Ajouter';
						$donneesPages['buttonsuppstate']='disabled';
						$aff.=getPageView($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters);
					}
					else
					{
						//$aff.="Aucune page ajoutée";
						$donneesPages['state']="Aucune page ajoutée";
						$donneesPages['buttontext']="Ajouter";
						$donneesPages['buttonsuppstate']='disabled';
						$aff.=getPageView($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters);
					}
				}
				elseif(array_key_exists("liens", $_POST)){
					$link=new Link("minicms");
					$tab=$link->getAllLinks();
					$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesLiens=array();
					//Récupération de tous les éléments présent dans la base		
					$tabPages=$pages->getAllPages();
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
							$aff.=getLinkView($view, $pages, $value, $tabPages);
						}
						$donneesLiens['buttontext']='Ajouter';
						$donneesLiens['buttonsuppstate']='disabled';
						//print_r($donneesLiens);
						$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
					}
					else
					{
						$donneesLiens['state']="Aucun lien ajouté";
						$donneesLiens['buttontext']="Ajouter";
						$donneesLiens['buttonsuppstate']='disabled';
						$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
					}
				}
				elseif(array_key_exists("header", $_POST)){
					$header=new Header("minicms");
					$tab=$header->getAllHeaders();
					$donnees=array(array("title"=>"Administration des Headers"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$donneesHeaders=array();
					$donneesLinks=array();
					if($tab)
					{
						foreach ($tab as $key => $value){
							$tabLink=$header->getLinks();
							//print_r($tabLink);
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
							$value['buttontext']='Modifier';
							array_push($donneesHeaders, $value);
							$aff.=$view->renderListSameFile($donneesHeaders, "layout/header-top.html");
							foreach($tabLink as $key => $valueLink){
								if($header->linkIsInHeader($valueLink["id"], $value["id"]))
								{
									$valueLink["selectedornot"]="selected";
								}
								$donneesLinks[]=$valueLink;
								
							}
							$aff.=$view->renderListSameFile($donneesLinks, "layout/dependanceLiens.html");		
							$aff.=$view->renderListSameFile($donneesHeaders, "layout/header-bottom.html");						
						}
						$donneesHeadersNew=array();
						$donneesHeadersNew[0]['buttontext']='Ajouter';
						$donneesHeadersNew[0]['buttonsuppstate']='disabled';
						$aff.=$view->renderListSameFile($donneesHeadersNew, "layout/header-top.html");
						foreach($donneesLinks as $key => $value){
							if(isset($value["selectedornot"]))
							{
								unset($value["selectedornot"]);
							}
							$donneesLinks[$key]=$value;
								
						}
						$aff.=$view->renderListSameFile($donneesLinks, "layout/dependanceLiens.html");		
						$aff.=$view->renderListSameFile($donneesHeadersNew, "layout/header-bottom.html");
					
					}
					else
					{
						$donneesHeaders[0]['state']="Aucun Header ajouté";
						$donneesHeaders[0]['buttontext']="Ajouter";
						$donneesHeaders[0]['buttonsuppstate']='disabled';
						//$aff.=$view->renderListSameFile($donneesHeaders, "layout/lien.html");
					}
				}
				else
				{

				}
				break;
			case 'liens':
				//print_r($_POST);
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
				$page=new Page("minicms");
				$link=new Link("minicms");
				$aff.=$view->renderList($donnees, $blocs);
				$tabPages=$page->getAllPages();
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
							$aff.=getLinkView($view, $page, $value, $tabPages);
						}
						$donneesLiens['buttontext']='Ajouter';
						$donneesLiens['buttonsuppstate']='disabled';
						$aff.=getLinkView($view, $page, $donneesLiens, $tabPages);
						unset($tab[0]['state']);
					else:
						$donneesLiens['buttontext']='Ajouter';
						$donneesLiens['state']='Il ne reste plus aucun lien !';
						$donneesLiens['buttonsuppstate']='disabled';
						$aff.=getLinkView($view, $page, $donneesLiens, $tabPages);
					endif;	
				}
				elseif(array_key_exists("Modifier", $_POST))
				{
					$rep= array_key_exists("dependancePages", $_POST) ? $link->updateLink($_POST["Modifier"], $_POST["url"], $_POST["desc"], $_POST["published"], $_POST['dependancePages'])
						: $link->updateLink($_POST["Modifier"], $_POST["url"], $_POST["desc"], $_POST["published"], null);
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
						$aff.=getLinkView($view, $page, $value, $tabPages);
					
					}
					$donneesLiens['buttontext']='Ajouter';
					$donneesLiens['buttonsuppstate']='disabled';
					$aff.=getLinkView($view, $page, $donneesLiens, $tabPages);
				}
				else
				{
					$rep= array_key_exists("dependancePages", $_POST) ? $link->insertNewLink($_POST["url"], $_POST["desc"], $_POST["published"], $_POST['dependancePages'])
						: $link->insertNewLink($_POST["url"], $_POST["desc"], $_POST["published"], null);
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
						$aff.=getLinkView($view, $page, $value, $tabPages);
					}
					$donneesLiens['buttontext']='Ajouter';
					$donneesLiens['buttonsuppstate']='disabled';
					$aff.=getLinkView($view, $page, $donneesLiens, $tabPages);
				}
				break;
			case 'pages':
				$page=new Page("minicms");
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				$tabLinks=$page->getLinks();
				$tabHeaders=$page->getHeaders();
				$tabFooters=$page->getFooters();
				if(array_key_exists("delete", $_POST)):
					$rep=$page->deletePage($_POST["delete"]);
					$tab=$page->getAllPages();
					$donneesPagesAjouter=array();
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
							//$donneesPages[$key]=$value;
							$aff.=getPageView($view, $page, $value, $tabLinks, $tabHeaders, $tabFooters);

						}
						$donneesPagesAjouter['buttontext']='Ajouter';
						$donneesPagesAjouter['buttonsuppstate']='disabled';
						$aff.=getPageView($view, $page, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
						unset($tab[0]['state']);
					}
					else
					{
						$donneesPagesAjouter['buttontext']='Ajouter';
						$donneesPagesAjouter['state']='Il ne reste plus aucune page !';
						$donneesPagesAjouter['buttonsuppstate']='disabled';
						$aff.=getPageView($view, $page, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					}	
				elseif(array_key_exists("Modifier", $_POST)):
					$rep= array_key_exists("dependanceLinks", $_POST) ? $page->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["url"], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'])
					: $page->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["url"], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter']);
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
						$aff.=getPageView($view, $page, $value, $tabLinks, $tabHeaders, $tabFooters);
					}
					$donneesPagesAjouter['buttontext']='Ajouter';
					$donneesPagesAjouter['buttonsuppstate']='disabled';
					$aff.=getPageView($view, $page, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					unset($donneesPages[0]['state']);
				else:
					//print_r($_POST);
					echo $_POST['dependanceFooter'];
					$rep= array_key_exists("dependanceLinks", $_POST) ? $page->insertNewPage($_POST["title"], $_POST["name"], $_POST['url'], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'])
					: $page->insertNewPage($_POST["title"], $_POST["name"], $_POST['url'], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter']);
					
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
						$aff.=getPageView($view, $page, $value, $tabLinks, $tabHeaders, $tabFooters);
					}
					$donneesPagesAjouter['buttontext']='Ajouter';
					$donneesPagesAjouter['buttonsuppstate']='disabled';
					$aff.=getPageView($view, $page, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					// unset($donneesPages[0]['state']);
				endif;
				break;
			case 'header':
				$header=new Header("minicms");
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Header"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				break;
			case 'footer':
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
