<?php
	include "connessione.php";
	include "Session.php";
	
	/*$anno=$_REQUEST['anno'];
	$ponte=$_REQUEST['ponte'];
	$mese=$_REQUEST['mese'];
	
	if($ponte=="%")
	{
		if($mese=="%")
		{
			$query2="SELECT        TOP (100) PERCENT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, nomeDitta
	FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta
							  FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
																				  MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
																				  dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
																				  dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
																				  dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
														GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
																				  dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) LIKE '$ponte')) AS derivedtbl_1
							  GROUP BY data, mese, nomeDitta) AS derivedtbl_2
	GROUP BY nomeDitta";
		}
		else
		{
			$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, nomeDitta
	FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta
							  FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
																				  MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
																				  dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
																				  dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
																				  dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
														GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
																				  dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) LIKE '$ponte')) AS derivedtbl_1
							  GROUP BY data, mese, nomeDitta) AS derivedtbl_2
	WHERE        (mese = $mese)
	GROUP BY nomeDitta";
		}
	}
	else
	{
		if($mese=="%")
		{
			$query2="SELECT        TOP (100) PERCENT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, nomeDitta
	FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta
							  FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
																				  MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
																				  dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
																				  dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
																				  dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
														GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
																				  dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) IN ($ponte))) AS derivedtbl_1
							  GROUP BY data, mese, nomeDitta) AS derivedtbl_2
	GROUP BY nomeDitta";
		}
		else
		{
			$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, nomeDitta
	FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta
							  FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
																				  MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
																				  dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
																				  dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
																				  dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
														GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
																				  dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
														HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) IN ($ponte))) AS derivedtbl_1
							  GROUP BY data, mese, nomeDitta) AS derivedtbl_2
	WHERE        (mese = $mese)
	GROUP BY nomeDitta";
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
			echo $row2["nomeDitta"]."|".$row2["mediaOperatori"]."%";
		}
	}*/

	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$mesi=json_decode($_REQUEST['JSONmesi']);
	$inMesi=implode("','",$mesi);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);

	$query2="SELECT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, nomeDitta
			FROM (SELECT TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta
			FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
			MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
			FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
			dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
			dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
			dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
			GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
			dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
			HAVING (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) IN ('".$inPonti."'))) AS derivedtbl_1
			GROUP BY data, mese, nomeDitta) AS derivedtbl_2
			WHERE (mese IN ('".$inMesi."'))
			GROUP BY nomeDitta";
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
			echo $row2["nomeDitta"]."|".$row2["mediaOperatori"]."%";
		}
	}

?>