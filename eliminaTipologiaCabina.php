<?php

	include "connessione.php";
	include "Session.php";
	
	$id_tipologie_attivita=$_REQUEST['id_tipologie_attivita'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	/*$query2="DELETE programmazione_ditte_attivita FROM programmazione_ditte_attivita WHERE codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa']." AND tipologia=(SELECT TOP (1) tipologia FROM programmazione_tipologie_attivita WHERE id_tipologie_attivita=$id_tipologie_attivita)";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{*/
		$queryRighe="DELETE programmazione_tipologie_attivita FROM programmazione_tipologie_attivita WHERE id_tipologie_attivita=$id_tipologie_attivita AND codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'];
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
	//}
	
?>