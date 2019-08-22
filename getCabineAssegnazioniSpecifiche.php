<?php

	include "connessione.php";
	include "Session.php";
	
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$famiglia=$_REQUEST['famiglia'];
	$tipologia=$_REQUEST['tipologia'];
	$codice_attivita=$_REQUEST['codice_attivita'];
	$cabineOk=[];
	$cabineKo=[];
	
	$queryRighe="SELECT dbo.[tip cab].[Nr# Cabina  Santarossa] AS numero_cabina, v1.val
				FROM dbo.[tip cab] LEFT OUTER JOIN
				(SELECT attivita, commessa, val, numero_cabina
				FROM     dbo.programmazione_assegnazioni_specifiche
				WHERE (attivita = '$codice_attivita') AND (commessa = ".$_SESSION['id_commessa'].")) AS v1 ON dbo.[tip cab].[Nr# Cabina  Santarossa] = v1.numero_cabina AND dbo.[tip cab].commessa = v1.commessa
				WHERE (dbo.[tip cab].Deck LIKE '$ponte') AND (dbo.[tip cab].FZ LIKE '$firezone') AND (dbo.[tip cab].Famiglia LIKE '$famiglia') AND (dbo.[tip cab].[Pax/Crew] LIKE '$tipologia') AND (dbo.[tip cab].commessa = ".$_SESSION['id_commessa'].")
	
	
	SELECT [Nr# Cabina  Santarossa] AS numero_cabina, commessa FROM dbo.[tip cab] WHERE (Deck LIKE '$ponte') AND (FZ LIKE '$firezone') AND (Famiglia LIKE '$famiglia') AND ([Pax/Crew] LIKE '$tipologia') AND (commessa = ".$_SESSION['id_commessa'].")";
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo '<table id="myTableAssegnazioniSpecifiche">';
			echo "<tr>";
				echo "<th style='text-align:left'>";
					echo 'Cabine selezionate<i style="margin-left:10px;font-size:15px;" title="Aggiungi singola cabina" onclick="aggiugniCabina(prompt('.htmlspecialchars(json_encode("Numero cabina:")).'))" class="fal fa-plus-circle"></i>';
				echo "</th>";
				echo "<th style='text-align:right;width:40px;'>";
					echo '<i class="fal fa-plus" style="margin-left:5px;" title="Aggiungi tutte"></i><input title="Aggiungi tutte" id="assegnazioniSpecificheCheckboxTutteOk" type="checkbox" style="cursor:pointer" onchange="toggleCheckBoxTutteCabineOk()">';
				echo "</th>";
				echo "<th style='text-align:right;width:40px;'>";
					echo '<i class="fal fa-minus" style="margin-left:5px;" title="Rimuovi tutte"></i><input title="Rimuovi tutte" id="assegnazioniSpecificheCheckboxTutteKo" type="checkbox" style="cursor:pointer" onchange="toggleCheckBoxTutteCabineKo()">';
				echo "</th>";
			echo "</tr>";
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo '<tr>';
					echo "<td style='text-align:left'>".$rowRighe['numero_cabina']."</td>";
					$checkedOk="";
					$checkedKo="";
					if($rowRighe['val']==1)
					{
						$checkedOk="checked";
						array_push($cabineOk,$rowRighe["numero_cabina"]);
					}
					if($rowRighe['val']==-1)
					{
						$checkedKo="checked";
						array_push($cabineKo,$rowRighe["numero_cabina"]);
					}
					echo "<td style='text-align:right;width:40px;'><input type='checkbox' $checkedOk class='checkboxCabinaAssegnazioniSpecificheOk' numero_cabina='".$rowRighe['numero_cabina']."' id='checkboxCabinaOk".$rowRighe['numero_cabina']."' onchange='toggleCheckBoxCabinaOk(".htmlspecialchars(json_encode($rowRighe['numero_cabina'])).")'></td>";
					echo "<td style='text-align:right;width:40px;'><input type='checkbox' $checkedKo class='checkboxCabinaAssegnazioniSpecificheKo' numero_cabina='".$rowRighe['numero_cabina']."' id='checkboxCabinaKo".$rowRighe['numero_cabina']."' onchange='toggleCheckBoxCabinaKo(".htmlspecialchars(json_encode($rowRighe['numero_cabina'])).")'></td>";
				echo "</tr>";
			}
		echo "</table>";
		echo "|".implode(",",$cabineOk)."|".implode(",",$cabineKo);
	}
?>