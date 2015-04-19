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
	//print_r($_POST);
	if(isset($_POST['action'])) :
		$view = new View("include/head.html");
		$pages=new Page("minicms");
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
						$view->setFile("layout/pageview/pageSortable-top.html");
						$aff.=$view->render(array());
						foreach ($tab as $key => $value) {
							
							//$tabFooters=$pages->getLinks();

							//Dans la boule les élément existent dans ne peuvent être que modifiés
							$value['buttontext']='Modifier';
							$nameConv = strtr($value["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					
							$value['source']='images/'.$nameConv.'-'.$value['id'].'/'.$value["image"];
							$value['sortable']='true';
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
						$view->setFile("layout/pageview/pageSortable-bottom.html");
						$aff.=$view->render(array());
						//Hors de la boucle, les éléments n'existent pas
						//donc doivent être édités.
						$donneesPages['buttontext']='Ajouter';
						$donneesPages['buttonsuppstate']='disabled';
						$donneesPages['required']='required';
						$donneesPages['sortable']='false';
						$aff.=getPageView($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters);
					}
					else
					{
						//$aff.="Aucune page ajoutée";
						$donneesPages['state']="Aucune page ajoutée";
						$donneesPages['buttontext']="Ajouter";
						$donneesPages['buttonsuppstate']='disabled';
						$donneesPages['required']='required';
						$donneesPages['sortable']='false';
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
					$tabPages=$pages->getAllPages();
					$donneesHeader=array();
					if($tab)
					{
						foreach ($tab as $key => $value){
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
							
							$aff.=getHeaderView($view, $pages, $value, $tabPages);
						}
						$donneesHeader['buttontext']='Ajouter';
						$donneesHeader['buttonsuppstate']='disabled';
						$aff.=getHeaderView($view, $pages, $donneesHeader, $tabPages);		
					}
					else
					{
						$donneesHeader['state']="Aucun Header ajouté";
						$donneesHeader['buttontext']="Ajouter";
						$donneesHeader['buttonsuppstate']='disabled';
						$aff.=getHeaderView($view, $pages, $donneesHeader, $tabPages);	
					}
				}
				else
				{
					$footer=new Footer("minicms");
					$tab=$footer->getAllFooters();
					$donnees=array(array("title"=>"Administration des Footers"), $_SESSION, array());
					$aff=$view->renderList($donnees, $blocs);
					$tabPages=$pages->getAllPages();
					$donneesFooter=array();
					if($tab)
					{
						foreach ($tab as $key => $value){
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
							
							$aff.=getFooterView($view, $pages, $value, $tabPages);
						}
						$donneesFooter['buttontext']='Ajouter';
						$donneesFooter['buttonsuppstate']='disabled';
						$aff.=getFooterView($view, $pages, $donneesFooter, $tabPages);		
					}
					else
					{
						$donneesFooter['state']="Aucun Footer ajouté";
						$donneesFooter['buttontext']="Ajouter";
						$donneesFooter['buttonsuppstate']='disabled';
						$aff.=getFooterView($view, $pages, $donneesFooter, $tabPages);	
					}
				}
				break;
			case 'liens':
				//print_r($_POST);
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Liens"), $_SESSION, array());
				$link=new Link("minicms");
				$aff.=$view->renderList($donnees, $blocs);
				$tabPages=$pages->getAllPages();
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
							$aff.=getLinkView($view, $pages, $value, $tabPages);
						}
						$donneesLiens['buttontext']='Ajouter';
						$donneesLiens['buttonsuppstate']='disabled';
						$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
						unset($tab[0]['state']);
					else:
						$donneesLiens['buttontext']='Ajouter';
						$donneesLiens['state']='Il ne reste plus aucun lien !';
						$donneesLiens['buttonsuppstate']='disabled';
						$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
					endif;	
				}
				elseif(array_key_exists("Modifier", $_POST))
				{
					$contenir=new Contenir("minicms");
					$rep= array_key_exists("dependancePages", $_POST) ? $contenir->updateLink($_POST["Modifier"], $_POST["url"], $_POST["desc"], $_POST["published"], $_POST['dependancePages'])
						: $contenir->updateLink($_POST["Modifier"], $_POST["url"], $_POST["desc"], $_POST["published"], null);
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
						$aff.=getLinkView($view, $pages, $value, $tabPages);
					
					}
					$donneesLiens['buttontext']='Ajouter';
					$donneesLiens['buttonsuppstate']='disabled';
					$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
				}
				else
				{
					$contenir=new Contenir("minicms");
					$rep= array_key_exists("dependancePages", $_POST) ? $contenir->insertLink($_POST["url"], $_POST["desc"], $_POST["published"], $_POST['dependancePages'])
						: $contenir->insertLink($_POST["url"], $_POST["desc"], $_POST["published"], null);
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
						$aff.=getLinkView($view, $pages, $value, $tabPages);
					}
					$donneesLiens['buttontext']='Ajouter';
					$donneesLiens['buttonsuppstate']='disabled';
					$aff.=getLinkView($view, $pages, $donneesLiens, $tabPages);
				}
				break;
			case 'pages':
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Pages"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				$tabLinks=$pages->getLinks();
				$tabHeaders=$pages->getHeaders();
				$tabFooters=$pages->getFooters();
				if(array_key_exists("delete", $_POST)):
					$nameConv = strtr($_POST["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					$uploads_dir = "images/".$nameConv."-".$_POST["delete"];
					//print_r($_FILES["imageUpload"]);
					if (file_exists($uploads_dir)) {
						$fichiers=scandir($uploads_dir);
						if($fichiers){
							foreach ($fichiers as $keyFolder => $value) {
								$delete= is_file("$uploads_dir/$value") ? unlink("$uploads_dir/$value") : false;
								if((!$delete) && ($value!="..")):
									$fichiers2=scandir("$uploads_dir/$value");
									foreach ($fichiers2 as $keyFolder2 => $value2) {
										$delete= is_file("$uploads_dir/$value/$value2") ? unlink("$uploads_dir/$value/$value2") : false;	
									}
									$delete=rmdir("$uploads_dir/$value");
								endif;	
							}
						}
						if (!rmdir($uploads_dir)) 
						{
							echo 'Echec lors de la suppréssion des répertoires...';
						}
					}
					$rep=$pages->deletePage($_POST["delete"]);
					$tab=$pages->getAllPages();
					$donneesPagesAjouter=array();
					if($tab)
					{
						$view->setFile("layout/pageview/pageSortable-top.html");
						$aff.=$view->render(array());
						$state = $rep != 0 ? "Page supprimée avec succès !" : "Erreur lors de la suppression de la page !";
						foreach ($tab as $key => $value) {
							if($key==0)
							{
								$value['state']=$state;
							}
							$value['buttontext']='Modifier';
							$nameConvUrl = strtr($value["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					
							$value['source']='images/'.$nameConvUrl.'-'.$value['id'].'/'.$value['image'];
							$value['sortable']='true';
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
							
							$aff.=getPageView($view, $pages, $value, $tabLinks, $tabHeaders, $tabFooters);
						}
						$view->setFile("layout/pageview/pageSortable-bottom.html");
						$aff.=$view->render(array());
						$donneesPagesAjouter['buttontext']='Ajouter';
						$donneesPagesAjouter['buttonsuppstate']='disabled';
						$donneesPagesAjouter['sortable']='false';
						$aff.=getPageView($view, $pages, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
						unset($tab[0]['state']);
					}
					else
					{
						$donneesPagesAjouter['buttontext']='Ajouter';
						$donneesPagesAjouter['state']='Il ne reste plus aucune page !';
						$donneesPagesAjouter['buttonsuppstate']='disabled';
						$donneesPagesAjouter['sortable']='false';
						$aff.=getPageView($view, $pages, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					}	
				elseif(array_key_exists("Modifier", $_POST)):
					$nameConv = strtr($_POST["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					//print_r($_FILES);
					$uploads_dir = "images/".$_POST['name']."-".$_POST['Modifier'];
					if($_FILES["imageUpload"])
					{	
						if (!file_exists($uploads_dir)) {
							if (!mkdir($uploads_dir, 0777, true)) {
							   echo 'Echec lors de la création du répertoire principal...';
							}
						}
						else
						{
							$fichiers=scandir($uploads_dir);
							if($fichiers){
								//print_r($fichiers);
								foreach ($fichiers as $keyFolder1 => $val1) {
									//echo "'$uploads_dir/$val1'";
									if(is_file("$uploads_dir/$val1")):
										unlink("$uploads_dir/$val1");
									endif;
									// $delete = is_file($val1) ? unlink("$uploads_dir/$val1") : false;		
								}
							}
						}
						foreach ($_FILES["imageUpload"]["error"] as $key => $error) {
						    if ($error == UPLOAD_ERR_OK) {
						        $tmp_name = $_FILES["imageUpload"]["tmp_name"][$key];
						        $name = $_FILES["imageUpload"]["name"][$key];
						        if (!move_uploaded_file($tmp_name, "$uploads_dir/$name"))
						    		echo "error";
						    
						    }
						    
						}
					}
					if(array_key_exists("idImage", $_POST)):
							
							foreach ($_POST["idImage"] as $keyImg => $valueImg) {
								$Ditectory=$_POST['Modifier']."-".$valueImg;
								$Path=$uploads_dir."/".$Ditectory;
								if(!file_exists($Path)) {
									if (!mkdir($Path, 0777, true)) {
									   echo 'Echec lors de la création du sous répertoires...';
									}
								}
								else
								{
									$fichiers=scandir($Path);
									if($fichiers){
										//print_r($fichiers);
										foreach ($fichiers as $keyFolder2 => $val2) {
											$delete = is_file("$Path/$val2") ? unlink("$Path/$val2") : false;		
										}
									}
								}
								foreach ($_FILES["imageUploadHash$valueImg"]["error"] as $key => $error) {
								    if ($error == UPLOAD_ERR_OK) {
								        $tmp_name = $_FILES["imageUploadHash$valueImg"]["tmp_name"][$key];
								        $name = $_FILES["imageUploadHash$valueImg"]["name"][$key];
								        if (!move_uploaded_file($tmp_name, "$Path/$name"))
								    		echo "error";
								    
								    }
								}
							}
					endif;
						
					// if()	
					// {	
						$rep= array_key_exists("dependanceLinks", $_POST) ? $pages->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["content"], $_POST["url"], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'], $_FILES["imageUpload"]["name"][0])
							: $pages->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["content"], $_POST["url"], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter'], $_FILES["imageUpload"]["name"][0]);
						$state = $rep != 0 ? "Page modifiée avec succès !" : "Erreur lors de la modification de la page !";			
					// }
					// else
					// {
						// $rep= array_key_exists("dependanceLinks", $_POST) ? $pages->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["content"], $_POST["url"], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'], null)
						// 	: $pages->updatePage($_POST["Modifier"], $_POST["title"], $_POST["name"], $_POST["content"], $_POST["url"], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter'], null);
						// $state = $rep != 0 ? "Modification de la page avec succès sans image." : "Erreur lors de la modification de la page !";
					// }
					if( array_key_exists("idTexte", $_POST) || array_key_exists("idImage", $_POST) || array_key_exists("idTextArea", $_POST)):
						/*print_r($_FILES);
						print_r($_POST);*/
						updateHashMap($pages, $_POST, $_FILES, $nameConv);
					endif;
					$tab=$pages->getAllPages();
					$donneesPages=array();
					$view->setFile("layout/pageview/pageSortable-top.html");
					$aff.=$view->render(array());
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
						$value['sortable']='true';
						$nameConvUrl = strtr($value["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');	
						$value['source']="images/".$nameConvUrl.'-'.$value['id'].'/'.$value['image'];
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
					$view->setFile("layout/pageview/pageSortable-bottom.html");
					$aff.=$view->render(array());
					$donneesPagesAjouter['buttontext']='Ajouter';
					$donneesPagesAjouter['buttonsuppstate']='disabled';
					$donneesPagesAjouter['required']='required';
					$donneesPagesAjouter['sortable']='false';
					$aff.=getPageView($view, $pages, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					unset($donneesPages[0]['state']);
				else:
					$nameConv = strtr($_POST["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					if($_FILES["imageUpload"])
					{
						$rep= array_key_exists("dependanceLinks", $_POST) ? $pages->insertNewPage($_POST["title"], $_POST["name"], $_POST["content"][0], $_POST['url'], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'], $_FILES["imageUpload"]["name"][0])
							: $pages->insertNewPage($_POST["title"], $_POST["name"], $_POST["content"][0], $_POST['url'], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter'], $_FILES["imageUpload"]["name"][0]);
						$state = $rep != 0 ? "Page ajoutée avec succès !" : "Erreur lors de l'ajout de la page !";
						
						$uploads_dir = "images/".$nameConv."-".$rep;
						//print_r($_FILES["imageUpload"]);
						$uploads_sdir=explode("/", $uploads_dir);
						if (!file_exists($uploads_sdir[0])) {
							if (!mkdir($uploads_sdir[0], 0777, true)) {
							   echo 'Echec lors de la création des répertoires...';
							}
						}
						elseif(!file_exists($uploads_dir))
						{
							if (!mkdir($uploads_dir, 0777, true)) {
							   echo 'Echec lors de la création des sous répertoires...';
							}
						}
						foreach ($_FILES["imageUpload"]["error"] as $key => $error) {
						    if ($error == UPLOAD_ERR_OK) {
						        $tmp_name = $_FILES["imageUpload"]["tmp_name"][$key];
						        $name = $_FILES["imageUpload"]["name"][$key];
						        if (!move_uploaded_file($tmp_name, "$uploads_dir/$name"))
						    		echo "error";
						    
						    }		    
						}
					}
					else
					{
						$rep= array_key_exists("dependanceLinks", $_POST) ? $pages->insertNewPage($_POST["title"], $_POST["name"], $_POST["content"], $_POST['url'], $_POST["published"], $_POST['dependanceLinks'], $_POST['dependanceHeader'], $_POST['dependanceFooter'], null)
							: $pages->insertNewPage($_POST["title"], $_POST["name"], $_POST["content"], $_POST['url'], $_POST["published"], null, $_POST['dependanceHeader'], $_POST['dependanceFooter'], null);
						$state = $rep != 0 ? "Page ajoutée avec succès mais sans image." : "Erreur lors de l'ajout de la page !";
					}
					    	
					$tab=$pages->getAllPages();
					$donneesPages=array();
					$view->setFile("layout/pageview/pageSortable-top.html");
					$aff.=$view->render(array());
					foreach ($tab as $key => $value) {
						if($key==0)
						{
							$value['state']=$state;
						}
						$value['buttontext']='Modifier';
						$value['sortable']='true';
						$nameConvUrl = strtr($value["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					
						$value['source']='images/'.$nameConvUrl.'-'.$value['id'].'/'.$value['image'];;
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
					$view->setFile("layout/pageview/pageSortable-bottom.html");
					$aff.=$view->render(array());
					$donneesPagesAjouter['buttontext']='Ajouter';
					$donneesPagesAjouter['buttonsuppstate']='disabled';
					$donneesPagesAjouter['required']='required';
					$donneesPagesAjouter['sortable']='false';
					$aff.=getPageView($view, $pages, $donneesPagesAjouter, $tabLinks, $tabHeaders, $tabFooters);
					// unset($donneesPages[0]['state']);
				endif;
				break;
			case 'header':
				$header=new Header("minicms");
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Header"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				$tabPages=$pages->getAllPages();
				if(array_key_exists("delete", $_POST)):
					$rep=$header->deleteHeader($_POST["delete"]);
					$tab=$header->getAllHeaders();
					$donneesHeader=array();
					if($tab)
					{
						$state = $rep != 0 ? "Header supprimé avec succès !" : "Erreur lors de la suppression du header !";
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
							$aff.=getHeaderView($view, $pages, $value, $tabPages);

						}
						$donneesHeader['buttontext']='Ajouter';
						$donneesHeader['buttonsuppstate']='disabled';
						$aff.=getHeaderView($view, $pages, $donneesHeader, $tabPages);
						unset($tab[0]['state']);
					}
					else
					{
						$donneesHeader['buttontext']='Ajouter';
						$donneesHeader['state']='Il ne reste plus aucun header !';
						$donneesHeader['buttonsuppstate']='disabled';
						$aff.=getHeaderView($view, $pages, $donneesHeader, $tabPages);
					}
				elseif(array_key_exists("Modifier", $_POST)):
					$rep= array_key_exists("dependancePages", $_POST) ? $header->updateHeader($_POST["Modifier"], $_POST["desc"], $_POST["logo"], $_POST["published"], $_POST['dependancePages'])
						: $header->updateHeader($_POST["Modifier"], $_POST["desc"], $_POST["logo"], $_POST["published"], null);
					$tab=$header->getAllHeaders();
					$state = $rep != 0 ? "Header modifié avec succès !" : "Erreur lors de la modification du header !";
					$donneesHeader=array();
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
						$aff.=getHeaderView($view, $pages, $value, $tabPages);
					
					}
					$donneesHeader['buttontext']='Ajouter';
					$donneesHeader['buttonsuppstate']='disabled';
					$aff.=getHeaderView($view, $pages, $donneesHeader, $tabPages);
				else:
					$rep= array_key_exists("dependancePages", $_POST) ? $header->insertHeader($_POST["desc"], $_POST["logo"], $_POST['published'], $_POST['dependancePages'])
						: $header->insertHeader($_POST["desc"], $_POST['logo'], $_POST["published"], null);
					
					$tab=$header->getAllHeaders();
					$state = $rep != 0 ? "Header ajouté avec succès !" : "Erreur lors de l'ajout du header !";
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
						$aff.=getHeaderView($view, $pages, $value, $tabPages);
					}
					$donneesPages['buttontext']='Ajouter';
					$donneesPages['buttonsuppstate']='disabled';
					$aff.=getHeaderView($view, $pages, $donneesPages, $tabPages);
				endif;
				break;
			case 'footer':
				$footer=new Footer("minicms");
				$blocs=array("include/header.html", "layout/aside.html");
				$donnees=array(array("title"=>"Administration des Footers"), $_SESSION, array());
				$aff.=$view->renderList($donnees, $blocs);
				$tabPages=$pages->getAllPages();
				if(array_key_exists("delete", $_POST)):
					$rep=$footer->deleteFooter($_POST["delete"]);
					$tab=$footer->getAllFooters();
					$donneesFooter=array();
					if($tab)
					{
						$state = $rep != 0 ? "Footer supprimé avec succès !" : "Erreur lors de la suppression du footer !";
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
							$aff.=getFooterView($view, $pages, $value, $tabPages);

						}
						$donneesFooter['buttontext']='Ajouter';
						$donneesFooter['buttonsuppstate']='disabled';
						$aff.=getFooterView($view, $pages, $donneesFooter, $tabPages);
						unset($tab[0]['state']);
					}
					else
					{
						$donneesFooter['buttontext']='Ajouter';
						$donneesFooter['state']='Il ne reste plus aucun footer !';
						$donneesFooter['buttonsuppstate']='disabled';
						$aff.=getFooterView($view, $pages, $donneesFooter, $tabPages);
					}
				elseif(array_key_exists("Modifier", $_POST)):
					$rep= array_key_exists("dependancePages", $_POST) ? $footer->updateFooter($_POST["Modifier"], $_POST["desc"], $_POST["logo"], $_POST["published"], $_POST['dependancePages'])
						: $footer->updateFooter($_POST["Modifier"], $_POST["desc"], $_POST["logo"], $_POST["published"], null);
					$tab=$footer->getAllFooters();
					$state = $rep != 0 ? "Footer modifié avec succès !" : "Erreur lors de la modification du footer !";
					$donneesFooter=array();
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
						$aff.=getFooterView($view, $pages, $value, $tabPages);
					
					}
					$donneesFooter['buttontext']='Ajouter';
					$donneesFooter['buttonsuppstate']='disabled';
					$aff.=getFooterView($view, $pages, $donneesFooter, $tabPages);
				else:
					$rep= array_key_exists("dependancePages", $_POST) ? $footer->insertFooter($_POST["desc"], $_POST["logo"], $_POST['published'], $_POST['dependancePages'])
						: $footer->insertFooter($_POST["desc"], $_POST['logo'], $_POST["published"], null);
					
					$tab=$footer->getAllFooters();
					$state = $rep != 0 ? "Footer ajouté avec succès !" : "Erreur lors de l'ajout du footer !";
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
						$aff.=getFooterView($view, $pages, $value, $tabPages);
					}
					$donneesPages['buttontext']='Ajouter';
					$donneesPages['buttonsuppstate']='disabled';
					$aff.=getFooterView($view, $pages, $donneesPages, $tabPages);
				endif;
				break;
			default:
				break;
		}
		echo $aff;
	else:
		$view = new View("include/head.html");
		$disp="";
		$donnees=array();
		// Vérification de l'utilisateur connecté
		if(isset($_SESSION['user'])){
			$donnees=array(array("title"=>"Portail Administration"), $_SESSION);
			//$disp = $view->render($donnees);
			$disp = $view->renderList($donnees, array("include/header.html"));
			$disp .= $view->renderListSameFile(array(array()), "layout/aside.html");
			$disp .= $view->renderListSameFile($_SESSION,"layout/accueil.html");
		}
		else
		{
			$donnees=array("title"=>"Connexion Partie Administration");
			$disp = $view->render($donnees);
			$disp .= $view->renderListSameFile(array(array()), "layout/connexion.html");
		}
		echo $disp;
	endif;
?>