<?php

	include "connessione.php";
    include "Session.php";
    
    $filtro=$_REQUEST["filtro"];
    
    $queryRighe1="DELETE FROM filtri_riepilogo_presenze_ditte WHERE utente = ".$_SESSION['id_utente'];
	$resultRighe1=sqlsrv_query($conn,$queryRighe1);
	if($resultRighe1==FALSE)
	{
		die("error");
    }
    else
    {
        $queryRighe="INSERT INTO filtri_riepilogo_presenze_ditte (utente,filtro) VALUES (".$_SESSION['id_utente'].",'$filtro')";
        $resultRighe=sqlsrv_query($conn,$queryRighe);
        if($resultRighe==FALSE)
        {
            die("error");
        }
    }
	
	
?>