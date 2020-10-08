<?php

	include "connessione.php";
	include "Session.php";
        
    $ditte=[];

	$query2="SELECT * FROM cantiere_ditte WHERE eliminata='false' ORDER BY nome";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		die("error");
	}
	else
	{
		while($row2=sqlsrv_fetch_array($result2))
		{
            array_push($ditte,$row2["nome"]);
		}
	}
	echo json_encode($ditte);
?>