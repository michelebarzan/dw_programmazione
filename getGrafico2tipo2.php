<?php
	include "connessione.php";
	include "Session.php";

	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);

	$ditteEnc=json_decode($_REQUEST['JSONditte']);
	$ditte=[];
	foreach ($ditteEnc as $ditta)
	{ 
		array_push($ditte,urldecode($ditta));
	} 
	$inDitte=implode("','",$ditte);

	$mesi=json_decode($_REQUEST['JSONmesi']);
	$inMesi=implode("','",$mesi);

	$query2="SELECT SUM(ore) AS ore,data FROM programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ('".$inPonti."') AND nomeDitta IN ('".$inDitte."') AND anno IN ('".$inAnni."') AND mese IN ('".$inMesi."') GROUP BY data";	
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