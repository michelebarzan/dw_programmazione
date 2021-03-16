<?php

	include "connessione.php";
	include "Session.php";
		
	$query2="SELECT        TOP (100) PERCENT data, mese, nomeDitta, ponte, ore, anno, commessa, note
	FROM            (SELECT        data, MONTH(data) AS mese, nomeDitta, ponte, SUM(ore) AS ore, YEAR(data) AS anno, commessa, note
							  FROM            dbo.cantiere_riepilogo_registrazioni
							  GROUP BY data, nomeDitta, ponte, MONTH(data), YEAR(data), commessa, note) AS programmazione_grafico2tipo2
	WHERE        (commessa = ".$_SESSION['id_commessa'].")
	ORDER BY anno, mese, nomeDitta";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table class='myTableRiepilogoPresenzeDitte' id='myTableRiepilogoPresenzeDitte2'>";
			echo "<tr>";
				echo "<th>Data</th>";
				echo "<th>Ditta</th>";
				echo "<th>Ponte</th>";
				echo "<th>Ore</th>";
				echo "<th>Note</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo '<td>'.$row2["data"]->format("d/m/Y").'</td>';
				echo '<td>'.$row2["nomeDitta"].'</td>';
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["ore"].'</td>';
				echo '<td>'.$row2["note"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>