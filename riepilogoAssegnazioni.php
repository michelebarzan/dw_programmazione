<?php

	include "connessione.php";
	include "Session.php";
	
	set_time_limit(240);
	
	$Descrizione=$_REQUEST['Descrizione'];
	$nomeDitta=$_REQUEST['nomeDitta'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$totCabine=$_REQUEST['totCabine'];
	$totOre=$_REQUEST['totOre'];
	
	$queryRighe="SELECT * FROM programmazione_riepilogo_assegnazioni WHERE Descrizione LIKE '$Descrizione' AND nomeDitta LIKE '$nomeDitta' AND ponte LIKE '$ponte' AND firezone LIKE '$firezone' AND totCabine LIKE '$totCabine' AND totOre LIKE '$totOre' AND commessa LIKE '".$_SESSION['commessa']."'";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableRiepilogoAssegnazioni'>";
			echo "<tr>";
				echo '<th>';
					echo 'Attivita';
					echo getSelectFiltro($conn,"Descrizione","F",$Descrizione);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Ditta';
					echo getSelectFiltro($conn,"nomeDitta","F",$nomeDitta);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Ponte';
					echo getSelectFiltro($conn,"ponte","M",$ponte);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(2,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(2,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Firezone';
					echo getSelectFiltro($conn,"firezone","F",$firezone);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(3,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(3,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Tot cabine';
					echo getSelectFiltro($conn,"totCabine","F",$totCabine);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(4,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(4,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Tot ore';
					echo getSelectFiltro($conn,"totOre","F",$totOre);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(5,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(5,'.htmlspecialchars(json_encode("myTableRiepilogoAssegnazioni")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr>";
					echo "<td>".$rowRighe['Descrizione']."</td>";
					echo "<td>".$rowRighe['nomeDitta']."</td>";
					echo "<td>".$rowRighe['ponte']."</td>";
					echo "<td>".$rowRighe['firezone']."</td>";
					echo "<td>".$rowRighe['totCabine']."</td>";
					echo "<td>".$rowRighe['totOre']."</td>";
				echo "</tr>";
			}
		echo "</table>";
	}

	function getSelectFiltro($conn,$colonna,$sesso,$valore)
	{
		if($valore=="%")
			$queryRighe="SELECT DISTINCT $colonna FROM programmazione_riepilogo_assegnazioni WHERE commessa='".$_SESSION['commessa']."' ORDER BY $colonna";
		else
			$queryRighe="SELECT DISTINCT $colonna FROM programmazione_riepilogo_assegnazioni WHERE commessa='".$_SESSION['commessa']."' AND $colonna<>'$valore' ORDER BY $colonna";
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			if($valore!="%")
				echo "<select class='selectFiltriRiepilogoAssegnazioni' style='color:red' onchange='riepilogoAssegnazioni()' id='selectFiltro$colonna'>";
			else
				echo "<select class='selectFiltriRiepilogoAssegnazioni' onchange='riepilogoAssegnazioni()' id='selectFiltro$colonna'>";
			if($valore!="%")
				echo "<option value='$valore'>$valore</option>";
			if($sesso=="F")
				echo "<option value='%'>Tutte</option>";
			else
				echo "<option value='%'>Tutti</option>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
					echo "<option value='".$rowRighe[$colonna]."'>".$rowRighe[$colonna]."</option>";
			}
		echo "</select>";
	}
	}

?>
