<?php

	include "connessione.php";
	include "Session.php";
	
	$queryRighe="SELECT TOP (100) PERCENT dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
				dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, COUNT(dbo.view_tblInternaCabineAttivita_1.numero_cabina) AS nCabine
				FROM dbo.programmazione_attivita INNER JOIN
				dbo.programmazione_attivita_commessa ON dbo.programmazione_attivita.codice_attivita = dbo.programmazione_attivita_commessa.codice_attivita LEFT OUTER JOIN
				dbo.view_tblInternaCabineAttivita_1 ON dbo.programmazione_attivita_commessa.commessa = dbo.view_tblInternaCabineAttivita_1.commessa AND 
				dbo.programmazione_attivita.Descrizione = dbo.view_tblInternaCabineAttivita_1.Descrizione
				GROUP BY dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
				dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, dbo.programmazione_attivita.eliminata
				HAVING (dbo.programmazione_attivita_commessa.commessa = ".$_SESSION['id_commessa'].") AND (dbo.programmazione_attivita.eliminata = 'false')
				ORDER BY dbo.programmazione_attivita.Descrizione";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo '<div id="cercaAttivitaContainer">';
			echo '<input type="search" id="cercaAttivitaInput" onkeyup="cercaAttivita()" onsearch="cercaAttivita()" placeholder="Cerca tra le attivita..." >';
		echo '</div>';
		echo "<table id='myTableElencoAttivita'>";
			echo "<tr>";
				echo '<th onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableElencoAttivita")).','.htmlspecialchars(json_encode("desc")).')">#<i class="fas fa-sort" style="margin-left:5px"></i></th>';
				echo '<th onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableElencoAttivita")).','.htmlspecialchars(json_encode("desc")).')">Descrizione<i class="fas fa-sort" style="margin-left:5px"></i></th>';
				echo '<th>K./P.</th>';
				echo '<th>N.Cab</th>';
				echo '<th style="padding:0px;text-align:center" id="iconCercaAttivitaContainer" onclick="toggleCercaAttivita()"><i class="far fa-search"></i></th>';
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr id='rigaAttivita".$rowRighe['codice_attivita']."' class='righeAttivita' onclick='selezionaAttivita(".$rowRighe['codice_attivita'].")'>";
					echo "<td>".$rowRighe['codice_attivita']."</td>";
					echo "<td>".$rowRighe['Descrizione']."</td>";
					echo "<td>".$rowRighe['kit/pref']."</td>";
					echo "<td>".$rowRighe['nCabine']."</td>";
					echo "<td style='padding:0px'><input type='button' id='btnSelezionaAttivita".$rowRighe['codice_attivita']."' class='btnSelezionaAttivita' onclick='selezionaAttivita(".$rowRighe['codice_attivita'].")' /></td>";
				echo "</tr>";
			}
		echo "</table>";
	}
?>