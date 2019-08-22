<?php

	include "connessione.php";
	include "Session.php";
	
	$id_attivita_programmata=$_REQUEST['id_attivita_programmata'];
	
	$queryRighe="SELECT * FROM programmazione_attivita_programmate WHERE commessa=".$_SESSION['id_commessa']." AND id_attivita_programmata=$id_attivita_programmata ORDER BY id_attivita_programmata DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowRighe=sqlsrv_fetch_array($resultRighe))
		{
			echo $rowRighe['codiceAttivita']."|";
			echo $rowRighe['descrizione']."|";
			echo $rowRighe['kit/pref']."|";
			echo $rowRighe['colore']."|";
			echo $rowRighe['dashType']."|";
			echo $rowRighe['note'];
		}
	}

?>