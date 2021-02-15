<?php
	include "connessione.php";
	include "Session.php";

    set_time_limit(240);

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
        $dataSeries["toolTipContent"]="Ditta: {name}<br>Mese: {x}<br>Media ore: {y}";
        $dataSeries["showInLegend"]=true;
        $dataSeries["markerSize"]=0;
        $dataSeries["dataPoints"]=[];

        $query2="SELECT TOP (100) PERCENT t3.anno, t3.mese, t3.nome AS ditta, CONVERT(FLOAT, t3.nOre) / CONVERT(FLOAT, t4.nGiorni) AS mediaOre
                FROM (SELECT nome, anno, mese, SUM(ore) AS nOre
                FROM (SELECT DISTINCT 
                dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_registrazioni.commessa, DATEPART(yy, dbo.cantiere_registrazioni.data) AS anno, DATEPART(mm, dbo.cantiere_registrazioni.data) 
                AS mese, dbo.cantiere_ponti_ditte_registrazioni.ponte, SUM(cantiere_ponti_ditte_registrazioni.ore) AS ore
                FROM dbo.cantiere_registrazioni INNER JOIN
                dbo.cantiere_ponti_ditte_registrazioni ON dbo.cantiere_registrazioni.id_registrazione = dbo.cantiere_ponti_ditte_registrazioni.registrazione INNER JOIN
                dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
                GROUP BY dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_registrazioni.commessa, DATEPART(yy, dbo.cantiere_registrazioni.data), DATEPART(mm, dbo.cantiere_registrazioni.data), 
                dbo.cantiere_ponti_ditte_registrazioni.ponte, cantiere_ponti_ditte_registrazioni.ore
                HAVING (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."')) AND (DATEPART(yy, 
                dbo.cantiere_registrazioni.data) IN ('".$inAnni."')) AND (dbo.cantiere_ditte.nome = '$ditta')) AS t1
                GROUP BY nome, anno, mese) AS t3 INNER JOIN
                (SELECT COUNT(data) AS nGiorni, nome, mese, anno
                FROM (SELECT DISTINCT 
                cantiere_registrazioni_1.data, cantiere_ditte_1.nome, cantiere_registrazioni_1.commessa, DATEPART(yy, cantiere_registrazioni_1.data) AS anno, DATEPART(mm, cantiere_registrazioni_1.data) 
                AS mese
                FROM dbo.cantiere_registrazioni AS cantiere_registrazioni_1 INNER JOIN
                dbo.cantiere_ponti_ditte_registrazioni AS cantiere_ponti_ditte_registrazioni_1 ON cantiere_registrazioni_1.id_registrazione = cantiere_ponti_ditte_registrazioni_1.registrazione INNER JOIN
                dbo.cantiere_ditte AS cantiere_ditte_1 ON cantiere_ponti_ditte_registrazioni_1.ditta = cantiere_ditte_1.id_ditta
                WHERE (cantiere_registrazioni_1.commessa = ".$_SESSION['id_commessa'].") AND (cantiere_ponti_ditte_registrazioni_1.ponte IN ('".$inPonti."')) AND (DATEPART(yy, 
                cantiere_registrazioni_1.data) IN ('".$inAnni."')) AND (cantiere_ditte_1.nome = '$ditta')) AS t
                GROUP BY nome, mese, anno) AS t4 ON t3.mese = t4.mese AND t3.nome = t4.nome AND t3.anno = t4.anno
                ORDER BY t3.mese";	
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
                $point["y"]=$row2["mediaOre"];
                array_push($dataSeries["dataPoints"],$point);
            }
        }
        array_push($data,$dataSeries);
    }

    $dataSeries["type"]="line";
    $dataSeries["name"]="TOTALE";
    $dataSeries["toolTipContent"]="Ditta: {name}<br>Mese: {x}<br>Media ore: {y}";
    $dataSeries["showInLegend"]=true;
    $dataSeries["markerSize"]=0;
    $dataSeries["lineDashType"]="dash";
    $dataSeries["color"]="red";
    $dataSeries["dataPoints"]=[];

    $query3="SELECT mese,AVG(mediaOre) AS mediaOre FROM (SELECT TOP (100) PERCENT t3.anno, t3.mese, t3.nome AS ditta, CONVERT(FLOAT, t3.nOre) / CONVERT(FLOAT, t4.nGiorni) AS mediaOre
            FROM (SELECT nome, anno, mese, SUM(ore) AS nOre
            FROM (SELECT DISTINCT 
            dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_registrazioni.commessa, DATEPART(yy, dbo.cantiere_registrazioni.data) AS anno, DATEPART(mm, dbo.cantiere_registrazioni.data) 
            AS mese, dbo.cantiere_ponti_ditte_registrazioni.ponte, SUM(cantiere_ponti_ditte_registrazioni.ore) AS ore
            FROM dbo.cantiere_registrazioni INNER JOIN
            dbo.cantiere_ponti_ditte_registrazioni ON dbo.cantiere_registrazioni.id_registrazione = dbo.cantiere_ponti_ditte_registrazioni.registrazione INNER JOIN
            dbo.cantiere_ditte ON dbo.cantiere_ponti_ditte_registrazioni.ditta = dbo.cantiere_ditte.id_ditta
            GROUP BY dbo.cantiere_registrazioni.data, dbo.cantiere_ditte.nome, dbo.cantiere_registrazioni.commessa, DATEPART(yy, dbo.cantiere_registrazioni.data), DATEPART(mm, dbo.cantiere_registrazioni.data), 
            dbo.cantiere_ponti_ditte_registrazioni.ponte, cantiere_ponti_ditte_registrazioni.ore
            HAVING (dbo.cantiere_registrazioni.commessa = ".$_SESSION['id_commessa'].") AND (dbo.cantiere_ponti_ditte_registrazioni.ponte IN ('".$inPonti."')) AND (DATEPART(yy, 
            dbo.cantiere_registrazioni.data) IN ('".$inAnni."'))) AS t1
            GROUP BY nome, anno, mese) AS t3 INNER JOIN
            (SELECT COUNT(data) AS nGiorni, nome, mese, anno
            FROM (SELECT DISTINCT 
            cantiere_registrazioni_1.data, cantiere_ditte_1.nome, cantiere_registrazioni_1.commessa, DATEPART(yy, cantiere_registrazioni_1.data) AS anno, DATEPART(mm, cantiere_registrazioni_1.data) 
            AS mese
            FROM dbo.cantiere_registrazioni AS cantiere_registrazioni_1 INNER JOIN
            dbo.cantiere_ponti_ditte_registrazioni AS cantiere_ponti_ditte_registrazioni_1 ON cantiere_registrazioni_1.id_registrazione = cantiere_ponti_ditte_registrazioni_1.registrazione INNER JOIN
            dbo.cantiere_ditte AS cantiere_ditte_1 ON cantiere_ponti_ditte_registrazioni_1.ditta = cantiere_ditte_1.id_ditta
            WHERE (cantiere_registrazioni_1.commessa = ".$_SESSION['id_commessa'].") AND (cantiere_ponti_ditte_registrazioni_1.ponte IN ('".$inPonti."')) AND (DATEPART(yy, 
            cantiere_registrazioni_1.data) IN ('".$inAnni."'))) AS t
            GROUP BY nome, mese, anno) AS t4 ON t3.mese = t4.mese AND t3.nome = t4.nome AND t3.anno = t4.anno) AS T GROUP BY mese
            ORDER BY mese";	
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
            $point["x"]=$row3["mese"];
            $point["y"]=$row3["mediaOre"];
            array_push($dataSeries["dataPoints"],$point);
        }
    }
    array_push($data,$dataSeries);

    echo json_encode($data);
    
?>