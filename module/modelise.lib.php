<?php
	function getPageView($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/pageview/page-top.html");
		$retour.=$view->render($donneesPages);
		//print_r($donneesPages);
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
		
		$view->setFile("layout/pageview/page-bottom.html");
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
?>