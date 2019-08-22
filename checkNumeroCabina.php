<?php

	include "connessione.php";
	include "Session.php";
	
	$numero_cabina=$_REQUEST['numero_cabina'];
	
	$queryRighe="SELECT [Nr# Cabina  Santarossa] AS numero_cabina FROM dbo.[tip cab] WHERE commessa = ".$_SESSION['id_commessa']." AND dbo.[tip cab].[Nr# Cabina  Santarossa]='$numero_cabina'";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		$rows = sqlsrv_has_rows( $resultRighe );
		if ($rows === true)
			echo "ok";
		else
			echo "ko";
	}
?>