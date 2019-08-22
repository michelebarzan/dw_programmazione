<?php

	include "connessione.php";
	include "Session.php";
	
	$utente=$_REQUEST['utente'];
	$gruppo=$_REQUEST['gruppo'];
	
	$query2="INSERT INTO permessi_gruppo (utente,gruppo,commessa) VALUES ($utente,$gruppo,".$_SESSION['id_commessa'].")";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$queryColonne="SELECT MAX(id_permessi_gruppo) AS id_permessi_gruppo FROM permessi_gruppo";
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
				echo $rowColonne["id_permessi_gruppo"];
			}
		}
	}
	
?>