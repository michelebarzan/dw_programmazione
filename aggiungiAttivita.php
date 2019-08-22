<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	$tabella=$_REQUEST['tabella'];
	
	if($tabella=="gruppo_attivita_programmata")
		$query2="INSERT INTO [dbo].[gruppo_attivita_programmata] ([gruppo],[attivita_programmata]) VALUES ($id_gruppo,$codice_attivita)";
	else
		$query2="INSERT INTO [dbo].[gruppo_attivita]([gruppo],[attivita]) VALUES ($id_gruppo,$codice_attivita)";
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "ok";
	}

?>