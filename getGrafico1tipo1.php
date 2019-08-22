<?php
	include "connessione.php";
	include "Session.php";
	
	$anno=$_REQUEST['anno'];
	$ditta=$_REQUEST['ditta'];
	$ponte=$_REQUEST['ponte'];
	
	if($ponte=="%")
		$query2="SELECT SUM(ore) AS ore,mese FROM programmazione_grafico1  WHERE commessa=".$_SESSION['id_commessa']." AND ponte LIKE '$ponte' AND nome LIKE '$ditta' AND anno=$anno GROUP BY mese";	
	else
		$query2="SELECT SUM(ore) AS ore,mese FROM programmazione_grafico1  WHERE commessa=".$_SESSION['id_commessa']." AND ponte IN ($ponte) AND nome LIKE '$ditta' AND anno=$anno GROUP BY mese";	
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