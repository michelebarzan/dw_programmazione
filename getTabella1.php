<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT * FROM programmazione_tabella1 WHERE commessa=".$_SESSION['id_commessa']." ORDER BY anno";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table class='myTableRiepilogoPresenzeDitte' id='myTableRiepilogoPresenzeDitte1'>";
			echo "<tr>";
				echo "<th>Ditta</th>";
				echo "<th>Ponte</th>";
				echo "<th>Anno</th>";
				echo "<th>Gennaio</th>";
				echo "<th>Febbraio</th>";
				echo "<th>Marzo</th>";
				echo "<th>Aprile</th>";
				echo "<th>Maggio</th>";
				echo "<th>Giugno</th>";
				echo "<th>Luglio</th>";
				echo "<th>Agosto</th>";
				echo "<th>Settembre</th>";
				echo "<th>Ottobre</th>";
				echo "<th>Novembre</th>";
				echo "<th>Dicembre</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo "<td>".$row2['nome']."</td>";
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["anno"].'</td>';
				echo '<td>'.$row2["1"].'</td>';
				echo '<td>'.$row2["2"].'</td>';
				echo '<td>'.$row2["3"].'</td>';
				echo '<td>'.$row2["4"].'</td>';
				echo '<td>'.$row2["5"].'</td>';
				echo '<td>'.$row2["6"].'</td>';
				echo '<td>'.$row2["7"].'</td>';
				echo '<td>'.$row2["8"].'</td>';
				echo '<td>'.$row2["9"].'</td>';
				echo '<td>'.$row2["10"].'</td>';
				echo '<td>'.$row2["11"].'</td>';
				echo '<td>'.$row2["12"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>