<?php

	include "connessione.php";
	include "Session.php";
	
	$id_permessi_firezone=$_REQUEST['id_permessi_firezone'];
	
	$query2="DELETE permessi_firezone FROM permessi_firezone WHERE id_permessi_firezone=$id_permessi_firezone";	
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