<?php
	include "connessione.php";
	include "Session.php";
	
	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$ditteEnc=json_decode($_REQUEST['JSONditte']);
	$ditte=[];
	foreach ($ditteEnc as $ditta)
	{ 
		array_push($ditte,urldecode($ditta));
	} 
	$inDitte=implode("','",$ditte);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);

	$query2="SELECT SUM(ore) AS ore,mese FROM programmazione_grafico1  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ('".$inPonti."') AND nome IN ('".$inDitte."') AND anno IN ('".$inAnni."') GROUP BY mese ORDER BY mese";	
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
			echo $row2["mese"]."|".$row2["ore"]."%";
		}
	}
?>