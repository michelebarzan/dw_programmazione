<?php

	include "connessione.php";
	include "Session.php";
	
	$attivita_programmata=$_REQUEST['id_attivita_programmata'];
	$settimana=$_REQUEST['settimana'];
	$nCabine=$_REQUEST['nCabine'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$anno=$_REQUEST['anno'];
		
	$queryRighe="INSERT INTO [dbo].[programmazione_righe_attivita_programmate]
				([attivita_programmata],[settimana],[nCabine],[ponte],[firezone],[anno],[commessa])
				VALUES ($attivita_programmata,$settimana,$nCabine,'$ponte','$firezone',$anno,".$_SESSION['id_commessa'].")";
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