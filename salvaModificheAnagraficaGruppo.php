<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	$nomeGruppo=$_REQUEST['nomeGruppo'];
	$griglia=$_REQUEST['griglia'];
	$esportazione=$_REQUEST['esportazione'];
	$grafico=$_REQUEST['grafico'];
	
	/*echo $nomeGruppo."<br>";
	echo $griglia."<br>";
	echo $esportazione."<br>";
	echo $grafico."<br>";*/
	
	$query2="UPDATE gruppi SET commessa=".$_SESSION['id_commessa']." , nomeGruppo='$nomeGruppo', griglia='$griglia',esportazione='$esportazione',grafico='$grafico' WHERE id_gruppo=$id_gruppo";
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