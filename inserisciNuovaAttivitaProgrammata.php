<?php

	include "connessione.php";
	include "Session.php";
	
	$codice=$_REQUEST['codice'];
	$descrizione=$_REQUEST['descrizione'];
	$kitpref=$_REQUEST['kitpref'];
	$note=$_REQUEST['note'];
	$colore=$_REQUEST['colore'];
	$dashType=$_REQUEST['dashType'];
	
	if($colore==null || $colore=='')
		$colore="A0A0A0";
	if($dashType==null || $dashType=='')
		$dashType="solid";
	
	$queryRighe="INSERT INTO [dbo].[programmazione_attivita_programmate]
           ([codiceAttivita]
           ,[descrizione]
           ,[note]
           ,[commessa]
           ,[kit/pref],
		   [colore],
		   [dashType])
			VALUES ('$codice','$descrizione','$note',".$_SESSION['id_commessa'].",'$kitpref','$colore','$dashType')";
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