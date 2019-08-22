<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	$famiglia=$_REQUEST['famiglia'];
	
	$queryRighe="SELECT id_tipologie_attivita,tipologia,ore FROM [programmazione_tipologie_attivita] WHERE codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa']." AND famiglia='$famiglia' ORDER BY tipologia DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableTipologieCabine'>";
			echo "<tr>";
				echo "<th>Tipologia</th>";
				echo "<th>Ore</th>";
				echo "<th></th>";
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				//echo "<tr onclick='getDitteCabine($codice_attivita,".htmlspecialchars(json_encode($rowRighe['tipologia'])).",".htmlspecialchars(json_encode($famiglia)).")' id='rigaTipologia".$rowRighe['tipologia']."' class='righeTipologie'>";
				echo "<tr class='righeTipologie'>";
					echo "<td>".$rowRighe['tipologia']."</td>";
					echo "<td style='padding:0px'><input type='number' onfocusout='assegnaOreTipologia(".htmlspecialchars(json_encode($rowRighe['id_tipologie_attivita'])).",this.value)' value='".$rowRighe['ore']."'></td>";
					echo '<td style="padding:0px"><i class="far fa-trash btnEliminaRigaRegistrazione" onclick="eliminaTipologiaCabina('.htmlspecialchars(json_encode($rowRighe["id_tipologie_attivita"])).')"></i></td>';
				echo "</tr>";
			}
		echo "</table>";
	}
?>