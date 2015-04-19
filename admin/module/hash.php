<?php
require("class/Db.class.php");
require("class/Page.class.php");
if(isset($_POST) && isset($_POST['typeItem'])):
	//print_r($_POST);
	$page=new Page("minicms");
	$arrayHash=array();
	switch($_POST['typeItem']){
		case '0':
			$arrayHash['item']['type']="Texte";
			$arrayHash['item']['value']=$_POST["contentItem"];
			$arrayHash['item']['order']=$_POST["order"];
			$arrayHash['item']['id']=$_POST["id"];
			break;
		case '1':
			$arrayHash['item']['type']="Wysiwyg";
			$arrayHash['item']['value']=$_POST["contentItem"];
			$arrayHash['item']['order']=$_POST["order"];
			$arrayHash['item']['id']=$_POST["id"];
			break;
		case '2':
			$arrayHash['item']['type']="Slideshow";
			$arrayHash['item']['order']=$_POST["order"];
			$arrayHash['item']['id']=$_POST["id"];
			if($_FILES["contentItem"])
			{
			 	$uploads_dir = "../images/".$_POST['nomPage']."-".$_POST['idPage'];
				$arrayDitectories=explode("/", $uploads_dir);
				$newDitectory=$_POST['idPage']."-".$_POST['id'];
				$newPath=$uploads_dir."/".$newDitectory;
				if(!file_exists($uploads_dir)){
					if (!mkdir($uploads_dir, 0777, true)){
			 	 		echo 'Echec lors de la création du répertoire principal...';
			 		}
				}
				elseif(file_exists($uploads_dir) && is_dir($uploads_dir))
				{
					if (!file_exists($newPath)){
			 	 		if (!mkdir($uploads_dir."/".$newDitectory, 0777, true)){
			 	 			echo 'Echec lors de la création du répertoire seconddaire...';
			 			}
			 		}
			 		else{
			 			$fichiers=scandir($newPath);
						if($fichiers){
							foreach ($fichiers as $keyFolder => $value) {
								$delete = is_file("$newPath/$value") ? unlink("$newPath/$value") : false;
							}	
						}
					}
				}
				
				$content=array();
				foreach ($_FILES["contentItem"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {
						$tmp_name = $_FILES["contentItem"]["tmp_name"][$key];
						$name = $_FILES["contentItem"]["name"][$key];
						if (!move_uploaded_file($tmp_name, "$newPath/$name"))
							echo "error";	
						$content[]=$name;   
					}		    
				}
				$arrayHash['item']['value']=$content;
			}
			break;
		default:
			break;
	}
	$page->addItemHashTable($_POST['idPage'], $arrayHash);
	header("Location: ../index.php");
else:
	echo "POST EMPTY !";
endif;
?>