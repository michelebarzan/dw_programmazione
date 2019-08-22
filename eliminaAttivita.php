<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	/*$queryRighe2="UPDATE Attivita SET eliminata='true' WHERE CodiceAttivita=$codice_attivita";
	$resultRighe2=sqlsrv_query($conn,$queryRighe2);
	if($resultRighe2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "ok";
	}*/
	
	/*$queryRighe="DELETE programmazione_attivita_commessa FROM programmazione_attivita_commessa WHERE codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryRighe2="DELETE Attivita FROM Attivita WHERE CodiceAttivita=$codice_attivita";
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
	}*/
	
	$queryRighe="DELETE programmazione_attivita_commessa FROM programmazione_attivita_commessa WHERE codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
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