<?php

	include "connessione.php";
	include "Session.php";
	
	$numero_cabina=$_REQUEST['numero_cabina'];
	$attivita=$_REQUEST['attivita'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$famiglia=$_REQUEST['famiglia'];
	$tipologia=$_REQUEST['tipologia'];
	$utente=$_REQUEST['utente'];
			
	set_time_limit(240);
	
	$queryRighe="SELECT * FROM programmazione_riepilogo_griglia_web
				WHERE (numero_cabina LIKE '$numero_cabina') AND (attivita LIKE '$attivita') AND (utente LIKE '$utente') AND (id_commessa = ".$_SESSION['id_commessa'].") AND (ponte LIKE N'$ponte') AND 
				(firezone LIKE '$firezone') AND (famiglia LIKE N'$famiglia') AND (tipologia LIKE N'$tipologia')";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableRiepilogoGrigliaWeb'>";
			echo "<tr>";
				echo '<th>';
					echo 'Id';
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(0,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Numero cabina';
					echo getSelectFiltro($conn,"numero_cabina","M",$numero_cabina);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(1,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Attivita';
					echo getSelectFiltro($conn,"attivita","F",$attivita);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(2,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(2,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Data ultima modifica';
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(3,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(3,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Nota';
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(4,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(4,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Utente';
					echo getSelectFiltro($conn,"utente","M",$utente);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(5,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(5,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Ponte';
					echo getSelectFiltro($conn,"ponte","M",$ponte);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(6,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(6,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Firezone';
					echo getSelectFiltro($conn,"firezone","F",$firezone);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(7,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(7,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Famiglia';
					echo getSelectFiltro($conn,"famiglia","F",$famiglia);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(8,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(8,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th>';
					echo 'Tipologia';
					echo getSelectFiltro($conn,"tipologia","F",$tipologia);
					echo '<i class="fas fa-sort-amount-up" onclick="sortTable(9,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("desc")).')"></i>';
					echo '<i class="fas fa-sort-amount-down" onclick="sortTable(9,'.htmlspecialchars(json_encode("myTableRiepilogoGrigliaWeb")).','.htmlspecialchars(json_encode("asc")).')"></i>';
				echo '</th>';
				echo '<th></th>';
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<tr>";
					echo "<td>".$rowRighe['id_nota']."</td>";
					echo "<td>".$rowRighe['numero_cabina']."</td>";
					echo "<td>".$rowRighe['attivita']."</td>";
					echo "<td>".$rowRighe['data_ultima_modifica']->format('d/m/Y')."</td>";
					echo "<td style='padding:0px'><textarea disabled>".$rowRighe['nota']."</textarea></td>";
					echo "<td>".$rowRighe['utente']."</td>";
					echo "<td>".$rowRighe['ponte']."</td>";
					echo "<td>".$rowRighe['firezone']."</td>";
					echo "<td>".$rowRighe['famiglia']."</td>";
					echo "<td>".$rowRighe['tipologia']."</td>";
					echo '<td  style="text-align:center"><i class="far fa-trash" style="margin-left:0px;float:center" title="Elimina nota" onclick="eliminaNotaGrigliaWeb('.$rowRighe["id_nota"].')"></i></td>';
				echo "</tr>";
			}
		echo "</table>";
	}

	function getSelectFiltro($conn,$colonna,$sesso,$valore)
	{
		if($valore=="%")
			$queryRighe="SELECT DISTINCT $colonna FROM programmazione_riepilogo_griglia_web WHERE id_commessa=".$_SESSION['id_commessa']." ORDER BY $colonna";
		else
			$queryRighe="SELECT DISTINCT $colonna FROM programmazione_riepilogo_griglia_web WHERE id_commessa=".$_SESSION['id_commessa']." AND $colonna<>'$valore' ORDER BY $colonna";
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			if($valore!="%")
				echo "<select class='selectFiltriRiepilogoGrigliaWeb' style='color:red' onchange='getNoteGrigliaWeb()' id='selectFiltro$colonna'>";
			else
				echo "<select class='selectFiltriRiepilogoGrigliaWeb' onchange='getNoteGrigliaWeb()' id='selectFiltro$colonna'>";
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
