<?php

	include "connessione.php";
	include "Session.php";
	
	set_time_limit(240);
	
	$cabineOk=$_REQUEST['cabineOk'];
	$cabineKo=$_REQUEST['cabineKo'];
	$cabineTutte=$_REQUEST['cabineTutte'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	$cabineOkArray=explode(",",$cabineOk);
	$cabineKoArray=explode(",",$cabineKo);
	
	$queryRighe0="DELETE [dbo].[programmazione_assegnazioni_specifiche] FROM [dbo].[programmazione_assegnazioni_specifiche] WHERE attivita=$codice_attivita AND commessa=".$_SESSION['id_commessa']." AND numero_cabina IN ($cabineTutte)";
	$resultRighe0=sqlsrv_query($conn,$queryRighe0);
	if($resultRighe0==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe0."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	
	foreach ($cabineOkArray as $numero_cabinaOk) 
	{
		if($numero_cabinaOk!='')
		{
			$queryRighe="INSERT INTO [dbo].[programmazione_assegnazioni_specifiche]
			   ([numero_cabina]
			   ,[attivita]
			   ,[commessa]
			   ,[val]) VALUES ('$numero_cabinaOk',$codice_attivita,".$_SESSION['id_commessa'].",1)";
			$queryRighe=rtrim($queryRighe,',');
			$resultRighe=sqlsrv_query($conn,$queryRighe);
			if($resultRighe==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
		}
	}
	foreach ($cabineKoArray as $numero_cabinaKo) 
	{
		if($numero_cabinaKo!='')
		{
			$queryRighe2="INSERT INTO [dbo].[programmazione_assegnazioni_specifiche]
			   ([numero_cabina]
			   ,[attivita]
			   ,[commessa]
			   ,[val]) VALUES ('$numero_cabinaKo',$codice_attivita,".$_SESSION['id_commessa'].",-1)";
			$queryRighe2=rtrim($queryRighe2,',');
			$resultRighe2=sqlsrv_query($conn,$queryRighe2);
			if($resultRighe2==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe2."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
		}
	}
	echo "ok";
?>