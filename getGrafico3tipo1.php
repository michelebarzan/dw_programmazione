<?php
	include "connessione.php";
	include "Session.php";
	
	/*$anno=$_REQUEST['anno'];
	$ditta=$_REQUEST['ditta'];
	$ponte=$_REQUEST['ponte'];
	
	if($ponte=="%")
	{
		if($ditta=="%")
		{
			$query2="SELECT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
					FROM (SELECT TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese
						FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
						MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
						FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
						dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
						dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
						dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
						GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
						dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
						HAVING (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) LIKE '$ponte')) AS derivedtbl_1
						WHERE (nomeDitta LIKE '$ditta')
						GROUP BY data, mese) AS derivedtbl_2
					GROUP BY mese";
		}
		else
		{
			$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, nomeDitta, data, mese
                          FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
                                                                              MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
                                                    FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
                                                                              dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
                                                                              dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
                                                                              dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                                                    GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
                                                                              dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
                                                    HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) LIKE '$ponte')) AS derivedtbl_1
                          WHERE        (nomeDitta LIKE '$ditta')
                          GROUP BY nomeDitta, data, mese
                          ORDER BY nomeDitta) AS derivedtbl_2
GROUP BY mese";
		}
	}
	else
	{
		if($ditta=="%")
		{
			$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese
                          FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
                                                                              dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
                                                    FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
                                                                              dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
                                                                              dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
                                                                              dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                                                    WHERE        (dbo.cantiere_registrazioni.commessa =".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ($ponte))) AS derivedtbl_1
                          WHERE        (nomeDitta LIKE '$ditta')
                          GROUP BY data, mese) AS derivedtbl_2
GROUP BY mese";
		}
		else
		{
			$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, nomeDitta, data, mese
                          FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
                                                                              dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
                                                    FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
                                                                              dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
                                                                              dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
                                                                              dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                                                    WHERE        (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ($ponte))) AS derivedtbl_1
                          WHERE        (nomeDitta LIKE '$ditta')
                          GROUP BY nomeDitta, data, mese
                          ORDER BY nomeDitta) AS derivedtbl_2
GROUP BY mese";
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
			echo $row2["mese"]."|".$row2["mediaOperatori"]."%";
		}
	}*/

	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$ditteEnc=json_decode($_REQUEST['JSONditte']);
	$ditte=[];
	foreach ($ditteEnc as $ditta)
	{ 
		array_push($ditte,urldecode($ditta));
	} 
	$inDitte=implode("','",$ditte);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);
	
	$festivi=$_REQUEST['festivi'];
	$filterFestivi="";
	if($festivi=="false")
		$filterFestivi="AND giorno NOT IN ('sunday','saturday')";

	$query2="SELECT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
	FROM (SELECT TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese,giorno
	FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
	dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data,DATENAME(dw, CAST(DATEPART(m, dbo.cantiere_registrazioni.data) AS VARCHAR) 
                         + '/' + CAST(DATEPART(d, dbo.cantiere_registrazioni.data) AS VARCHAR) + '/' + CAST(DATEPART(yy, dbo.cantiere_registrazioni.data) AS VARCHAR)) AS giorno
	FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
	dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
	dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
	dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
	WHERE (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."'))) AS derivedtbl_1
	WHERE (nomeDitta IN ('".$inDitte."')) $filterFestivi
	GROUP BY  data, mese,giorno) AS derivedtbl_2
	GROUP BY mese";

	/*$query2="SELECT AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, mese
			FROM (SELECT TOP (100) PERCENT COUNT(*) AS nOperatori, nomeDitta, data, mese
			FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
			dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
			FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
			dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
			dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
			dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
			WHERE (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."'))) AS derivedtbl_1
			WHERE (nomeDitta IN ('".$inDitte."'))
			GROUP BY nomeDitta, data, mese) AS derivedtbl_2
			GROUP BY mese";*/
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
			echo $row2["mese"]."|".$row2["mediaOperatori"]."%";
		}
	}
	//echo $query2;
?>