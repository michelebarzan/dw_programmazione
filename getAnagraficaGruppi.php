<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT gruppi.* FROM gruppi WHERE commessa=".$_SESSION['id_commessa']." ORDER BY gruppi.nomeGruppo";
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableGestioneGestioneGruppi'>";
			echo "<tr>";
				echo "<th>Nome</th>";
				echo "<th>Griglia web</th>";
				echo "<th>Esportazione</th>";
				echo "<th>Grafico</th>";
				echo "<th></th>";
				echo "<th></th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			$grigliaChecked=$esportaioneChecked=$graficoChecked="";
			if($row2["griglia"]=='true')
				$grigliaChecked='checked';
			if($row2["esportazione"]=='true')
				$esportaioneChecked='checked';
			if($row2["grafico"]=='true')
				$graficoChecked='checked';
			echo "<tr id='rigaGruppo".$row2['id_gruppo']."'>";
				echo '<td><input type="text" id="nomeGruppo'.$row2["id_gruppo"].'" value="'.$row2["nomeGruppo"].'" maxlength="50" /></td>';
				echo '<td><input type="checkbox" id="grigliaGruppo'.$row2["id_gruppo"].'" '.$grigliaChecked.' /></td>';
				echo '<td><input type="checkbox" id="esportazioneGruppo'.$row2["id_gruppo"].'" '.$esportaioneChecked.' /></td>';
				echo '<td><input type="checkbox" id="graficoGruppo'.$row2["id_gruppo"].'" '.$graficoChecked.' /></td>';
				echo '<td><i class="fas fa-save" title="Salva modifiche" onclick="salvaModificheAnagraficaGruppo('.$row2["id_gruppo"].')"></i></td>';
				echo '<td id="risultato'.$row2["id_gruppo"].'" style="text-align:center;width:40px"></td>';
			echo "</tr>";
		}
			echo "<tr id='rigaNuovoGruppo'>";
				echo '<td><input type="text" id="nomeNuovoGruppo" maxlength="50" /></td>';
				echo '<td><input type="checkbox" id="grigliaNuovoGruppo"  /></td>';
				echo '<td><input type="checkbox" id="esportazioneNuovoGruppo"  /></td>';
				echo '<td><input type="checkbox" id="graficoNuovoGruppo"  /></td>';
				echo '<td><i class="fas fa-plus"  title="Inserisci gruppo" onclick="inserisciGruppo()"></i></td>';
				echo '<td id="risultatoNuovoGruppo" style="text-align:center;width:40px"></td>';
			echo "</tr>";
		echo "</table>";
	}

	
?>