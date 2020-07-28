<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="SELECT distinct [Famiglia] FROM [tip cab] WHERE [Famiglia] NOT IN (SELECT DISTINCT famiglia FROM programmazione_tipologie_attivita WHERE codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa'].") AND (commessa = ".$_SESSION['id_commessa'].") ORDER BY [Famiglia] DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<select class='modalSelect' id='famigliaModal'>";
			echo "<option value='' disabled selected>Famiglia</option>";
			echo "<option value='*'>Tutte</option>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<option value='".$rowRighe['Famiglia']."'>".$rowRighe['Famiglia']."</option>";
			}
		echo "</select>";
	}
?>