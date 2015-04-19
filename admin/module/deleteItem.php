<?php
	require("class/Db.class.php");
	require("class/Page.class.php");
	if($_POST)
	{
		$page=new Page("minicms");
		$uploads_dir = "../images/".$_POST['namePage']."-".$_POST['idPage'];
		$uploads_dir_hash=$uploads_dir."/".$_POST['idPage']."-".$_POST['idItem'];
		// if(file_exists($uploads_dir) && is_dir($uploads_dir)){
			if (file_exists($uploads_dir_hash) && is_dir($uploads_dir_hash)){
				$fichiers=scandir($uploads_dir_hash);
				if($fichiers){
					foreach ($fichiers as $keyFolder => $value) {
						$delete = is_file("$uploads_dir_hash/$value") ? unlink("$uploads_dir_hash/$value") : false;
					}	
				}
				$delete = rmdir($uploads_dir_hash);
			}
		// }
		$ret=$page->deleteItemHashTable($_POST['idPage'], $_POST['idItem']);		
		echo json_encode($ret);
	}
?>