<?php
	include "connessione.php";
	include "Session.php";
	
	$anno=$_REQUEST['anno'];
	$ditta=$_REQUEST['ditta'];
	$ponte=$_REQUEST['ponte'];
	$mese=$_REQUEST['mese'];
	
	if($ditta=="%")
	{
		if($ponte=="%")
			$query2="SELECT SUM(ore) AS ore,data FROM programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND anno=$anno AND mese=$mese GROUP BY data";	
		else
			$query2="SELECT SUM(ore) AS ore,data FROM programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ($ponte) AND anno=$anno AND mese=$mese GROUP BY data";	
	}
	else
	{
		if($ponte=="%")
			$query2="SELECT SUM(ore) AS ore,data FROM programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND nomeDitta LIKE '%$ditta%' AND anno=$anno AND mese=$mese GROUP BY data ";	
		else
			$query2="SELECT ore,data FROM programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ($ponte) AND nomeDitta LIKE '%$ditta%' AND anno=$anno AND mese=$mese";	
	}
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
			echo $row2["data"]->format('d/m/Y')."|".$row2["ore"]."%";
		}
	}
	
?>