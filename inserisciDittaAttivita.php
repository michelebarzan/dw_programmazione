<?php
	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	$id_ditta=$_REQUEST['id_ditta'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	
	if($ponte!="*" && $firezone!="*")
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_ditte_attivita]
					([codice_attivita]
					,[ponte]
					,[firezone]
					,[ditta]
					,[commessa])
					VALUES
					($codice_attivita,'$ponte','$firezone',$id_ditta,".$_SESSION['id_commessa'].")";
	}
	if($ponte=="*" && $firezone!="*")
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_ditte_attivita]
					([codice_attivita]
					,[ponte]
					,[firezone]
					,[ditta]
					,[commessa])
					SELECT DISTINCT $codice_attivita,[tip cab].Deck,'$firezone',$id_ditta,".$_SESSION['id_commessa']." FROM [tip cab]";
	}
	if($ponte!="*" && $firezone=="*")
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_ditte_attivita]
					([codice_attivita]
					,[ponte]
					,[firezone]
					,[ditta]
					,[commessa])
					SELECT DISTINCT $codice_attivita,'$ponte',[tip cab].FZ,$id_ditta,".$_SESSION['id_commessa']." FROM [tip cab]";
	}
	if($ponte=="*" && $firezone=="*")
	{
		$queryRighe="INSERT INTO [dbo].[programmazione_ditte_attivita]
					([codice_attivita]
					,[ponte]
					,[firezone]
					,[ditta]
					,[commessa])
					SELECT DISTINCT $codice_attivita,[tip cab].Deck,[tip cab].FZ,$id_ditta,".$_SESSION['id_commessa']." FROM [tip cab]";
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