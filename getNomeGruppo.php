<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	
	$query2="SELECT nomeGruppo FROM gruppi WHERE id_gruppo=$id_gruppo";	
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
			echo $row2["nomeGruppo"];
		}
	}
	
?>