<?php
	function getPage($view, $pages, $donneesPages, $tabLinks, $tabHeaders, $tabFooters)
	{
		$retour='';
		//Inclusion du haut d'un élément contenant le détail d'une page
		$view->setFile("layout/page-top.html");
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
		$retour.=$view->renderListSameFile($donneesLinks, "layout/dependanceLiens.html");

		$view->setFile("layout/page-middle1.html");
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
		$retour.=$view->renderListSameFile($donneesHeaders, "layout/dependanceHeaderFooter.html");
		
		$view->setFile("layout/page-middle2.html");
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
		// //Inclusion de la liste des header dans le détail de la page
		$retour.=$view->renderListSameFile($donneesFooters, "layout/dependanceHeaderFooter.html");
		
		$view->setFile("layout/page-bottom.html");
		$retour.=$view->render($donneesPages);

		return $retour;
							
	}
?>