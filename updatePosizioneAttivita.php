<?php

	include "connessione.php";
	include "Session.php";
	
	$queries=json_decode($_REQUEST['JSONqueries']);

    foreach ($queries as $query)
    {
        //echo $query."\n";
        $result=sqlsrv_query($conn,$query);
        if($result==FALSE)
            die("error".$query);
    }
?>