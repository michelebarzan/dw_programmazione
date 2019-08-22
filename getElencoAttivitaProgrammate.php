<?php

	include "connessione.php";
	include "Session.php";
	
	$queryRighe="SELECT * FROM programmazione_attivita_programmate WHERE commessa=".$_SESSION['id_commessa']." ORDER BY id_attivita_programmata DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableElencoAttivitaProgrammate'>";
			echo "<tr>";
				echo "<th>Id</th>";
				echo "<th>Codice</th>";
				echo "<th>Descrizione</th>";
				echo "<th>Kit/Pref</th>";
				echo "<th></th>";
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr id='rigaAttivitaProgrammata".$rowRighe['id_attivita_programmata']."' class='righeAttivitaProgrammate' onclick='selezionaAttivitaProgrammata(".$rowRighe['id_attivita_programmata'].")'>";
					echo "<td>".$rowRighe['id_attivita_programmata']."</td>";
					echo "<td>".$rowRighe['codiceAttivita']."</td>";
					echo "<td>".$rowRighe['descrizione']."</td>";
					echo "<td>".$rowRighe['kit/pref']."</td>";
					echo "<td style='padding:0px'><input type='button' id='btnSelezionaAttivitaProgrammate".$rowRighe['id_attivita_programmata']."' class='btnSelezionaAttivitaProgrammate' onclick='selezionaAttivitaProgrammata(".$rowRighe['id_attivita_programmata'].")' /></td>";
				echo "</tr>";
			}
		echo "</table>";
	}
	
	
	
?>