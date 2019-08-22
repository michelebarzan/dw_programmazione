<?php
	include "connessione.php";
	include "Session.php";
	
	$tipo=$_REQUEST['tipo'];
	$gruppo=$_REQUEST['gruppo'];
	$ponte=$_REQUEST['ponte'];
	$firezone=$_REQUEST['firezone'];
	$ditta=$_REQUEST['ditta'];
	$settimanaInizio=$_REQUEST['settimanaInizio'];
	$settimanaFine=$_REQUEST['settimanaFine'];
	$legenda="";
	$attivita=array();
	$attivitaParentesi=array();
	$attivitaApici=array();
	$attivitaProgrammate=array();
	$attivitaProgrammateParentesi=array();
	$attivitaProgrammateApici=array();
	
	echo "<table id='myTableScaricaExcelGraficoAvanzamento' >";
		echo "<tr>";
			echo "<th>Settimana</th>";
			$query3="SELECT dbo.programmazione_attivita.descrizione, dbo.gruppi.id_gruppo, dbo.programmazione_attivita.codice_attivita
					FROM dbo.gruppi INNER JOIN
					dbo.gruppo_attivita ON dbo.gruppi.id_gruppo = dbo.gruppo_attivita.gruppo INNER JOIN
					dbo.programmazione_attivita ON dbo.gruppo_attivita.attivita = dbo.programmazione_attivita.codice_attivita 
					WHERE id_gruppo=$gruppo";	
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
					array_push($attivitaApici,"'".$row3['descrizione']."'");
					echo "<th>".$row3['descrizione']."</th>";
				}
			}
			$query4="SELECT dbo.gruppi.id_gruppo, dbo.gruppi.nomeGruppo, dbo.programmazione_attivita_programmate.codiceAttivita AS codice_attivita, dbo.programmazione_attivita_programmate.descrizione, dbo.gruppi.commessa
			FROM dbo.gruppi INNER JOIN
				dbo.gruppo_attivita_programmata ON dbo.gruppi.id_gruppo = dbo.gruppo_attivita_programmata.gruppo INNER JOIN
				dbo.programmazione_attivita_programmate ON dbo.gruppo_attivita_programmata.attivita_programmata = dbo.programmazione_attivita_programmate.id_attivita_programmata
			WHERE (dbo.gruppi.id_gruppo = $gruppo) AND (dbo.gruppi.commessa = ".$_SESSION['id_commessa'].")";	
			$result4=sqlsrv_query($conn,$query4);
			if($result4==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$query4."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row4=sqlsrv_fetch_array($result4))
				{
					array_push($attivitaProgrammate,$row4['descrizione']);
					array_push($attivitaProgrammateParentesi,"[".$row4['descrizione']."]");
					array_push($attivitaProgrammateApici,"'".$row4['descrizione']."'");
					echo "<th>".$row4['descrizione']."</th>";
				}
			}
		echo "</tr>";
		if($tipo=="complessivo")
		{
			if(count($attivitaApici)==0)
			{
				$query2="SELECT *
					FROM (SELECT TOP (100) PERCENT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,
						(SELECT SUM(nCabine) AS nCabine
						FROM (SELECT TOP (100) PERCENT dbo.programmazione_attivita_programmate.codiceAttivita, dbo.programmazione_attivita_programmate.descrizione, 
						SUM(dbo.programmazione_righe_attivita_programmate.nCabine) AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) 
						AS settimana
						FROM dbo.programmazione_attivita_programmate INNER JOIN
						dbo.programmazione_righe_attivita_programmate ON 
						dbo.programmazione_attivita_programmate.id_attivita_programmata = dbo.programmazione_righe_attivita_programmate.attivita_programmata
						WHERE (dbo.programmazione_righe_attivita_programmate.ponte LIKE '$ponte') AND (dbo.programmazione_righe_attivita_programmate.firezone LIKE '$firezone') AND 
						(dbo.programmazione_attivita_programmate.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY dbo.programmazione_attivita_programmate.codiceAttivita, dbo.programmazione_attivita_programmate.descrizione, CONVERT(DECIMAL(10, 2), 
						{ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) })
						HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) 
						> '$settimanaInizio') AND (dbo.programmazione_attivita_programmate.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
						ORDER BY settimana) AS derivedtbl_1
						WHERE (settimana <= v2.settimana) AND (descrizione = v2.descrizione)) AS nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, SUM(programmazione_righe_attivita_programmate_1.nCabine) 
						AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) AS settimana
						FROM dbo.programmazione_attivita_programmate AS programmazione_attivita_programmate_1 INNER JOIN
						dbo.programmazione_righe_attivita_programmate AS programmazione_righe_attivita_programmate_1 ON 
						programmazione_attivita_programmate_1.id_attivita_programmata = programmazione_righe_attivita_programmate_1.attivita_programmata
						WHERE (programmazione_righe_attivita_programmate_1.ponte LIKE '$ponte') AND (programmazione_righe_attivita_programmate_1.firezone LIKE '$firezone') AND 
						(programmazione_attivita_programmate_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) })
						HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) > '$settimanaInizio') AND 
						(programmazione_attivita_programmate_1.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
						ORDER BY settimana) AS v2
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaProgrammateParentesi).")) p";
			}
			if(count($attivitaProgrammateApici)==0)
			{
				$query2="SELECT *
					FROM (
				SELECT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,
						(SELECT SUM(nCabine) AS nCabine
						FROM (SELECT TOP (100) PERCENT dbo.programmazione_attivita.descrizione, dbo.programmazione_attivita.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) 
						} AS settimana
						FROM dbo.programmazione_attivita INNER JOIN
						dbo.attivitasvoltenoaccento INNER JOIN
						dbo.tblDettagliAttSvolte ON dbo.attivitasvoltenoaccento.IDAttSvolte = dbo.tblDettagliAttSvolte.IDAttSvolteDettagli ON 
						dbo.programmazione_attivita.codice_attivita = dbo.attivitasvoltenoaccento.codice_attivita
						WHERE (dbo.tblDettagliAttSvolte.Ponte LIKE N'$ponte') AND (dbo.tblDettagliAttSvolte.FireZone LIKE N'$firezone') AND ((dbo.attivitasvoltenoaccento.NomeDitta LIKE N'$ditta') OR
						(dbo.attivitasvoltenoaccento.NomeDitta IS NULL)) AND (attivitasvoltenoaccento.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY dbo.programmazione_attivita.descrizione, dbo.programmazione_attivita.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (dbo.programmazione_attivita.descrizione IN (".implode(",",$attivitaApici)."))
						ORDER BY settimana) AS derivedtbl_1
						WHERE (settimana <= v2.settimana) AND (descrizione = v2.descrizione)) AS nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } AS settimana
						FROM dbo.programmazione_attivita AS programmazione_attivita_1 INNER JOIN
						dbo.attivitasvoltenoaccento AS attivitasvoltenoaccento_1 INNER JOIN
						dbo.tblDettagliAttSvolte AS tblDettagliAttSvolte_1 ON attivitasvoltenoaccento_1.IDAttSvolte = tblDettagliAttSvolte_1.IDAttSvolteDettagli ON 
						programmazione_attivita_1.codice_attivita = attivitasvoltenoaccento_1.codice_attivita
						WHERE (tblDettagliAttSvolte_1.Ponte LIKE N'$ponte') AND (tblDettagliAttSvolte_1.FireZone LIKE N'$firezone') AND ((attivitasvoltenoaccento_1.NomeDitta LIKE N'$ditta') OR
						(attivitasvoltenoaccento_1.NomeDitta IS NULL)) AND (attivitasvoltenoaccento_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', 
						CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (programmazione_attivita_1.descrizione IN (".implode(",",$attivitaApici).")) 
						ORDER BY settimana) AS v2
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaParentesi).")) p";
			}
			if(count($attivitaProgrammateApici)>0 && count($attivitaApici)>0)
			{
				$query2="SELECT *
					FROM (
				SELECT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,
						(SELECT SUM(nCabine) AS nCabine
						FROM (SELECT TOP (100) PERCENT dbo.programmazione_attivita.descrizione, dbo.programmazione_attivita.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) 
						} AS settimana
						FROM dbo.programmazione_attivita INNER JOIN
						dbo.attivitasvoltenoaccento INNER JOIN
						dbo.tblDettagliAttSvolte ON dbo.attivitasvoltenoaccento.IDAttSvolte = dbo.tblDettagliAttSvolte.IDAttSvolteDettagli ON 
						dbo.programmazione_attivita.codice_attivita = dbo.attivitasvoltenoaccento.codice_attivita
						WHERE (dbo.tblDettagliAttSvolte.Ponte LIKE N'$ponte') AND (dbo.tblDettagliAttSvolte.FireZone LIKE N'$firezone') AND ((dbo.attivitasvoltenoaccento.NomeDitta LIKE N'$ditta') OR
						(dbo.attivitasvoltenoaccento.NomeDitta IS NULL)) AND (attivitasvoltenoaccento.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY dbo.programmazione_attivita.descrizione, dbo.programmazione_attivita.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(dbo.attivitasvoltenoaccento.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, dbo.attivitasvoltenoaccento.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (dbo.programmazione_attivita.descrizione IN (".implode(",",$attivitaApici)."))
						ORDER BY settimana) AS derivedtbl_1
						WHERE (settimana <= v2.settimana) AND (descrizione = v2.descrizione)) AS nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } AS settimana
						FROM dbo.programmazione_attivita AS programmazione_attivita_1 INNER JOIN
						dbo.attivitasvoltenoaccento AS attivitasvoltenoaccento_1 INNER JOIN
						dbo.tblDettagliAttSvolte AS tblDettagliAttSvolte_1 ON attivitasvoltenoaccento_1.IDAttSvolte = tblDettagliAttSvolte_1.IDAttSvolteDettagli ON 
						programmazione_attivita_1.codice_attivita = attivitasvoltenoaccento_1.codice_attivita
						WHERE (tblDettagliAttSvolte_1.Ponte LIKE N'$ponte') AND (tblDettagliAttSvolte_1.FireZone LIKE N'$firezone') AND ((attivitasvoltenoaccento_1.NomeDitta LIKE N'$ditta') OR
						(attivitasvoltenoaccento_1.NomeDitta IS NULL)) AND (attivitasvoltenoaccento_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', 
						CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (programmazione_attivita_1.descrizione IN (".implode(",",$attivitaApici)."))
						ORDER BY settimana) AS v2
					UNION ALL
					SELECT TOP (100) PERCENT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,
						(SELECT SUM(nCabine) AS nCabine
						FROM (SELECT TOP (100) PERCENT dbo.programmazione_attivita_programmate.codiceAttivita, dbo.programmazione_attivita_programmate.descrizione, 
						SUM(dbo.programmazione_righe_attivita_programmate.nCabine) AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) 
						AS settimana
						FROM dbo.programmazione_attivita_programmate INNER JOIN
						dbo.programmazione_righe_attivita_programmate ON 
						dbo.programmazione_attivita_programmate.id_attivita_programmata = dbo.programmazione_righe_attivita_programmate.attivita_programmata
						WHERE (dbo.programmazione_righe_attivita_programmate.ponte LIKE '$ponte') AND (dbo.programmazione_righe_attivita_programmate.firezone LIKE '$firezone') AND 
						(dbo.programmazione_attivita_programmate.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY dbo.programmazione_attivita_programmate.codiceAttivita, dbo.programmazione_attivita_programmate.descrizione, CONVERT(DECIMAL(10, 2), 
						{ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) })
						HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						dbo.programmazione_righe_attivita_programmate.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), dbo.programmazione_righe_attivita_programmate.settimana)) }, 2)) }) 
						> '$settimanaInizio') AND (dbo.programmazione_attivita_programmate.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
						ORDER BY settimana) AS derivedtbl_1
						WHERE (settimana <= v2.settimana) AND (descrizione = v2.descrizione)) AS nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, SUM(programmazione_righe_attivita_programmate_1.nCabine) 
						AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) AS settimana
						FROM dbo.programmazione_attivita_programmate AS programmazione_attivita_programmate_1 INNER JOIN
						dbo.programmazione_righe_attivita_programmate AS programmazione_righe_attivita_programmate_1 ON 
						programmazione_attivita_programmate_1.id_attivita_programmata = programmazione_righe_attivita_programmate_1.attivita_programmata
						WHERE (programmazione_righe_attivita_programmate_1.ponte LIKE '$ponte') AND (programmazione_righe_attivita_programmate_1.firezone LIKE '$firezone') AND 
						(programmazione_attivita_programmate_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) })
						HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
						programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) > '$settimanaInizio') AND 
						(programmazione_attivita_programmate_1.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
						ORDER BY settimana) AS v2
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaParentesi).",".implode(",",$attivitaProgrammateParentesi).")) p";
			}
		}
		else
		{
			if(count($attivitaApici)==0)
			{
				$query2="SELECT *
				FROM (
					SELECT TOP (100) PERCENT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,nCabine
						FROM (SELECT TOP (100) PERCENT programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, SUM(programmazione_righe_attivita_programmate_1.nCabine) 
							AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
							programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) AS settimana
							FROM dbo.programmazione_attivita_programmate AS programmazione_attivita_programmate_1 INNER JOIN
							dbo.programmazione_righe_attivita_programmate AS programmazione_righe_attivita_programmate_1 ON 
							programmazione_attivita_programmate_1.id_attivita_programmata = programmazione_righe_attivita_programmate_1.attivita_programmata
							WHERE (programmazione_righe_attivita_programmate_1.ponte LIKE '$ponte') AND (programmazione_righe_attivita_programmate_1.firezone LIKE '$firezone') AND 
							(programmazione_attivita_programmate_1.commessa = ".$_SESSION['id_commessa'].")
							GROUP BY programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
							programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) })
							HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
							programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
							programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) > '$settimanaInizio') AND 
							(programmazione_attivita_programmate_1.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
							ORDER BY settimana) AS v2
						ORDER BY settimana
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaProgrammateParentesi).")) p ";
			}
			if(count($attivitaProgrammateApici)==0)
			{
				$query2="SELECT *
				FROM (
				SELECT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } AS settimana
						FROM dbo.programmazione_attivita AS programmazione_attivita_1 INNER JOIN
						dbo.attivitasvoltenoaccento AS attivitasvoltenoaccento_1 INNER JOIN
						dbo.tblDettagliAttSvolte AS tblDettagliAttSvolte_1 ON attivitasvoltenoaccento_1.IDAttSvolte = tblDettagliAttSvolte_1.IDAttSvolteDettagli ON 
						programmazione_attivita_1.codice_attivita = attivitasvoltenoaccento_1.codice_attivita
						WHERE        (tblDettagliAttSvolte_1.Ponte LIKE N'$ponte') AND (tblDettagliAttSvolte_1.FireZone LIKE N'$firezone') AND (attivitasvoltenoaccento_1.NomeDitta LIKE N'$ditta' OR
                         attivitasvoltenoaccento_1.NomeDitta IS NULL) AND (attivitasvoltenoaccento_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', 
						CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (programmazione_attivita_1.descrizione IN (".implode(",",$attivitaApici)."))
						ORDER BY settimana) AS v2
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaParentesi).")) p ";
			}
			//echo $query2;
			if(count($attivitaProgrammateApici)>0 && count($attivitaApici)>0)
			{
				$query2="SELECT *
				FROM (
				SELECT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,nCabine
					FROM (SELECT TOP (100) PERCENT programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, COUNT(*) AS nCabine, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
						YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } AS settimana
						FROM dbo.programmazione_attivita AS programmazione_attivita_1 INNER JOIN
						dbo.attivitasvoltenoaccento AS attivitasvoltenoaccento_1 INNER JOIN
						dbo.tblDettagliAttSvolte AS tblDettagliAttSvolte_1 ON attivitasvoltenoaccento_1.IDAttSvolte = tblDettagliAttSvolte_1.IDAttSvolteDettagli ON 
						programmazione_attivita_1.codice_attivita = attivitasvoltenoaccento_1.codice_attivita
						WHERE (tblDettagliAttSvolte_1.Ponte LIKE N'$ponte') AND (tblDettagliAttSvolte_1.FireZone LIKE N'$firezone') AND ((attivitasvoltenoaccento_1.NomeDitta LIKE N'$ditta') OR
						(attivitasvoltenoaccento_1.NomeDitta IS NULL)) AND (attivitasvoltenoaccento_1.commessa = ".$_SESSION['id_commessa'].")
						GROUP BY programmazione_attivita_1.descrizione, programmazione_attivita_1.codice_attivita, { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, 
						RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) }
						HAVING  ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), DATEPART(ww, 
						attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } > '$settimanaInizio') AND ({ fn CONCAT({ fn CONCAT(CONVERT(varchar(4), YEAR(attivitasvoltenoaccento_1.datasvolgimento)), '.') }, RIGHT({ fn CONCAT('0', 
						CONVERT(varchar(3), DATEPART(ww, attivitasvoltenoaccento_1.datasvolgimento))) }, 2)) } < '$settimanaFine') AND (programmazione_attivita_1.descrizione IN (".implode(",",$attivitaApici)."))
						ORDER BY settimana) AS v2
					UNION ALL
					SELECT TOP (100) PERCENT descrizione, REPLACE(CAST(settimana AS varchar(10)), '.', '_') AS settimana,nCabine
						FROM (SELECT TOP (100) PERCENT programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, SUM(programmazione_righe_attivita_programmate_1.nCabine) 
							AS nCabine, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
							programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) AS settimana
							FROM dbo.programmazione_attivita_programmate AS programmazione_attivita_programmate_1 INNER JOIN
							dbo.programmazione_righe_attivita_programmate AS programmazione_righe_attivita_programmate_1 ON 
							programmazione_attivita_programmate_1.id_attivita_programmata = programmazione_righe_attivita_programmate_1.attivita_programmata
							WHERE (programmazione_righe_attivita_programmate_1.ponte LIKE '$ponte') AND (programmazione_righe_attivita_programmate_1.firezone LIKE '$firezone') AND 
							(programmazione_attivita_programmate_1.commessa = ".$_SESSION['id_commessa'].")
							GROUP BY programmazione_attivita_programmate_1.codiceAttivita, programmazione_attivita_programmate_1.descrizione, CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
							programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) })
							HAVING  (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), 
							programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) < '$settimanaFine') AND (CONVERT(DECIMAL(10, 2), { fn CONCAT({ fn CONCAT(CONVERT(varchar(4), 
							programmazione_righe_attivita_programmate_1.anno), '.') }, RIGHT({ fn CONCAT('0', CONVERT(varchar(3), programmazione_righe_attivita_programmate_1.settimana)) }, 2)) }) > '$settimanaInizio') AND 
							(programmazione_attivita_programmate_1.descrizione IN (".implode(",",$attivitaProgrammateApici)."))
							ORDER BY settimana) AS v2
						ORDER BY settimana
					) t  PIVOT (SUM(nCabine) FOR descrizione IN (".implode(",",$attivitaParentesi).",".implode(",",$attivitaProgrammateParentesi).")) p ";
			}
		}
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
				echo "<tr>";
					echo "<td>".$row2["settimana"]."</td>";
					if(count($attivitaApici)==0)
					{
						foreach ($attivitaProgrammate as $value) 
						{
							echo "<td>".$row2[$value]."</td>";
						}
					}
					if(count($attivitaProgrammateApici)==0)
					{
						foreach ($attivita as $value) 
						{
							echo "<td>".$row2[$value]."</td>";
						}
					}
					if(count($attivitaProgrammateApici)>0 && count($attivitaApici)>0)
					{
						foreach ($attivita as $value) 
						{
							echo "<td>".$row2[$value]."</td>";
						}
						foreach ($attivitaProgrammate as $value) 
						{
							echo "<td>".$row2[$value]."</td>";
						}
					}
				echo "</tr>";
			}
		}
	echo "</table>";
	//echo $query2;
?>



