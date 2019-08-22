<?php
	include "connessione.php";
	include "Session.php";
	
	$anno=$_REQUEST['anno'];
	$ponte=$_REQUEST['ponte'];
	$mese=$_REQUEST['mese'];
	
	if($ponte=="%")
		$query2="SELECT  nomeDitta ,SUM(ore) AS ore FROM dbo.programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND ponte LIKE '$ponte' AND anno=$anno AND mese LIKE '$mese' GROUP BY  nomeDitta ";	
	else
		$query2="SELECT  nomeDitta ,SUM(ore) AS ore FROM dbo.programmazione_grafico2tipo2  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ($ponte) AND anno=$anno AND mese LIKE '$mese' GROUP BY  nomeDitta ";	
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
			echo $row2["nomeDitta"]."|".$row2["ore"]."%";
		}
	}
	
?>