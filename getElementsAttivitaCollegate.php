<?php

	include "connessione.php";
	include "Session.php";
	
	$id_gruppo=$_REQUEST['id_gruppo'];
	
	$query2="SELECT        dbo.gruppo_attivita.gruppo, dbo.gruppi.nomeGruppo, dbo.gruppo_attivita.attivita, dbo.gruppi.commessa, dbo.gruppo_attivita.id_gruppo_attivita, dbo.programmazione_attivita.descrizione
FROM            dbo.gruppi INNER JOIN
                         dbo.gruppo_attivita ON dbo.gruppi.id_gruppo = dbo.gruppo_attivita.gruppo INNER JOIN
                         dbo.programmazione_attivita ON dbo.gruppo_attivita.attivita = dbo.programmazione_attivita.codice_attivita WHERE dbo.gruppi.commessa=".$_SESSION['id_commessa']." AND gruppo=$id_gruppo ORDER BY id_gruppo_attivita";
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
			echo '<div class="elementGestioneGruppi" id="attivitaCollegata'.$row2["id_gruppo_attivita"].'">';
				echo '<span style="275px;overflow:hidden;float:left;display:inline-block">'.$row2["descrizione"]."</span>";
				echo '<i style="float:right;margin-right:3px;margin-top:3px;display:inline-block;color:red;width:20px;cursor:pointer" title="Rimuovi attivita collegata" onclick="rimuoviAttivitaCollegata('.$id_gruppo.','.$row2["id_gruppo_attivita"].','.htmlspecialchars(json_encode('id_gruppo_attivita')).','.htmlspecialchars(json_encode('gruppo_attivita')).')" class="fas fa-trash"></i>';
			echo '</div>';
		}
	}
	$query3="SELECT        dbo.gruppo_attivita_programmata.gruppo, dbo.gruppi.nomeGruppo, dbo.gruppo_attivita_programmata.attivita_programmata, dbo.gruppi.commessa, 
                         dbo.gruppo_attivita_programmata.id_gruppo_attivita_programmata, dbo.programmazione_attivita_programmate.descrizione
FROM            dbo.gruppi INNER JOIN
                         dbo.gruppo_attivita_programmata ON dbo.gruppi.id_gruppo = dbo.gruppo_attivita_programmata.gruppo INNER JOIN
                         dbo.programmazione_attivita_programmate ON dbo.gruppo_attivita_programmata.attivita_programmata = dbo.programmazione_attivita_programmate.id_attivita_programmata WHERE dbo.gruppi.commessa=".$_SESSION['id_commessa']." AND gruppo=$id_gruppo ORDER BY id_gruppo_attivita_programmata";
	$result3=sqlsrv_query($conn,$query3);
	if($result3==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query3."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row3=sqlsrv_fetch_array($result3))
		{
			echo '<div class="elementGestioneGruppi" id="attivitaCollegata'.$row3["id_gruppo_attivita_programmata"].'">';
				echo '<span style="275px;overflow:hidden;float:left;display:inline-block">'.$row3["descrizione"]."</span>";
				echo '<i style="float:right;margin-right:3px;margin-top:3px;display:inline-block;color:red;width:20px;cursor:pointer" title="Rimuovi attivita collegata" onclick="rimuoviAttivitaCollegata('.$id_gruppo.','.$row3["id_gruppo_attivita_programmata"].','.htmlspecialchars(json_encode('id_gruppo_attivita_programmata')).','.htmlspecialchars(json_encode('gruppo_attivita_programmata')).')" class="fas fa-trash"></i>';
			echo '</div>';
		}
	}
?>