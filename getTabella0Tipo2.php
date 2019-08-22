<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT * FROM programmazione_grafico1 WHERE commessa=".$_SESSION['id_commessa']." ORDER BY nome";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table class='myTableRiepilogoPresenzeDitte' id='myTableRiepilogoPresenzeDitte0'>";
			echo "<tr>";
				echo "<th>Ditta</th>";
				echo "<th>Mese</th>";
				echo "<th>Anno</th>";
				echo "<th>Ponte</th>";
				echo "<th>Ore</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo "<td>".$row2['nome']."</td>";
				echo '<td>'.$row2["mese"].'</td>';
				echo '<td>'.$row2["anno"].'</td>';
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["ore"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>