<?php

	include "connessione.php";
	include "Session.php";
	
	$codice_attivita=$_REQUEST['codice_attivita'];
	/*$tipologia=$_REQUEST['tipologia'];
	$famiglia=$_REQUEST['famiglia'];*/
	
	//$queryRighe="SELECT programmazione_ditte_attivita.*,cantiere_ditte.nome FROM [programmazione_ditte_attivita],cantiere_ditte WHERE programmazione_ditte_attivita.ditta=cantiere_ditte.id_ditta AND codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa']." AND famiglia='$famiglia' AND tipologia='$tipologia' ORDER BY cantiere_ditte.nome DESC";
	$queryRighe="SELECT programmazione_ditte_attivita.*,cantiere_ditte.nome FROM [programmazione_ditte_attivita],cantiere_ditte WHERE programmazione_ditte_attivita.ditta=cantiere_ditte.id_ditta AND codice_attivita =$codice_attivita AND commessa=".$_SESSION['id_commessa']." ORDER BY cantiere_ditte.nome DESC";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableDitteCabine'>";
			echo "<tr>";
				echo "<th>Ditta</th>";
				echo "<th>Ponte</th>";
				echo "<th>Firezone</th>";
				echo "<th></th>";
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr>";
					echo "<td>".$rowRighe['nome']."</td>";
					echo "<td>".$rowRighe['ponte']."</td>";
					echo "<td>".$rowRighe['firezone']."</td>";
					echo '<td style="padding:0px"><i class="far fa-trash btnEliminaRigaRegistrazione" onclick="eliminaDittaCabina('.htmlspecialchars(json_encode($rowRighe["id_ditte_attivita"])).')"></i></td>';
				echo "</tr>";
			}
		echo "</table>";
	}
?>