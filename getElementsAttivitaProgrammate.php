<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	
	$query2="SELECT id_attivita_programmata, codiceAttivita, descrizione, note, commessa, [kit/pref], colore, dashType
			FROM dbo.programmazione_attivita_programmate
			WHERE (id_attivita_programmata NOT IN
			(SELECT dbo.gruppo_attivita_programmata.attivita_programmata
			FROM dbo.gruppo_attivita_programmata INNER JOIN
			dbo.gruppi ON dbo.gruppo_attivita_programmata.gruppo = dbo.gruppi.id_gruppo INNER JOIN
			dbo.programmazione_attivita_programmate AS programmazione_attivita_programmate_1 ON 
			dbo.gruppo_attivita_programmata.attivita_programmata = programmazione_attivita_programmate_1.id_attivita_programmata AND 
			dbo.gruppi.commessa = programmazione_attivita_programmate_1.commessa
			WHERE (dbo.gruppo_attivita_programmata.gruppo = $id_gruppo) AND (dbo.gruppi.commessa = ".$_SESSION['id_commessa']."))) AND (commessa = ".$_SESSION['id_commessa'].")";
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
			echo '<div class="elementGestioneGruppi" style="cursor:grab" id="'.$id_gruppo.'attivitaProgrammata'.$row2["id_attivita_programmata"].'" title="Trascina o fai doppio click per aggiungere alle attivita collegate al gruppo" ondblclick="spostaAttivita(this.id)" draggable="true" ondragstart="drag(event)" ondragend="fixContainerStyle()">';
				echo $row2["descrizione"];
			echo '</div>';
		}
	}

?>