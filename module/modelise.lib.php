<?php
	function getPageView($view, $pageId, $tabPagesOnlines)
	{
		$retour='';
		//$retour=array();
		$page=new Page("minicms");
		$page->setIdPage($pageId);
		//Inclusion du header
		//$retour.=getHeaderView($view, $page, $tabPagesOnlines);
		//Inclusion de la page
		$donneesPage=$page->getPage();
		/*$view->setFile("include/pageview/page.html");
		$nameConvUrl = strtr($donneesPage[0]["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		
		$retour.=$view->render(array("contenu" => $donneesPage[0]["contenu"], "name"=> $nameConvUrl, "title" => $donneesPage[0]["title"], "image" => $donneesPage[0]["image"], "id" => $pageId));*/
		
		$retour.=getSliderView($view, $page);
		$retour.=getNavigationBar($view, $page);
		$retour.=getContent($view, $page, $donneesPage);
		
		$arrayHashMap=$page->getHashTable($page->getIdPage());
		$retour.=getHashMapView($view, $arrayHashMap, $page->getIdPage(), $donneesPage[0]["name"]);

		//Inclusion du footer
		$retour.=getFooterViewSimple($view, $page);
		return $retour;						
	}

	function getHeaderView($view, $page, $tabPagesOnlines)
	{
		//$retour=$page->getHeader();
		$retour='';
		$donneesHeader=$page->getHeader();
		$tabPagesOnlines=$page->getAllPagesOnline();
		// //Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("include/headerview/header-top.html");
		$retour.=$view->render($tabPagesOnlines[0]);

		// $donneesHeader=array();
		
		// //Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($tabPagesOnlines, "include/headerview/listPages.html");
		$view->setFile("include/headerview/header-bottom.html");
		$retour.=$view->render(array("header"=> array("contenu"=> $donneesHeader[0]["contenu"], "logo"=> $donneesHeader[0]["logo"])));
		// $view->setFile("layout/headerview/header-bottom.html");
		// $retour.=$view->render($donneesHeader);
		// //die(print_r($donneesLink));
		return $retour;
	}

	function getNavigationBar($view, $page)
	{
		//$retour=$page->getHeader();
		$retour='';
		$tabPagesOnlines=$page->getAllPagesOnline();
		// //Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("include/headerview/navbar-top.html");
		$retour.=$view->render(array());

		// $donneesHeader=array();
		
		// //Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($tabPagesOnlines, "include/headerview/navbar-content.html");
		$view->setFile("include/headerview/navbar-bottom.html");
		// $view->setFile("layout/headerview/header-bottom.html");
		 $retour.=$view->render(array());
		// //die(print_r($donneesLink));
		return $retour;
	}

	function getContent($view, $page, $donneesPage)
	{
		$retour="";
		$donneesContent=array();
		$view->setFile("include/pageview/content.html");
		$donneesContent["id"]=1;
		$donneesContent["page"]["nom"]=$donneesPage[0]["name"];
		$donneesContent["page"]["contenu"]=htmlspecialchars($donneesPage[0]["contenu"]);
		$retour.=$view->render($donneesContent);
		return $retour;
	}

	function getFooterView($view, $page, $tabPagesOnlines)
	{
		$retour='';
		$donneesFooter=$page->getFooter();
		$tabPagesOnlines=$page->getAllPagesOnline();
		// //Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("include/footerview/footer-top.html");
		$retour.=$view->render(array("footer"=> array("contenu"=> $donneesFooter[0]["contenu"], "logo"=> $donneesFooter[0]["logo"])));

		// $donneesHeader=array();
		
		// //Inclusion de la liste des liens dans le détail de la page
		$retour.=$view->renderListSameFile($tabPagesOnlines, "include/headerview/listPages.html");
		$view->setFile("include/footerview/footer-bottom.html");
		$retour.=$view->render(array());
		// $view->setFile("layout/headerview/header-bottom.html");
		// $retour.=$view->render($donneesHeader);
		// //die(print_r($donneesLink));
		return $retour;
	}

	function getFooterViewSimple($view, $page)
	{
		$retour='';
		$donneesFooter=$page->getFooter();
		// //Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("include/footerview/footerview.html");
		$retour.=$view->render($donneesFooter[0]);
		return $retour;
	}

	function getLinksView($view, $page, $donneesLink, $tabPages)
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

	function getSliderView($view, $page)
	{
		$retour='';
		$donneesHeader=$page->getHeader();
		$donneesPage=$page->getPage();
		$nameConvUrl = strtr($donneesPage[0]["name"],'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
					
		$dirname = 'admin/images/'.$nameConvUrl.'-'.$donneesPage[0]["id"].'/';
		$imageDir=$nameConvUrl.'-'.$donneesPage[0]["id"];
		$tabImages=array();
		if(file_exists($dirname))
		{
			$dir = opendir($dirname);
			$i=0;
			while($file = readdir($dir)) {
				if((!is_dir($dirname.$file)) && ($file != '.') && ($file != '..'))
				{
					$tabImages['imagePath'][]=$dirname.$file;
				}
				$i++;
			}	
			$i;
			//print_r($tabImages);
			closedir($dir);
		} 
		$donneesImages=array();
		$donneesIndicators=array();
		if($tabImages)
		{
			/* FOR THE CAROUSEL */
			foreach ($tabImages['imagePath'] as $key => $valueImage) {
				switch($key){
					case 0:
						$donneeItem['active']='active';
						$donneeItem['imagePath']=$valueImage;
						$donneeCarIndicators['id']=$key;
						$donneeCarIndicators['active']='active';

						break;
					
					default:
						$donneeItem['active']='';	
						$donneeItem['imagePath']=$valueImage;
						$donneeCarIndicators['id']=$key;
						$donneeCarIndicators['active']='';
						break;
				}
				$donneesImages[]=$donneeItem;
				$donneesIndicators[]=$donneeCarIndicators;
			}
			// foreach ($tabImages['imagePath'] as $key => $valueImage) {
			// 	switch($key){
			// 		case 0:
			// 			$donnee['active']='active';
			// 			$donnee['imagePath']=$valueImage;
			// 			break;
					
			// 		default:
			// 			$donnee['active']='';	
			// 			$donnee['imagePath']=$valueImage;
			// 			break;
			// 	}
			// 	$donneesImages[]=$donnee;
			// }
			//print_r($donneesImages);
			//Inclusion du haut d'un élément contenant le détail d'une page
			// $view->setFile("include/sliderview/slider-top.html");
			// $retour.=$view->render(array());
			$view->setFile("include/sliderview/carousel-top.html");
			$retour.=$view->render(array());

			//Inclusion de la liste des liens dans le détail de la page
			// $retour.=$view->renderListSameFile($donneesImages, "include/sliderview/sliderImages.html");
			$retour.=$view->renderListSameFile($donneesIndicators, "include/sliderview/carousel-indicators.html");

			$view->setFile("include/sliderview/carousel-middle.html");
			$retour.=$view->render(array());

			$retour.=$view->renderListSameFile($donneesImages, "include/sliderview/carousel-items.html");

			$view->setFile("include/sliderview/carousel-controls.html");
			$retour.=$view->render($donneesHeader[0]);

			$view->setFile("include/sliderview/carousel-bottom.html");
			$retour.=$view->render(array());
			// $view->setFile("include/sliderview/slider-middle.html");
			// $retour.=$view->render(array());
			// $view->setFile("include/sliderview/sliderNavButtons.html");
			// for ($i=0; $i<sizeof($donneesImages); $i++) {
			// 	$retour.=$view->render(array());
			// }
			// $view->setFile("include/sliderview/slider-bottom.html");
			// $retour.=$view->render(array());
			//die(print_r($donneesLink));
		}
		return $retour;
	}

	function getHashMapView($view, $donneesHashMap, $idPage, $namePage)
	{
		$retour='';
		if($donneesHashMap){
			$donneesHashMapPourDisp=array();
			// $view->setFile("include/hash/hash-top.html");
			// $retour.=$view->render(array());
			foreach ($donneesHashMap as $key => $value) {
				switch ($value["item"]["type"]){
					case 'Slideshow':
						$donneesImages=array();
						$nameConv = strtr($namePage,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
												'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
						$donneesImages=getImagePathsDatas($value["item"]["value"], $namePage, $value["item"]["order"], $idPage);
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$view->setFile("include/hash/sliderview/slider-top.html");
						$retour.=$view->render($donneesHashMapPourDisp["item"]);

						//Inclusion de la liste des liens dans le détail de la page
						$retour.=$view->renderListSameFile($donneesImages, "include/hash/sliderview/sliderImages.html");

						$view->setFile("include/hash/sliderview/slider-bottom.html");
						$retour.=$view->render(array());
						break;
					case 'Texte':
						$donneesHashMapPourDisp["item"]["name"]=$value["item"]["value"];
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$view->setFile("include/hash/textview/textview.html");
						$retour.=$view->render($donneesHashMapPourDisp["item"]);
						break;
					case 'Wysiwyg':
						$donneesHashMapPourDisp["item"]["content"]=$value["item"]["value"];
						$donneesHashMapPourDisp["item"]["order"]=$value["item"]["order"];
						$view->setFile("include/hash/contentview/contentview.html");
						$retour.=$view->render($donneesHashMapPourDisp["item"]);
						break;
					default:
						break;
				}
			}
			$view->setFile("include/hash/hash-bottom.html");
			$retour.=$view->render(array());
		}
		return $retour;
	}
	//Fonction retournant un tableau contenan le chemin de toutes
	//les image de la Hastable
	function getImagePathsDatas($tabImages, $namePage=null, $orderItem, $idPage){
		foreach ($tabImages as $key => $valueImage) {
			$donnee=array();
			$donnee['imagePath']="admin/images/$namePage-$idPage/$idPage-$orderItem/$valueImage";
			$datas[]=$donnee;
		}
		return $datas;
	}
	
?>	