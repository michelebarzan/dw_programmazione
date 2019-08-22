<?php

	include "connessione.php";
	include "Session.php";
	
	$CodiceAttivita=$_REQUEST['codice_attivita'];
	$marinaarredo=$_REQUEST['marinaarredo'];
	$descrizione=$_REQUEST['descrizione'];
	if(strpos($descrizione, '"') || strpos($descrizione, "'") || strpos($descrizione, '|') || strpos($descrizione, '£') || strpos($descrizione, '$') || strpos($descrizione, '%') || strpos($descrizione, '&') || strpos($descrizione, '/') || strpos($descrizione, '=') || strpos($descrizione, '(') || strpos($descrizione, ')') || strpos($descrizione, '[') || strpos($descrizione, ']') || strpos($descrizione, '{') || strpos($descrizione, '}') || strpos($descrizione, '@') || strpos($descrizione, '#') || strpos($descrizione, '-') || strpos($descrizione, '<') || strpos($descrizione, '>') || strpos($descrizione, '*') || strpos($descrizione, '+') || strpos($descrizione, '.') || strpos($descrizione, ',') || strpos($descrizione, ';') || strpos($descrizione, ':'))
	{
		echo "La descrizione deve contenere solo numeri e lettere non accentate.";
	}
	else
	{
		$q="SELECT * FROM Attivita WHERE Descrizione='$descrizione' AND CodiceAttivita<>$CodiceAttivita";
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
				echo "Esiste gia un' attivita con questa descrizione.";
			}
			else
			{
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
				
				$queryRighe="UPDATE Attivita SET marinaarredo='$marinaarredo', descrizione='$descrizione', Kit=$kit, Pref=$pref, note='$note',colore='$colore',dashType='$dashType' WHERE CodiceAttivita=$CodiceAttivita";
				$resultRighe=sqlsrv_query($conn,$queryRighe);
				if($resultRighe==FALSE)
				{
					echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
					die(print_r(sqlsrv_errors(),TRUE));
				}
				else
				{
					echo "ok";
				}
			}
		}
	}
	
?>