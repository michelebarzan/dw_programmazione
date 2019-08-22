<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	$ore=$_REQUEST['ore'];
	$famiglia=$_REQUEST['famiglia'];
	$tipologia=$_REQUEST['tipologia'];
	if($ore=='' || $ore==null)
		$ore=0;
	
	$queryRighe="INSERT INTO [dbo].[programmazione_tipologie_attivita]
           ([codice_attivita]
           ,[commessa]
           ,[tipologia]
           ,[famiglia]
           ,[ore])
      VALUES($codice_attivita,".$_SESSION['id_commessa'].",'$tipologia','$famiglia',$ore)";
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