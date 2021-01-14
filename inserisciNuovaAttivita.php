<?php

	include "connessione.php";
	include "Session.php";
		
	$marinaarredo=$_REQUEST['marinaarredo'];
	$kitpref=$_REQUEST['kitpref'];
	if($kitpref=="kitpref")
	{
		$pref="'PREF'";
		$kit="'KIT'";
	}
	else
	{
		if($kitpref=="kit")
			$kit="'KIT'";
		else
			$kit="NULL";
		if($kitpref=="pref")
			$pref="'PREF'";
		else
			$pref="NULL";
	}
	$note=$_REQUEST['note'];
	$colore=$_REQUEST['colore'];
	$dashType=$_REQUEST['dashType'];
	if($colore==null || $colore=='')
		$colore="A0A0A0";
	if($dashType==null || $dashType=='')
		$dashType="solid";
	
	//echo "kitpref-->$kitpref | kit-->$kit | pref-->$pref";
	$descrizione=$_REQUEST['descrizione'];
	if(strpos($descrizione, '"') || strpos($descrizione, "'") || strpos($descrizione, '|') || strpos($descrizione, '£') || strpos($descrizione, '$') || strpos($descrizione, '%') || strpos($descrizione, '&') || strpos($descrizione, '/') || strpos($descrizione, '=') || strpos($descrizione, '(') || strpos($descrizione, ')') || strpos($descrizione, '[') || strpos($descrizione, ']') || strpos($descrizione, '{') || strpos($descrizione, '}') || strpos($descrizione, '@') || strpos($descrizione, '#') || strpos($descrizione, '-') || strpos($descrizione, '<') || strpos($descrizione, '>') || strpos($descrizione, '*') || strpos($descrizione, '+') || strpos($descrizione, '.') || strpos($descrizione, ',') || strpos($descrizione, ';') || strpos($descrizione, ':'))
	{
		echo "La descrizione deve contenere solo numeri e lettere non accentate.";
	}
	else
	{
		//Controllo se l' anagrafica dell' attivita esiste
		$q="SELECT * FROM Attivita WHERE (dbo.Attivita.Descrizione = '$descrizione')";
		$r=sqlsrv_query($conn,$q);
		if($r==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			$rows = sqlsrv_has_rows( $r );
			if ($rows === true)
			{
				//Se l' anagrafica esiste
				//Controllo se è gia stata dichiarata per questa commessa
				$q2="SELECT dbo.Attivita.CodiceAttivita, dbo.programmazione_attivita_commessa.commessa, dbo.Attivita.eliminata
					FROM dbo.Attivita INNER JOIN
					dbo.programmazione_attivita_commessa ON dbo.Attivita.CodiceAttivita = dbo.programmazione_attivita_commessa.codice_attivita
					WHERE (dbo.Attivita.Descrizione = '$descrizione') AND (dbo.programmazione_attivita_commessa.commessa = ".$_SESSION['id_commessa'].")";
				$r2=sqlsrv_query($conn,$q2);
				if($r2==FALSE)
				{
					echo "<br><br>Errore esecuzione query<br>Query: ".$q2."<br>Errore: ";
					die(print_r(sqlsrv_errors(),TRUE));
				}
				else
				{
					$rows2 = sqlsrv_has_rows( $r2 );
					if ($rows2 === true)
					{
						//Se e' gia stata dichiarata per questa commessa
						//Stampo errore
						echo "L' attivita $descrizione esiste gia per la commessa ".$_SESSION['commessa'];
					}
					else
					{
						//Se non e' gia stata dichiarata per questa commessa
						//Assegno l' attivita a questa commessa
						$query3="INSERT INTO [dbo].[programmazione_attivita_commessa]
								([codice_attivita]
								,[commessa])
								VALUES
								(".getCodiceAttivita($conn,$descrizione).",".$_SESSION['id_commessa'].")";
						$result3=sqlsrv_query($conn,$query3);
						if($result3==FALSE)
						{
							echo "<br><br>Errore esecuzione query<br>Query: ".$query3."<br>Errore: ";
							die(print_r(sqlsrv_errors(),TRUE));
						}
						else
						{
							echo "ok";
						}
					}
				}
			}
			else
			{
				//Se l 'anagrafica non esiste
				//Trovo un nuovo codice per la nuova attivita
				$query2="SELECT MAX(CodiceAttivita) AS CodiceAttivita FROM Attivita";
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
						$codice_attivita=$row2['CodiceAttivita']+1;
					}
				}
				//Inserisco la nuova anagrafica
				$queryRighe="INSERT INTO [dbo].[Attivita]
						   ([CodiceAttivita]
						   ,[Descrizione] 
						   ,[Kit]
						   ,[Pref]
						   ,[MarinaArredo]
						   ,[colore]
						   ,[dashType]
						   ,[note]
						   ,[eliminata],
						   posizione)
							VALUES
						   ($codice_attivita,'$descrizione',$kit,$pref,'$marinaarredo','$colore','$dashType','$note','false',(select max(posizione)+1 from Attivita))";
				$resultRighe=sqlsrv_query($conn,$queryRighe);
				if($resultRighe==FALSE)
				{
					echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
					die(print_r(sqlsrv_errors(),TRUE));
				}
				else
				{
					//Dichiaro l' attivita per questa commessa
					$query3="INSERT INTO [dbo].[programmazione_attivita_commessa]
							([codice_attivita]
							,[commessa])
							VALUES
						($codice_attivita,".$_SESSION['id_commessa'].")";
					$result3=sqlsrv_query($conn,$query3);
					if($result3==FALSE)
					{
						echo "<br><br>Errore esecuzione query<br>Query: ".$query3."<br>Errore: ";
						die(print_r(sqlsrv_errors(),TRUE));
					}
					else
					{
						echo "ok";
					}
				}
			}
		}
	}
	
	
	function getCodiceAttivita($conn,$descrizione)
	{
		$query2="SELECT CodiceAttivita FROM Attivita WHERE Descrizione='$descrizione'";
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
				return $row2['CodiceAttivita'];
			}
		}
	}
?>