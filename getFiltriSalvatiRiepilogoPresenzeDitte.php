<?php

	include "connessione.php";
	include "Session.php";
	
	$queryRighe="SELECT * FROM filtri_riepilogo_presenze_ditte WHERE utente = ".$_SESSION['id_utente'];
	$resultRighe=sqlsrv_query($conn,$queryRighe);
	if($resultRighe==FALSE)
	{
		die("error");
	}
	else
	{
        while($rowRighe=sqlsrv_fetch_array($resultRighe))
        {
            echo utf8_encode($rowRighe['filtro']);
        }
	}
?>