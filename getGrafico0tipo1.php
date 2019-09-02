<?php
	include "connessione.php";
	include "Session.php";
	
	/*$anno=$_REQUEST['anno'];
	$ponte=$_REQUEST['ponte'];
	$mese=$_REQUEST['mese'];
		if($ponte=="%")
		{
		$query2="SELECT COUNT(*) AS nOperatori, nomeDitta
	FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
							MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa
	FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
							dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
							dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
							dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
	GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), dbo.cantiere_registrazioni.data, 
							YEAR(dbo.cantiere_registrazioni.data), dbo.cantiere_registrazioni.commessa
							HAVING  (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa']." AND YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) LIKE '$ponte') AND (MONTH(dbo.cantiere_registrazioni.data) LIKE '$mese')) AS derivedtbl_1
	GROUP BY nomeDitta";	
		}
		else
		{
			$query2="SELECT COUNT(*) AS nOperatori, nomeDitta
	FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
														dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
							FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
														dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
														dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
														dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
							WHERE (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) = $anno) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ($ponte)) AND 
														(MONTH(dbo.cantiere_registrazioni.data) LIKE '$mese')) AS derivedtbl_1
	GROUP BY nomeDitta";
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
				echo $row2["nomeDitta"]."|".$row2["nOperatori"]."%";
			}
		}
	*/
	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$mesi=json_decode($_REQUEST['JSONmesi']);
	$inMesi=implode("','",$mesi);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);
	
	$query2="SELECT COUNT(*) AS nOperatori, nomeDitta
			FROM (
				SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
					dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
				FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
					dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
					dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
					dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
				WHERE (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."')) AND 
					(MONTH(dbo.cantiere_registrazioni.data) IN ('".$inMesi."'))
				) AS derivedtbl_1
			GROUP BY nomeDitta";
			//echo $query2;
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
			echo $row2["nomeDitta"]."|".$row2["nOperatori"]."%";
		}
	}

?>