<?php
	include('class/Db.class.php');
	include('class/Page.class.php');
	$ret=0;
	if($_POST['order'])
	{
		$page=new Page("minicms");
		foreach ($_POST['order'] as $key => $value) {
			$idPage=intval($value);
			$order=intval($key+1);
			$ret+=$page->updateOrder($idPage, $order);
		}
	}
	echo json_encode($ret);
?>