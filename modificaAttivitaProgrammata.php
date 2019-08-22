<?php

	include "connessione.php";
	include "Session.php";
	
	$id_attivita_programmata=$_REQUEST['id_attivita_programmata'];
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
	
	$queryRighe="UPDATE programmazione_attivita_programmate SET codiceAttivita='$codice', descrizione='$descrizione', [kit/pref]='$kitpref', note='$note',colore='$colore',dashType='$dashType' WHERE id_attivita_programmata=$id_attivita_programmata";
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