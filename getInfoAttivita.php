<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$query2="SELECT colore,dashType FROM Attivita WHERE CodiceAttivita=$codice_attivita";
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo $row2["colore"].'|'.$row2["dashType"];
		}
	}
	
?>