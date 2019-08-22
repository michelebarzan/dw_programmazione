<?php
	include "connessione.php";
	//include "Session.php";
	
	$gruppo=$_REQUEST['gruppo'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$ditta=$_REQUEST['ditta'];
	$famiglia=$_REQUEST['famiglia'];
	$tipologia=$_REQUEST['tipologia'];
	$dataInizio=DateTime::createFromFormat('Y-m-d', $_REQUEST['dataInizio']);
	$dataInizio=$dataInizio->format('Y-m-d');
	$dataFine=DateTime::createFromFormat('Y-m-d', $_REQUEST['dataFine']);
	$dataFine=$dataFine->format('Y-m-d');
	
	//echo $dataFine;
	
	$attivitaParentesi=array();
	$attivita=array();
		
	if(set_time_limit(120))
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
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
					$query4="SELECT top(50) *
							FROM (SELECT [nCabina], [risultato], [ponte], [firezone], [famiglia], [tipologia], [descrizione]
							 FROM [programmazione_esportazione_griglia_2] WHERE ponte LIKE '$ponte' AND firezone LIKE '$firezone' AND famiglia LIKE '$famiglia' AND tipologia LIKE '$tipologia' AND ((data_svolgimento > '$dataInizio' AND data_svolgimento<'$dataFine') or data_svolgimento is null) ) t  PIVOT (SUM(risultato) FOR descrizione IN (".implode(",",$attivitaParentesi).")) p 
							 WHERE nCabina IS NOT NULL";
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
								echo "<th class='row-header' style='height:30px;max-height:30px;width:170px;min-width:170px'>".$row4['famiglia']."</th>";
								echo "<th class='row-header' style='height:30px;max-height:30px;width:70px;min-width:70px'>".$row4['tipologia']."</th>";
								foreach ($attivita as $value) 
								{
									if($row4[$value]==11)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#24953E;background-color:#24953E'>".$row4[$value]."</td>";
									if($row4[$value]==0)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#BFBFBF;background-color:#BFBFBF'>".$row4[$value]."</td>";
									if($row4[$value]==1)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:#EBEBEB;background-color:#EBEBEB'>".$row4[$value]."</td>";
									if($row4[$value]!=11 && $row4[$value]!=0 && $row4[$value]!=1)
										echo "<td style='width:30px;height:30px;min-width:30px;min-height:30px;color:red;background-color:red'>".$row4[$value]."</td>";
								}
							echo "</tr>";
						}
					}
				}
			echo "</tbody>";
		echo "</table>";
		echo "|".$nRighe;
	}
?>