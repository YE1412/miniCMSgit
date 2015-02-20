<?php
	session_start();
	include('include/head.html');
	include('module/class/Db.class.php');
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
	else:
		echo $view->render(array());
	endif;
	$test = new Db('minicms');
	var_dump( $test->select('users'));
?>
