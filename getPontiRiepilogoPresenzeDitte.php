<?php

	include "connessione.php";
	include "Session.php";
        
    $ponti=[];

	$query2="SELECT * FROM cantiere_ponti WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(ponte), ponte";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		die("error");
	}
	else
	{
		while($row2=sqlsrv_fetch_array($result2))
		{
            array_push($ponti,$row2["ponte"]);
		}
	}
	array_push($ponti,"gen");
	array_push($ponti,"pref");
	array_push($ponti,"mag");
	array_push($ponti,"bag");
	echo json_encode($ponti);
?>