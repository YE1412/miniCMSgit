<?php
	session_start();
	include('include/head.html');
	include('module/class/View.class.php');
	include('module/class/User.class.php');

	$view=new View("layout/connexion.html");
	if(isset($userConnecter)) {
		include('include/header.html');
		# code...
	}else{
		echo $view->render(array());	
	}
	
?>