<?php

	include "connessione.php";
	include "Session.php";
	
	$nomeGruppo=$_REQUEST['nomeGruppo'];
	$griglia=$_REQUEST['griglia'];
	$esportazione=$_REQUEST['esportazione'];
	$grafico=$_REQUEST['grafico'];
	
	/*echo $nomeGruppo."<br>";
	echo $griglia."<br>";
	echo $esportazione."<br>";
	echo $grafico."<br>";*/
	
	$query2="INSERT INTO [dbo].[gruppi]
           ([nomeGruppo]
           ,[commessa]
           ,[griglia]
           ,[esportazione]
           ,[grafico])
     VALUES ('$nomeGruppo',".$_SESSION['id_commessa']." , '$griglia','$esportazione','$grafico' )";
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