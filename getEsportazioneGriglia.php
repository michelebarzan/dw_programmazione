<?php
	include "connessione.php";
	include "Session.php";
	
	$gruppo=$_REQUEST['gruppo'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$ditta=$_REQUEST['ditta'];
	$famiglia=$_REQUEST['famiglia'];
	$tipologia=$_REQUEST['tipologia'];
	$dataInizio=$_REQUEST['dataInizio'];
	$dataFine=$_REQUEST['dataFine'];
	
	/*$dataInizio=DateTime::createFromFormat('Y-m-d', $_REQUEST['dataInizio']);
	$dataInizio=$dataInizio->format('Y-m-d');
	$dataFine=DateTime::createFromFormat('Y-m-d', $_REQUEST['dataFine']);
	$dataFine=$dataFine->format('Y-m-d');*/
	/*$dataInizio=DateTime::createFromFormat('Y-d-m', $_REQUEST['dataInizio']);
	$dataInizio=$dataInizio->format('Y-d-m');
	$dataFine=DateTime::createFromFormat('Y-d-m', $_REQUEST['dataFine']);
	$dataFine=$dataFine->format('Y-d-m');*/
	
	$annoInizio=explode("-",$dataInizio)[0];
	$meseInizio=explode("-",$dataInizio)[1];
	$giornoInizio=explode("-",$dataInizio)[2];
	
	$dataInizioChar=$annoInizio.$meseInizio.$giornoInizio;
	
	$annoFine=explode("-",$dataFine)[0];
	$meseFine=explode("-",$dataFine)[1];
	$giornoFine=explode("-",$dataFine)[2];
	
	$dataFineChar=$annoFine.$meseFine.$giornoFine;
	
	/*echo $dataInizio." - ";
	echo $dataFine;*/
	
	$attivitaParentesi=array();
	$attivita=array();
		
	if(set_time_limit(240))
	{
		echo "<table id='myTableEsportazioneGriglia' class='table table-header-rotated'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='normalTableHeader'>N cabina</th>";
					echo "<th class='normalTableHeader'>Ponte</th>";
					echo "<th class='normalTableHeader'>Firezone</th>";
					echo "<th class='normalTableHeader' style='height:30px;max-height:30px;width:170px;min-width:170px'>Famiglia</th>";
					echo "<th class='normalTableHeader' style='height:30px;max-height:30px;width:70px;min-width:70px'>Tipologia</th>";
					$query3="SELECT dbo.programmazione_attivita.descrizione, dbo.gruppi.id_gruppo, dbo.programmazione_attivita.codice_attivita FROM dbo.gruppi INNER JOIN dbo.gruppo_attivita ON dbo.gruppi.id_gruppo = dbo.gruppo_attivita.gruppo INNER JOIN dbo.programmazione_attivita ON dbo.gruppo_attivita.attivita = dbo.programmazione_attivita.codice_attivita  WHERE id_gruppo=$gruppo";	
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
							array_push($attivita,$row3['descrizione']);
							array_push($attivitaParentesi,"[".$row3['descrizione']."]");
							echo "<th class='rotate'><div><div class='innerDivRotate'>".$row3['descrizione']."</div></div></th>";
						}
				echo "</tr>";//data_svolgimento > '$dataInizio' AND data_svolgimento<'$dataFine'
			echo "</thead>";
			echo "<tbody>";
					$query4="SELECT distinct * 
							FROM (SELECT w1.numero_cabina AS nCabina, SUM(w1.valore) AS risultato, dbo.[tip cab].Deck AS ponte, CONVERT(varchar(2), dbo.[tip cab].FZ) AS firezone, dbo.[tip cab].Famiglia, dbo.[tip cab].[Pax/Crew] AS tipologia, 
							w1.Descrizione AS descrizione, w1.commessa
							FROM (SELECT dbo.tblDettagliAttSvolte.[numero cabina] AS numero_cabina, dbo.attivitasvolteV.codiceattivita AS codice_attivita, 10 AS valore, dbo.attivitasvolteV.commessa, dbo.Attivita.Descrizione, 
							dbo.attivitasvolteV.NomeDitta AS nome_ditta, NULL AS data_svolgimento
							FROM dbo.attivitasvolteV INNER JOIN
							dbo.Attivita ON dbo.attivitasvolteV.codiceattivita = dbo.Attivita.CodiceAttivita INNER JOIN
							dbo.tblDettagliAttSvolte ON dbo.attivitasvolteV.IDAttSvolte = dbo.tblDettagliAttSvolte.IDAttSvolteDettagli
							WHERE (CONVERT(char(8), dbo.attivitasvolteV.datasvolgimento, 112) >= '$dataInizioChar') AND (CONVERT(char(8), dbo.attivitasvolteV.datasvolgimento, 112) <= '$dataFineChar')
							UNION ALL
							SELECT dbo.[tip cab].[Nr# Cabina  Santarossa],0,0 ,dbo.[tip cab].[commessa],CONVERT(varchar(50), 0) AS [00],null,null
							FROM dbo.[tip cab]
							UNION ALL
							SELECT dbo.view_tblInternaCabineAttivita_1.numero_cabina AS nCabina, Attivita_1.CodiceAttivita AS codice_attivita, dbo.view_tblInternaCabineAttivita_1.val AS valore, dbo.view_tblInternaCabineAttivita_1.commessa, 
							dbo.view_tblInternaCabineAttivita_1.Descrizione, NULL AS data_svolgimento, NULL AS nome_ditta
							FROM dbo.view_tblInternaCabineAttivita_1 INNER JOIN
							dbo.Attivita AS Attivita_1 ON dbo.view_tblInternaCabineAttivita_1.Descrizione = Attivita_1.Descrizione) AS w1 INNER JOIN
							dbo.[tip cab] ON w1.numero_cabina = dbo.[tip cab].[Nr# Cabina  Santarossa] AND w1.commessa = dbo.[tip cab].commessa
							GROUP BY w1.numero_cabina, dbo.[tip cab].Deck, CONVERT(varchar(2), dbo.[tip cab].FZ), dbo.[tip cab].Famiglia, dbo.[tip cab].[Pax/Crew], w1.Descrizione, w1.commessa
							HAVING (dbo.[tip cab].Deck LIKE N'$ponte') AND (CONVERT(varchar(2), dbo.[tip cab].FZ) LIKE '$firezone') AND (dbo.[tip cab].Famiglia LIKE N'$famiglia') AND (dbo.[tip cab].[Pax/Crew] LIKE N'$tipologia') AND (w1.commessa = ".$_SESSION['id_commessa'].")) t PIVOT (SUM(risultato) 
							FOR descrizione IN (".implode(",",$attivitaParentesi).")) p";
					$result4=sqlsrv_query($conn,$query4);
					if($result4==FALSE)
					{
						echo "<br><br>Errore esecuzione query<br>Query: ".$query4."<br>Errore: ";
						die(print_r(sqlsrv_errors(),TRUE));
					}
					else
					{
						$nRighe=0;
						while($row4=sqlsrv_fetch_array($result4))
						{
							$nRighe++;
							echo "<tr>";
								echo "<th class='row-header'>".$row4['nCabina']."</th>";
								echo "<th class='row-header'>".$row4['ponte']."</th>";
								echo "<th class='row-header'>".$row4['firezone']."</th>";
								echo "<th class='row-header' style='height:30px;max-height:30px;width:170px;min-width:170px'>".$row4['Famiglia']."</th>";
								echo "<th class='row-header' style='height:30px;max-height:30px;width:70px;min-width:70px'>".$row4['tipologia']."</th>";
								foreach ($attivita as $value) 
								{
									if($row4[$value]==11)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#24953E;background-color:#24953E'>".$row4[$value]."</td>";//verde
									if($row4[$value]==0)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#BFBFBF;background-color:#BFBFBF'>".$row4[$value]."</td>";//grigio
									if($row4[$value]==1)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#EBEBEB;background-color:#EBEBEB'>".$row4[$value]."</td>";//niente
									if($row4[$value]!=11 && $row4[$value]!=0 && $row4[$value]!=1)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:red;background-color:red'>".$row4[$value]."</td>";//rosso
								}
							echo "</tr>";
						}
					}
				}
			echo "</tbody>";
		echo "</table>";
		//echo $query4;
		echo "|".$nRighe;
	}
?>