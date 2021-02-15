<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];

	if($id_gruppo==0)
	{
		$queryRighe="SELECT TOP (100) PERCENT dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
				dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, posizione, COUNT(dbo.view_tblInternaCabineAttivita_1.numero_cabina) AS nCabine
				FROM dbo.programmazione_attivita INNER JOIN
				dbo.programmazione_attivita_commessa ON dbo.programmazione_attivita.codice_attivita = dbo.programmazione_attivita_commessa.codice_attivita LEFT OUTER JOIN
				dbo.view_tblInternaCabineAttivita_1 ON dbo.programmazione_attivita_commessa.commessa = dbo.view_tblInternaCabineAttivita_1.commessa AND 
				dbo.programmazione_attivita.Descrizione = dbo.view_tblInternaCabineAttivita_1.Descrizione
				GROUP BY dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
				dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, dbo.programmazione_attivita.eliminata, posizione
				HAVING (dbo.programmazione_attivita_commessa.commessa = ".$_SESSION['id_commessa'].") AND (dbo.programmazione_attivita.eliminata = 'false')
				ORDER BY dbo.programmazione_attivita.posizione";
	}
	else
	{
		$queryRighe="SELECT TOP (100) PERCENT dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
							dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, posizione, COUNT(dbo.view_tblInternaCabineAttivita_1.numero_cabina) AS nCabine, 
							dbo.gruppo_attivita.gruppo
					FROM dbo.programmazione_attivita INNER JOIN
							dbo.programmazione_attivita_commessa ON dbo.programmazione_attivita.codice_attivita = dbo.programmazione_attivita_commessa.codice_attivita INNER JOIN
							dbo.gruppo_attivita ON dbo.programmazione_attivita.codice_attivita = dbo.gruppo_attivita.attivita LEFT OUTER JOIN
							dbo.view_tblInternaCabineAttivita_1 ON dbo.programmazione_attivita_commessa.commessa = dbo.view_tblInternaCabineAttivita_1.commessa AND 
							dbo.programmazione_attivita.Descrizione = dbo.view_tblInternaCabineAttivita_1.Descrizione
					GROUP BY dbo.programmazione_attivita.codice_attivita, dbo.programmazione_attivita.Descrizione, dbo.programmazione_attivita.[kit/pref], dbo.programmazione_attivita.colore, dbo.programmazione_attivita.dashType, 
							dbo.programmazione_attivita.note, dbo.programmazione_attivita.[marina/arredo], dbo.programmazione_attivita_commessa.commessa, dbo.programmazione_attivita.eliminata, dbo.gruppo_attivita.gruppo, posizione
					HAVING (dbo.programmazione_attivita_commessa.commessa = ".$_SESSION['id_commessa'].") AND (dbo.programmazione_attivita.eliminata = 'false') AND (dbo.gruppo_attivita.gruppo = $id_gruppo)
					ORDER BY dbo.programmazione_attivita.posizione";
	}	
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
			echo '<select id="selectGruppoAttivita" onchange="getElencoAttivita()">';
				if($id_gruppo==0)
					echo '<option value="0" selected>Tutti i gruppi</option>';
				else
					echo '<option value="0">Tutti i gruppi</option>';
				$query2="SELECT gruppi.* FROM gruppi WHERE commessa=".$_SESSION['id_commessa']." ORDER BY gruppi.nomeGruppo";
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
						if($id_gruppo==$row2["id_gruppo"])
							echo '<option value="'.$row2["id_gruppo"].'" selected>'.$row2["nomeGruppo"].'</option>';
						else
							echo '<option value="'.$row2["id_gruppo"].'">'.$row2["nomeGruppo"].'</option>';
					}
				}
			echo '</select>';
		echo '</div>';
		echo "<table id='myTableElencoAttivita'>";
			echo "<tr>";
				echo '<th onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableElencoAttivita")).','.htmlspecialchars(json_encode("desc")).')">#<i class="fas fa-sort" style="margin-left:5px"></i></th>';
				echo '<th onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableElencoAttivita")).','.htmlspecialchars(json_encode("desc")).')">Descrizione<i class="fas fa-sort" style="margin-left:5px"></i></th>';
				echo '<th colspan="2" onclick="sortTable(3,'.htmlspecialchars(json_encode("myTableElencoAttivita")).','.htmlspecialchars(json_encode("asc")).')">Pos.<i class="fas fa-sort" style="margin-left:5px"></i></th>';
				echo '<th>K./P.</th>';
				echo '<th>N.Cab</th>';
				echo '<th style="padding:0px;text-align:center" id="iconCercaAttivitaContainer" onclick="toggleCercaAttivita()"><i class="fas fa-filter"></i></th>';
			echo "</tr>";
			$i=0;
			$arrayAttivita=[];
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				$attivita['codice_attivita']=$rowRighe['codice_attivita'];
				$attivita['posizione']=$rowRighe['posizione'];
				array_push($arrayAttivita,$attivita);

				echo "<tr id='rigaAttivita".$rowRighe['codice_attivita']."' class='righeAttivita' onclick='selezionaAttivita(".$rowRighe['codice_attivita'].")'>";
					echo "<td>".$rowRighe['codice_attivita']."</td>";
					echo "<td>".$rowRighe['Descrizione']."</td>";
					echo '<td><button onclick="cambiaPosizione(event,'.$rowRighe["codice_attivita"].','.htmlspecialchars(json_encode("meno")).',this)"><i class="fad fa-arrow-alt-square-up"></i></button><button onclick="cambiaPosizione(event,'.$rowRighe["codice_attivita"].','.htmlspecialchars(json_encode("piu")).',this)"><i class="fad fa-arrow-alt-square-down"></i></button></td>';
					echo "<td id='posizione".$rowRighe['codice_attivita']."'>".$rowRighe['posizione']."</td>";
					echo "<td>".$rowRighe['kit/pref']."</td>";
					echo "<td>".$rowRighe['nCabine']."</td>";
					echo "<td style='padding:0px'><input type='button' id='btnSelezionaAttivita".$rowRighe['codice_attivita']."' class='btnSelezionaAttivita' onclick='selezionaAttivita(".$rowRighe['codice_attivita'].")' /></td>";
				echo "</tr>";
				$i++;
			}
		echo "</table>";
		echo "|";
		echo json_encode($arrayAttivita);
	}
?>