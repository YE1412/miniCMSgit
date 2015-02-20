<?php
	include('module/class/View.class.php');
	$test1= array('login' =>  "Gerard");

	$view=new View("layout/test1.html");
	echo $view->render($test1);	
?>