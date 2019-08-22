<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	$famiglia=$_REQUEST['famiglia'];
	
	$queryRighe="SELECT distinct [Pax/Crew] FROM [tip cab] WHERE Famiglia='$famiglia' AND [Pax/Crew] NOT IN (SELECT DISTINCT tipologia FROM programmazione_tipologie_attivita WHERE codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa'].") ORDER BY [Pax/Crew] DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<select class='modalSelect' id='tipologiaModal'>";
			echo "<option value='' disabled selected>Tipologia</option>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<option value='".$rowRighe['Pax/Crew']."'>".$rowRighe['Pax/Crew']."</option>";
			}
		echo "</select>";
	}
?>