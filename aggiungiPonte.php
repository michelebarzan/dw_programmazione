<?php

	include "connessione.php";
	include "Session.php";
	
	$utente=$_REQUEST['utente'];
	$Deck=$_REQUEST['Deck'];
	
	$query2="INSERT INTO permessi_ponte (utente,ponte,commessa) VALUES ($utente,$Deck,".$_SESSION['id_commessa'].")";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryColonne="SELECT MAX(id_permessi_ponte) AS id_permessi_ponte FROM permessi_ponte";
		$resultColonne=sqlsrv_query($conn,$queryColonne);
		if($resultColonne==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowColonne=sqlsrv_fetch_array($resultColonne))
			{
				echo $rowColonne["id_permessi_ponte"];
			}
		}
	}
	
?>