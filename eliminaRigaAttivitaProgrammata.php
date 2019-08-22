<?php

	include "connessione.php";
	include "Session.php";
	
	$id_cabina_coinvolta=$_REQUEST['id_cabina_coinvolta'];
	
	$queryRighe="DELETE programmazione_righe_attivita_programmate FROM programmazione_righe_attivita_programmate WHERE id_cabina_coinvolta=$id_cabina_coinvolta";
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