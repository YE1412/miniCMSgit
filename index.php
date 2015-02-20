<?php
	session_start();
	include('include/head.html');
	include('include/header.html');
	include('module/class/View.class.php');
	include('module/class/User.class.php');

	$view=new View("layout/connexion.html");
	if(isset($userConnecter)) {
		# code...
	}else{
		echo $view->render(array());	
	}
	
?>