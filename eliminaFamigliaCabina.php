<?php

	include "connessione.php";
	include "Session.php";
	
	$famiglia=$_REQUEST['famiglia'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="DELETE programmazione_tipologie_attivita FROM programmazione_tipologie_attivita WHERE famiglia='$famiglia' AND codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
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