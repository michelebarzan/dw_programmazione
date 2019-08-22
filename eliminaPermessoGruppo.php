<?php

	include "connessione.php";
	include "Session.php";
	
	$id_permessi_gruppo=$_REQUEST['id_permessi_gruppo'];
	
	$query2="DELETE permessi_gruppo FROM permessi_gruppo WHERE id_permessi_gruppo=$id_permessi_gruppo";	
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