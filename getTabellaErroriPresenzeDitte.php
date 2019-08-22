<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT * FROM programmazione_errori_presenze_ditte WHERE commessa=".$_SESSION['id_commessa'];	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table class='myTablePresenzeDitteDoubleTriple' id='myTableErroriPresenzeDitte'>";
			echo "<tr>";
				echo "<th>Registrazione</th>";
				echo "<th>Data</th>";
				echo "<th>Username</th>";
				echo "<th>Ditta</th>";
				echo "<th>Ponte</th>";
				echo "<th>Nome</th>";
				echo "<th>Cognome</th>";
				echo "<th>Ore registrate dall' utente</th>";
				echo "<th>Ore totali registrate</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo "<td>".$row2['id_registrazione']."</td>";
				echo '<td>'.$row2["data"]->format("d/m/Y").'</td>';
				echo '<td>'.$row2["username"].'</td>';
				echo '<td>'.$row2["nomeDitta"].'</td>';
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["nome"].'</td>';
				echo '<td>'.$row2["cognome"].'</td>';
				echo '<td>'.$row2["oreRegistrateDaUsername"].'</td>';
				echo '<td>'.$row2["oreTotaliRegistrate"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>