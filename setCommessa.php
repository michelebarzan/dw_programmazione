<?php
	include "connessione.php";
	include "Session.php";
	
	$commessa=$_REQUEST['commessa'];
	$id_commessa=$_REQUEST['id_commessa'];
	
	$hour = time() + 3600 * 24 * 30;
	setcookie('id_commessa', $id_commessa, $hour);
	setcookie('commessa', $commessa, $hour);
	$_SESSION['id_commessa']=$id_commessa;
	$_SESSION['commessa']=$commessa;
?>