<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="DELETE programmazione_tipologie_attivita FROM programmazione_tipologie_attivita WHERE codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryRighe2="DELETE programmazione_assegnazioni_specifiche FROM programmazione_assegnazioni_specifiche WHERE attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
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