<?php
	include "connessione.php";
	include "Session.php";

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
    
    $data=[];
    foreach ($ditte as $ditta)
    { 
        $dataSeries["type"]="line";
        $dataSeries["name"]=$ditta;
        $dataSeries["toolTipContent"]="Ditta: {name}<br>Mese: {x}<br>Ore: {y}";
        $dataSeries["showInLegend"]=true;
        $dataSeries["markerSize"]=0;
        $dataSeries["dataPoints"]=[];

        $query2="SELECT TOP (100) PERCENT DATEPART(mm, data) AS mese, ditta, commessa, MAX(sommaOre) AS sommaOre
                FROM (SELECT dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome AS ditta, dbo.cantiere_registrazioni.commessa, SUM(dbo.cantiere_ponti_ditte_registrazioni.ore) AS sommaOre
                FROM dbo.cantiere_registrazioni INNER JOIN
                dbo.cantiere_ponti_ditte_registrazioni ON dbo.cantiere_registrazioni.id_registrazione = dbo.cantiere_ponti_ditte_registrazioni.registrazione INNER JOIN
                dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                WHERE (DATEPART(yy, dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (cantiere_ditte.nome ='$ditta') AND (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."'))
                GROUP BY dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_registrazioni.commessa) AS derivedtbl_1
                GROUP BY DATEPART(mm, data), ditta, commessa
                ORDER BY ditta, mese";	
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
                $point["x"]=$row2["mese"];
                $point["y"]=$row2["sommaOre"];
                array_push($dataSeries["dataPoints"],$point);
            }
        }
        array_push($data,$dataSeries);
    }

    echo json_encode($data);
    
?>