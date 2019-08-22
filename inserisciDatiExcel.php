<?php
	include "connessione.php";
	include "Session.php";
	
	$queriesString=$_REQUEST['queries'];
	
	$queries=explode("|",$queriesString);
	
	foreach ($queries as $query2) 
	{
		$result2=sqlsrv_query($conn,$query2);
		if($result2==FALSE)
		{
			die("Errore: ".print_r(sqlsrv_errors(),TRUE));
			/*echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));*/
		}
	}
	echo "ok";	
?>