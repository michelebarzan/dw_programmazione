<?php

	include "connessione.php";
	include "Session.php";
		
	$query2="SELECT * FROM programmazione_grafico2tipo2 WHERE commessa=".$_SESSION['id_commessa']." ORDER BY anno,mese,nomeDitta";	
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
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo '<td>'.$row2["data"]->format("d/m/Y").'</td>';
				echo '<td>'.$row2["nomeDitta"].'</td>';
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["ore"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>