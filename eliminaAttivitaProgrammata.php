<?php

	include "connessione.php";
	include "Session.php";
	
	$id_attivita_programmata=$_REQUEST['id_attivita_programmata'];
	
	$queryRighe="DELETE programmazione_righe_attivita_programmate FROM programmazione_righe_attivita_programmate WHERE attivita_programmata=$id_attivita_programmata";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryRighe2="DELETE programmazione_attivita_programmate FROM programmazione_attivita_programmate WHERE id_attivita_programmata=$id_attivita_programmata";
		$resultRighe2=sqlsrv_query($conn,$queryRighe2);
		if($resultRighe2==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			echo "ok";
		}
	}
	
	
	
?>