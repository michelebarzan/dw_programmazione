<?php
	include "session.php";
	include "connessione.php";
		
	if(set_time_limit(240))
	{
		/* Begin the transaction. */
		if ( sqlsrv_begin_transaction( $conn ) === false ) 
		{
			die( print_r( sqlsrv_errors(), true ));
		}

		/* Initialize parameter values. */
		$stmt1=false;
		$stmt2=false;
		$stmt3=false;
		$stmt4=false;
		
		$arrayColonne=[];
		array_push($arrayColonne," ISNULL([00],0) AS [00]");
		$query="SELECT Descrizione FROM programmazione_attivita";
		$result=sqlsrv_query($conn,$query);
		if($result==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$query."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			/*$query2="SELECT * FROM  
					(SELECT numero_cabina,commessa,Descrizione,val 
						FROM   dbo.view_tblInternaCabineAttivita_1 where commessa=".$_SESSION['id_commessa']."
					) AS SourceTable  
					PIVOT  
					(  
					SUM(val)  
					FOR Descrizione IN ([00]";*/
			$query2="SELECT * FROM  
					(SELECT numero_cabina,commessa,Descrizione,val 
						FROM   dbo.view_tblInternaCabineAttivita_1 
					) AS SourceTable  
					PIVOT  
					(  
					SUM(val)  
							FOR Descrizione IN ([00]";
			while($row=sqlsrv_fetch_array($result))
			{
				array_push($arrayColonne," ISNULL([".$row['Descrizione']."],0) AS [".$row['Descrizione']."]");
				$query2=$query2.",[".$row['Descrizione']."]";
			}
			$query2=$query2.")) AS PivotTable";
			$query4="drop view view_tblInternaCabineAttivita_2";
			$stmt1=sqlsrv_query($conn,$query4);
			$query3="create view view_tblInternaCabineAttivita_2 as ".$query2;
			$stmt2=sqlsrv_query($conn,$query3);
			$query5="drop view view_tblInternaCabineAttivita";
			$stmt3=sqlsrv_query($conn,$query5);
			$query6="CREATE VIEW view_tblInternaCabineAttivita AS SELECT numero_cabina AS NCabina, commessa, ".implode(",",$arrayColonne)." FROM view_tblInternaCabineAttivita_2";
			$stmt4=sqlsrv_query($conn,$query6);
		}

		if($stmt1 && $stmt2 && $stmt3 && $stmt4) /* If all queries were successful, commit the transaction. */
		{
			sqlsrv_commit( $conn );
			echo "ok";
		} 
		else /* Otherwise, rollback the transaction. */
		{
			sqlsrv_rollback( $conn );
			echo "Errore";
		}
	}
	else
		die("Errore");
?>