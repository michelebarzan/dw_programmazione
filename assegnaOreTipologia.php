<?php

	include "connessione.php";
	include "Session.php";
	
	$id_tipologie_attivita=$_REQUEST['id_tipologie_attivita'];
	$ore=$_REQUEST['ore'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="UPDATE programmazione_tipologie_attivita SET ore=$ore WHERE id_tipologie_attivita=$id_tipologie_attivita AND codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "ok";
	}
	
	
	
?>