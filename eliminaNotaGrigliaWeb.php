<?php

	include "connessione.php";
	include "Session.php";
	
	$id_nota=$_REQUEST['id_nota'];
	$queryRighe="DELETE cantiere_note FROM cantiere_note WHERE id_nota=$id_nota";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
		echo "ok";
	
?>