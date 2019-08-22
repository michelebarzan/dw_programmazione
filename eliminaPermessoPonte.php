<?php

	include "connessione.php";
	include "Session.php";
	
	$id_permessi_ponte=$_REQUEST['id_permessi_ponte'];
	
	$query2="DELETE permessi_ponte FROM permessi_ponte WHERE id_permessi_ponte=$id_permessi_ponte";	
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