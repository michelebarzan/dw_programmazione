<?php

	include "connessione.php";
	include "Session.php";
	
	$id=$_REQUEST['id'];
	$campo=$_REQUEST['campo'];
	$tabella=$_REQUEST['tabella'];
	
	$query2="DELETE $tabella FROM $tabella WHERE $campo=$id";
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