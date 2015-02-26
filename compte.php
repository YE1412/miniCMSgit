<?php
	session_start();
	include('module/class/Db.class.php');
	include('module/class/View.class.php');
	include('module/class/User.class.php');
	
	$disp="";
	// Deconnexion
	if(isset($_GET['action']) && $_GET['action'] == "logout")
	{
		$user = new User('minicms');
		$user->logout();
	}

	if(isset($_SESSION["user"]))
	{
		$user = new User('minicms');
		$view = new View("include/head.html");
		$donnees=array(array("title"=>"Administration du Compte"), $_SESSION, array());
		$disp.=$view->renderList($donnees, array("include/header.html", "layout/aside.html"));
		//die(var_dump($disp));
	}
	else
	{
		header('Location: index.php');
	}

	if(isset($_POST) && $_POST) :
		if(array_key_exists("modif", $_POST))
		{
			$datasToUpdate=array($_POST['login'], $_POST['pass'], $_POST['mail']);
			$rep=$user->updateUser($datasToUpdate[0], $datasToUpdate[1], $datasToUpdate[2]);
			$newUserInfo=$user->login($datasToUpdate[0], $datasToUpdate[1]);
			$_SESSION['user']= $rep != 0 ? $newUserInfo[0] : $_SESSION['user'];
			$_SESSION['action']['state'] = $rep != 0 ? "Votre compte à bien été mis à jour !" : "Erreur lors de la mise à jour de votre compte !";
			$donnees=array($_SESSION);
			$disp.=$view->renderListSameFile($donnees, "layout/compte.html");
			unset($_SESSION['action']);
		}
		else
		{

		}
	else:
		$donnees=array($_SESSION);
		$disp.=$view->renderListSameFile($donnees, "layout/compte.html");
	endif;
	echo $disp; 
?>