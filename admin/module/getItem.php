<?php
	require("class/View.class.php");
	require("modelise.lib.php");
	if($_POST)
	{
		$view=new View("../include/newItemForm.html");
		echo getNewItemView($view, $_POST);	
	}
?>