<?php

	include "connessione.php";
	include "Session.php";
		
	$columnList=array();
	$columnListPivot="";
	
	$query2="SELECT nome FROM cantiere_ditte where eliminata='false'";	
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
			array_push($columnList,$row2['nome']);
		}
	}
	echo "<table class='myTablePresenzeDitteDoubleTriple' id='myTableDettaglioMediaPresenzeDitte'>";
	echo "<tr>";
		echo "<th>Anno</th>";
		echo "<th>Mese</th>";
		echo "<th>Ponte</th>";
		foreach ($columnList as $value) 
		{
			$columnListPivot=$columnListPivot."[".$value."],";
			echo "<th>$value</th>";
		}
		$queryColonne="SELECT * FROM (SELECT mediaOperatori,anno,mese,nomeDitta,ponte,commessa FROM dbo.programmazione_tabella4_1) t PIVOT (SUM(mediaOperatori) FOR nomeDitta IN (".rtrim($columnListPivot,",")."))p where commessa=".$_SESSION['id_commessa'];
		
	echo "</tr>";
	//echo $queryColonne;
	$resultColonne=sqlsrv_query($conn,$queryColonne);
	if($resultColonne==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($rowColonne=sqlsrv_fetch_array($resultColonne))
		{
			echo "<tr>";
				echo "<td>".$rowColonne['anno']."</td>";
				echo "<td>".$rowColonne['mese']."</td>";
				echo "<td>".$rowColonne['ponte']."</td>";
				foreach ($columnList as $value) 
				{
					echo "<td>".number_format($rowColonne[$value],2)."</td>";
				}
			echo "</tr>";
		}
	}
	echo "</table>";
?>





