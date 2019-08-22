<?php

	include "connessione.php";
	include "Session.php";
		
	$query2="SELECT        AVG(CAST(nOperatori AS FLOAT)) AS mediaOperatori, YEAR(data) AS anno, mese, nomeDitta, ponte
FROM            (SELECT        TOP (100) PERCENT COUNT(*) AS nOperatori, data, mese, nomeDitta, ponte
                          FROM            (SELECT        dbo.cantiere_ditte.nome AS nomeDitta, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data) AS mese, 
                                                                              MAX(dbo.cantiere_ponti_ditte_registrazioni.ponte) AS ponte, YEAR(dbo.cantiere_registrazioni.data) AS anno, dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data
                                                    FROM            dbo.cantiere_ponti_ditte_registrazioni INNER JOIN
                                                                              dbo.cantiere_operatori_ditte ON dbo.cantiere_ponti_ditte_registrazioni.operatore = dbo.cantiere_operatori_ditte.id_operatore INNER JOIN
                                                                              dbo.cantiere_registrazioni ON dbo.cantiere_ponti_ditte_registrazioni.registrazione = dbo.cantiere_registrazioni.id_registrazione INNER JOIN
                                                                              dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                                                    GROUP BY dbo.cantiere_ditte.nome, dbo.cantiere_operatori_ditte.nome, dbo.cantiere_operatori_ditte.cognome, MONTH(dbo.cantiere_registrazioni.data), YEAR(dbo.cantiere_registrazioni.data), 
                                                                              dbo.cantiere_registrazioni.commessa, dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_ponti_ditte_registrazioni.ponte
                                                    HAVING         (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].")) AS derivedtbl_1
                          GROUP BY data, mese, nomeDitta, ponte) AS derivedtbl_2
GROUP BY YEAR(data), mese, nomeDitta, ponte
ORDER BY anno DESC,mese,nomeDitta";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table class='myTableRiepilogoPresenzeDitte' id='myTableRiepilogoPresenzeDitte3'>";
			echo "<tr>";
				echo "<th>Ditta</th>";
				echo "<th>Mese</th>";
				echo "<th>Anno</th>";
				echo "<th>Ponte</th>";
				echo "<th>Media</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo '<td>'.$row2["nomeDitta"].'</td>';
				echo '<td>'.$row2["mese"].'</td>';
				echo '<td>'.$row2["anno"].'</td>';
				echo '<td>'.$row2["ponte"].'</td>';
				echo '<td>'.$row2["mediaOperatori"].'</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>