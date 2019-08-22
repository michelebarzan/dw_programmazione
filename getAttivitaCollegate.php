<?php

	include "connessione.php";
	include "Session.php";
	
	echo "<input type='text' class='searchFieldGestioneGruppi' id='searchFieldGruppi' placeholder='Cerca tra i gruppi'>";
	echo "<input type='text' class='searchFieldGestioneGruppi' id='searchFieldAttivitaCollegate' placeholder='Cerca tra le attivita collegate'>";
	echo "<input type='text' class='searchFieldGestioneGruppi' id='searchFieldAttivita' placeholder='Cerca tra le attivita'>";
	echo "<input type='text' class='searchFieldGestioneGruppi' id='searchFieldAttivitaProgrammate' placeholder='Cerca tra le attivita programmate'>";
	echo "<div class='containerElementsGestioneGruppi' id='containerElementsGruppi'>";
		getElencoGruppi($conn);
	echo "</div>";
	echo "<div class='containerElementsGestioneGruppi' id='containerElementsAttivitaCollegate' ondrop='drop(event);fixContainerStyle()' ondragover='allowDrop(event)' ondragleave='dragLeave(event)'>";
	echo "</div>";
	echo "<div class='containerElementsGestioneGruppi' id='containerElementsAttivita'>";
	echo "</div>";
	echo "<div class='containerElementsGestioneGruppi' id='containerElementsAttivitaProgrammate'>";
	echo "</div>";
	
	function getElencoGruppi($conn)
	{
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
				echo '<div class="elementGestioneGruppi" style="cursor:pointer" title="Seleziona il gruppo" onclick="selezionaGruppo('.$row2["id_gruppo"].')" id="nomeGruppo'.$row2["id_gruppo"].'">'.$row2["nomeGruppo"].'</div>';
			}
		}
	}
	
?>