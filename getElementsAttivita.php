<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	
	$query2="SELECT dbo.programmazione_attivita_commessa.codice_attivita, dbo.Attivita.Descrizione AS descrizione
			FROM dbo.Attivita INNER JOIN
			dbo.programmazione_attivita_commessa ON dbo.Attivita.CodiceAttivita = dbo.programmazione_attivita_commessa.codice_attivita
			WHERE (dbo.programmazione_attivita_commessa.codice_attivita NOT IN
			(SELECT DISTINCT dbo.gruppo_attivita.attivita
			FROM dbo.gruppo_attivita INNER JOIN
			dbo.gruppi ON dbo.gruppo_attivita.gruppo = dbo.gruppi.id_gruppo INNER JOIN
			dbo.programmazione_attivita_commessa AS programmazione_attivita_commessa_1 ON dbo.gruppi.commessa = programmazione_attivita_commessa_1.commessa
			WHERE (dbo.gruppi.commessa = ".$_SESSION['id_commessa'].") AND (dbo.gruppo_attivita.gruppo = $id_gruppo))) AND (dbo.programmazione_attivita_commessa.commessa = ".$_SESSION['id_commessa'].")";
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
			echo '<div class="elementGestioneGruppi" style="cursor:grab" id="'.$id_gruppo.'attivita'.$row2["codice_attivita"].'" title="Trascina o fai doppio click per aggiungere alle attivita collegate al gruppo" ondblclick="spostaAttivita(this.id)" draggable="true" ondragstart="drag(event)" ondragend="fixContainerStyle()">';
				echo $row2["descrizione"];
			echo '</div>';
		}
	}

?>