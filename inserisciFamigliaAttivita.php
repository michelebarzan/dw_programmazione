<?php
	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	$famiglia=$_REQUEST['famiglia'];
	$ore=$_REQUEST['ore'];
	if($ore=='' || $ore==null)
		$ore=0;
	
	if($famiglia!="*")
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_tipologie_attivita]
			   ([codice_attivita]
			   ,[commessa]
			   ,[tipologia]
			   ,[famiglia]
			   ,[ore])
		  SELECT DISTINCT $codice_attivita,".$_SESSION['id_commessa'].",[tip cab].[Pax/Crew],'$famiglia',$ore FROM [tip cab] WHERE Famiglia='$famiglia' and commessa=".$_SESSION['id_commessa'];
	}
	else
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_tipologie_attivita]
			   ([codice_attivita]
			   ,[commessa]
			   ,[tipologia]
			   ,[famiglia]
			   ,[ore])
		  SELECT DISTINCT $codice_attivita,".$_SESSION['id_commessa'].",[tip cab].[Pax/Crew],[tip cab].[Famiglia],$ore FROM [tip cab] WHERE Famiglia NOT IN (select DISTINCT famiglia from programmazione_tipologie_attivita WHERE codice_attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa'].") and commessa=".$_SESSION['id_commessa'];
	}
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