<?php

	include "connessione.php";
	include "Session.php";
	
	$id_ditte_attivita=$_REQUEST['id_ditte_attivita'];
	
	$queryRighe="DELETE programmazione_ditte_attivita FROM programmazione_ditte_attivita WHERE id_ditte_attivita=$id_ditte_attivita";
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