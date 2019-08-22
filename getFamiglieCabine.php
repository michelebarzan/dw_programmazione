<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	
	$queryRighe="SELECT distinct famiglia FROM [programmazione_tipologie_attivita] WHERE codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa']." ORDER BY famiglia DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableFamiglieCabine'>";
			echo "<tr>";
				echo "<th>Famiglia</th>";
				echo '<th style="padding:0px"><i class="far fa-trash btnEliminaRigaRegistrazione" title="Elimina tutte le assegnazioni" onclick="eliminaTutteFamiglieCabine('.$codice_attivita.')"></i></th>';
				echo "<th></th>";
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr onclick='getTipologieCabine($codice_attivita,".htmlspecialchars(json_encode($rowRighe['famiglia'])).")' id='rigaFamiglie".$rowRighe['famiglia']."' class='righeFamiglia'>";
					echo "<td>".$rowRighe['famiglia']."</td>";
					echo '<td style="padding:0px"><i class="far fa-trash btnEliminaRigaRegistrazione" onclick="eliminaFamigliaCabina('.htmlspecialchars(json_encode($rowRighe["famiglia"])).','.$codice_attivita.')"></i></td>';
				echo "</tr>";
			}
		echo "</table>";
	}
?>