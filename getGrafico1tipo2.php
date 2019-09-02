<?php
	include "connessione.php";
	include "Session.php";
	
	$anni=json_decode($_REQUEST['JSONanni']);
	$inAnni=implode("','",$anni);

	$ditte=json_decode($_REQUEST['JSONditte']);
	$inDitte=implode("','",$ditte);

	$ponti=json_decode($_REQUEST['JSONponti']);
	$inPonti=implode("','",$ponti);
	

		$query2="SELECT COUNT(*) AS nOperatori, mese
			FROM (SELECT dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
			dbo.cantiere_ponti_ditte_registrazioni.ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
			FROM dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
			dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
			dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
			dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
			WHERE (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (YEAR(dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."')) AND 
			(dbo.cantiere_ditte.nome  IN ('".$inDitte."'))) AS derivedtbl_1
			GROUP BY mese";
	
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
			echo $row2["mese"]."|".$row2["nOperatori"]."%";
		}
	}
	
?>