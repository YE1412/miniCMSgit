<?php
	session_start();
	include('include/head.html');
	include('module/class/View.class.php');
	include('module/class/User.class.php');

	$view=new View("layout/connexion.html");
	if(isset($_POST['action'])) :
		switch ($_POST['action']) {
			case 'connexion':
				$view=new View("layout/accueil.html");
				break;
			
			default:
				$view=new View("layout/accueil.html");
				break;
		}
		include('include/header.html');
		# code...
	else:
		echo $view->render(array());	
	endif;
	
?>