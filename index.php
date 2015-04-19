<?php
	include('admin/module/class/Db.class.php');
	include('admin/module/class/View.class.php');
	include('admin/module/class/Page.class.php');
	include('module/modelise.lib.php');

	//print_r($_SERVER);
	$numChar=strspn($_SERVER["REQUEST_URI"], $_SERVER["SCRIPT_NAME"]);
	$Char=substr($_SERVER["SCRIPT_NAME"], 0, $numChar);
	//echo $Char;
	if($numChar>0)
	{
		$pageUrl=str_ireplace($Char, "", $_SERVER["REQUEST_URI"]);
		$pageAAtteindre=explode("/", $pageUrl);
		//echo $pageUrl;
		if(sizeof($pageAAtteindre)>0)
			$pageAAtteindre=$pageAAtteindre[sizeof($pageAAtteindre)-1];
		else
			$pageAAtteindre=$pageUrl;
		//echo $pageAAtteindre;
		$view = new View("include/head.html");
		$page=new Page("minicms");
		$aff="";
		//Contenu html affiché.
		$tab=$page->getAllPagesOnline();
		if($tab)
		{
		 	foreach ($tab as $key => $value){
				if($value["url"]==$pageAAtteindre)
				{
					$aff.=$view->render(array("title" => $value['title']));
		 			$aff.=getPageView($view, $value['id'], $tab);
				}
			}
		}
		echo $aff;
	}
?>