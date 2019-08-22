<?php

	include "connessione.php";
	include "Session.php";
	
	$id_attivita_programmata=$_REQUEST['id_attivita_programmata'];
	
	$queryRighe="SELECT * FROM programmazione_righe_attivita_programmate WHERE attivita_programmata=$id_attivita_programmata ORDER BY id_cabina_coinvolta DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableElencoRigheAttivitaProgrammate'>";
			echo "<tr>";
				echo "<th>Settimana</th>";
				echo "<th>N cabine</th>";
				echo "<th>Ponte</th>";
				echo "<th>FZ</th>";
				echo "<th>Anno</th>";
				echo '<th style="padding:0px"><i style="color:black" class="far fa-trash-alt" onclick="eliminaTutteRigheAttivitaProgrammata('.$id_attivita_programmata.')" title="Elimina tutte le righe"></i></th>';
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr>";
					echo "<td>".$rowRighe['settimana']."</td>";
					echo "<td>".$rowRighe['nCabine']."</td>";
					echo "<td>".$rowRighe['ponte']."</td>";
					echo "<td>".$rowRighe['firezone']."</td>";
					echo "<td>".$rowRighe['anno']."</td>";
					echo '<td style="padding:0px"><i class="far fa-trash btnEliminaRigaRegistrazione" onclick="eliminaRigaAttivitaProgrammata('.$rowRighe["id_cabina_coinvolta"].')"></i></td>';
				echo "</tr>";
			}
		echo "</table>";
	}
	
	
	
?>