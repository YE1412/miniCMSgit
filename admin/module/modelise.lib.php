<?php
	function getPageView($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/pageview/page-top.html");
		$retour.=$view->render($donneesPages);
		//Boucle parcourant tous les liens de la base.
		$donneesLinks=array();
		foreach($tabLinks as $keyLink => $valueLink){
			if(array_key_exists("id", $donneesPages))
			{
				if($pages->linkIsInPage($valueLink["id"], $donneesPages["id"]))
				{
					$valueLink["selectedornot"]="selected";
				}
			}
			$donneesLinks[]=$valueLink;					
		}

		//Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($donneesLinks, "layout/pageview/dependanceLiens.html");

		$view->setFile("layout/pageview/page-middle1.html");
		$retour.=$view->render(array());
		//Boucle parcourant tous les headers de la base.
		$donneesHeaders=array();
		foreach($tabHeaders as $key => $valueHeader){
			if(array_key_exists("id", $donneesPages))
			{
				if($pages->headerIsInPage($valueHeader["id"], $donneesPages["id"]))
				{
					$valueHeader["selectedornot"]="selected";
				}
			}
			$donneesHeaders[]=$valueHeader;
		}
		// //Inclusion de la liste des header dans le détail de la page
		$retour.=$view->renderListSameFile($donneesHeaders, "layout/pageview/dependanceHeaderFooter.html");
		
		$view->setFile("layout/pageview/page-middle2.html");
		$retour.=$view->render(array());

		$donneesFooters=array();
		foreach($tabFooters as $key => $valueFooter){
			if(array_key_exists("id", $donneesPages))
			{
				if($pages->footerIsInPage($valueFooter["id"], $donneesPages["id"]))
				{
					$valueFooter["selectedornot"]="selected";
				}
			}
			$donneesFooters[]=$valueFooter;
		}
		//Inclusion de la liste des header dans le détail de la page
		$retour.=$view->renderListSameFile($donneesFooters, "layout/pageview/dependanceHeaderFooter.html");	
		$view->setFile("layout/pageview/page-middle3.html");
		$retour.=$view->render(array());
		
		//Inclusion du bas de la vue de la page
		$view->setFile("layout/pageview/page-bottom.html");
		$retour.=$view->render($donneesPages);
			

		/* Inclusion de la HashTable */
		if(array_key_exists("id", $donneesPages)):
			$pages->setIdPage($donneesPages["id"]);
			$arrayHashMap=$pages->getHashTable($pages->getIdPage());
			$retour.=getHashMapView($view, $arrayHashMap, $pages->getIdPage(), $donneesPages["name"]);
			
		endif;

		//Inclusion du bas de la vue de la page
		$view->setFile("layout/hash/hashMap-bottom.html");
		$retour.=$view->render($donneesPages);
		return $retour;					
	}

	function getLinkView($view, $page, $donneesLink, $tabPages)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/linkview/link-top.html");
		$retour.=$view->render($donneesLink);

		$donneesPages=array();
		foreach($tabPages as $keyPage => $valuePage){
			if(array_key_exists("id", $donneesLink))
			{
				if($page->linkIsInPage($donneesLink["id"], $valuePage["id"]))
				{
					$valuePage["selectedornot"]="selected";
				}
			}
			$donneesPages[]=$valuePage;					
		}
		//Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($donneesPages, "layout/linkview/dependancePages.html");

		$view->setFile("layout/linkview/link-bottom.html");
		$retour.=$view->render($donneesLink);
		//die(print_r($donneesLink));
		return $retour;
	}

	function getHeaderView($view, $page, $donneesHeader, $tabPages)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/headerview/header-top.html");
		$retour.=$view->render($donneesHeader);

		$donneesPages=array();
		foreach($tabPages as $keyPage => $valuePage){
			if(array_key_exists("id", $donneesHeader))
			{
				if($page->headerIsInPage($donneesHeader["id"], $valuePage["id"]))
				{
					$valuePage["selectedornot"]="selected";
				}
			}
			$donneesPages[]=$valuePage;					
		}
		//Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($donneesPages, "layout/linkview/dependancePages.html");

		$view->setFile("layout/headerview/header-bottom.html");
		$retour.=$view->render($donneesHeader);
		//die(print_r($donneesLink));
		return $retour;
	}

	function getFooterView($view, $page, $donneesFooter, $tabPages)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/footerview/footer-top.html");
		$retour.=$view->render($donneesFooter);

		$donneesPages=array();
		foreach($tabPages as $keyPage => $valuePage){
			if(array_key_exists("id", $donneesFooter))
			{
				if($page->footerIsInPage($donneesFooter["id"], $valuePage["id"]))
				{
					$valuePage["selectedornot"]="selected";
				}
			}
			$donneesPages[]=$valuePage;					
		}
		//Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($donneesPages, "layout/linkview/dependancePages.html");

		$view->setFile("layout/footerview/footer-bottom.html");
		$retour.=$view->render($donneesFooter);
		//die(print_r($donneesLink));
		return $retour;
	}
	function updateHashMap($pages, $donneesPage, $files, $namePageConv)
	{
		$hashTable=array();
		$idPage=$donneesPage['Modifier'];
		$i=0;
		if(array_key_exists("idTextArea", $donneesPage)):
			
			foreach($donneesPage['contentHash'] as $keyWiz => $valueWizHashMap) {			
				$hashTable[$i]["item"]["value"]=$valueWizHashMap;
				$hashTable[$i]["item"]["order"]=$donneesPage['orderWiz'][$keyWiz];
				$hashTable[$i]["item"]["idPage"]=$idPage;	
				$hashTable[$i]["item"]["type"]="Wysiwyg";	
				$hashTable[$i]["item"]["id"]=$donneesPage['idTextArea'][$keyWiz];			
				$i++;	
			}
		endif;
		if(array_key_exists("idTexte", $donneesPage)):
			foreach($donneesPage['strong'] as $keyText => $valueTextHashMap) {			
				$hashTable[$i]["item"]["value"]=$valueTextHashMap;
				$hashTable[$i]["item"]["order"]=$donneesPage['orderTexte'][$keyText];
				$hashTable[$i]["item"]["idPage"]=$idPage;
				$hashTable[$i]["item"]["type"]="Texte";			
				$hashTable[$i]["item"]["id"]=$donneesPage['idTexte'][$keyText];			
				$i++;
			}	
		endif;
		if(array_key_exists("idImage", $donneesPage)):
			foreach ($donneesPage["idImage"] as $keySlide => $valueSlide) {
				foreach($files["imageUploadHash$valueSlide"]["name"] as $keyImage => $valueImage) {			
				 	$hashTable[$i]["item"]["value"][$keyImage]=$valueImage;
				}
				$hashTable[$i]["item"]["order"]=$donneesPage['orderSlide'][$keySlide];
				$hashTable[$i]["item"]["idPage"]=$idPage;
				$hashTable[$i]["item"]["nom"]=$namePageConv;	
				$hashTable[$i]["item"]["type"]="Slideshow";
				$hashTable[$i]["item"]["id"]=$valueSlide;
				$i++;
			}		
		endif;
		//print_r($files);
		if($hashTable){
			$pages->updateHashTable($idPage, $hashTable);
		}
	}

	function getHashMapView($view, $donneesHashMap, $idPage, $namePage)
	{
		$retour='';
		if($donneesHashMap){
			$donneesHashMapPourDisp=array();
			foreach ($donneesHashMap as $key => $value) {
				switch ($value["item"]["type"]){
					case 'Slideshow':
						$nameConv = strtr($namePage,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
						$donneesHashMapPourDisp["item"]["src"]=$value["item"]["value"][0];
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$donneesHashMapPourDisp["item"]["idPage"]=$idPage;
						$donneesHashMapPourDisp["item"]["id"]=$value["item"]["id"];
						$donneesHashMapPourDisp["item"]["nom"]=$nameConv;
						$view->setFile("layout/hash/image.html");
						$retour.=$view->render($donneesHashMapPourDisp);
						break;
					case 'Texte':
						$donneesHashMapPourDisp["item"]["value"]=$value["item"]["value"];
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$donneesHashMapPourDisp["item"]["idPage"]=$idPage;
						$donneesHashMapPourDisp["item"]["id"]=$value["item"]["id"];
						$view->setFile("layout/hash/texte.html");
						$retour.=$view->render($donneesHashMapPourDisp);
						break;
					case 'Wysiwyg':
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$donneesHashMapPourDisp["item"]["content"]=$value["item"]["value"];
						$donneesHashMapPourDisp["item"]["idPage"]=$idPage;
						$donneesHashMapPourDisp["item"]["id"]=$value["item"]["id"];
						$view->setFile("layout/hash/textarea.html");
						$retour.=$view->render($donneesHashMapPourDisp);
						break;
					default:
						break;
				}
			}
		}
		return $retour;
	}

	function getNewItemView($view, $donneesItemForm)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$retour.=$view->render($donneesItemForm);
		return $retour;
	}
?>