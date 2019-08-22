<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="SELECT * FROM programmazione_attivita WHERE codice_attivita=$codice_attivita";
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
			echo $rowRighe['marina/arredo']."|";
			echo $rowRighe['Descrizione']."|";
			echo $rowRighe['kit/pref']."|";
			echo $rowRighe['colore']."|";
			echo $rowRighe['dashType']."|";
			echo $rowRighe['note'];
		}
	}

?>