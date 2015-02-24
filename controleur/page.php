<?php
	session_start();
	include('../module/class/Db.class.php');
	include('../module/class/View.class.php');
	include('../module/class/Page.class.php');

	if(isset($_POST['action'])) :
		switch ($_POST['action']) {
			case 'menu':
				//var_dump($_POST);
				if(array_key_exists("pages", $_POST)){
					$pages=new Page("minicms");
					$tab=$pages->getAllPages();
					//var_dump($tab);

					$view = new View("../include/head.html");
					$donnees=array(array("title"=>"Administration des pages"), $_SESSION, array());
					$view->renderList($donnees, array("../include/header.html", "../layout/aside.html"));
					foreach ($tab as $key => $value) {
						//var_dump($value);
						/*switch($value['published'])
						{
							case 0:
								$value['published']='Non';
								break;
							case 1:
								$value['published']='Oui';
								break;
							default:
								break;
						}*/
						array_push($donnees, $value);
						echo $view->renderList($donnees, array("../layout/page.html"));
					}
				}
				else{

				}
				break;
			default:
				break;
		}
	else:
		$view = new View("../include/head.html");
		$donnees=array(array("title"=>"Connexion Partie Administration"), array());
		echo $view->renderList($donnees, array("../layout/connexion.html"));
	endif;
?>