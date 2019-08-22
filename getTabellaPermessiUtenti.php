<?php

	include "connessione.php";
	include "Session.php";
	
	$query2="SELECT * FROM utenti";	
	$result2=sqlsrv_query($conn,$query2);
	if($result2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$query2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		echo "<table id='myTableGestioneUtenti'>";
			echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>Nome</th>";
				echo "<th>Cognome</th>";
				echo "<th>Username</th>";
				//echo "<th>Password</th>";
				echo "<th>Gruppi</th>";
				echo "<th>Ponti</th>";
				echo "<th>Firezone</th>";
			echo "</tr>";
		while($row2=sqlsrv_fetch_array($result2))
		{
			echo "<tr>";
				echo "<td>".$row2['id_utente']."</td>";
				echo '<td>'.$row2["nome"].'</td>';
				echo '<td>'.$row2["cognome"].'</td>';
				echo '<td>'.$row2["username"].'</td>';
				//echo '<td><input type="button" value="Cambia password" class="inputPasswordGestioneUtenti" onclick/></td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoGruppi($conn,$row2['id_utente']);
					getListaGruppi($conn,$row2['id_utente']);
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoDeck($conn,$row2['id_utente']);
					getListaDeck($conn,$row2['id_utente']);
				echo '</td>';
				echo '<td style="padding-top:0px;padding-bottom:0px">';
					getElencoFZ($conn,$row2['id_utente']);
					getListaFZ($conn,$row2['id_utente']);
				echo '</td>';
			echo "</tr>";
		}
		echo "</table>";
	}
	
	
	function getElencoGruppi($conn,$utente)
	{
		$queryOperatore="SELECT * FROM gruppi WHERE commessa=".$_SESSION['id_commessa'];
		$resultOperatore=sqlsrv_query($conn,$queryOperatore);
		if($resultOperatore==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryOperatore."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			echo "<select class='selectPermessi' onchange='aggiungiGruppo(".$utente.",this.value)' >";
				echo "<option value='' disabled selected>Gruppo</option>";
				while($rowOperatore=sqlsrv_fetch_array($resultOperatore))
				{
					echo "<option value='".$rowOperatore['id_gruppo']."'>".$rowOperatore['nomeGruppo']."</option>";
				}
			echo "</select>";
		}
	}
	function getListaGruppi($conn,$utente)
	{
		echo '<ul class="listaPermessi" id="listaGruppo'.$utente.'">';
		$queryColonne="SELECT permessi_gruppo.id_permessi_gruppo,permessi_gruppo.gruppo,gruppi.nomeGruppo FROM permessi_gruppo,gruppi WHERE permessi_gruppo.commessa=".$_SESSION['id_commessa']." AND permessi_gruppo.gruppo=gruppi.id_gruppo AND permessi_gruppo.utente=".$utente;
			$resultColonne=sqlsrv_query($conn,$queryColonne);
			if($resultColonne==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($rowColonne=sqlsrv_fetch_array($resultColonne))
				{
					echo '<li class="liListaPermessi" id="liItemGruppo'.$rowColonne["id_permessi_gruppo"].'">'.$rowColonne["nomeGruppo"].'<input type="button" class="btnEliminaPermesso" value="Elimina" onclick="eliminaPermessoGruppo('.$rowColonne["id_permessi_gruppo"].')" /></li>';
				}
			}
		echo '</ul>';
	}
	function getElencoDeck($conn,$utente)
	{
		$queryOperatore="SELECT DISTINCT Deck FROM [tip cab] WHERE commessa=".$_SESSION['id_commessa']." ORDER BY Deck";
		$resultOperatore=sqlsrv_query($conn,$queryOperatore);
		if($resultOperatore==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryOperatore."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			echo "<select class='selectPermessi' onchange='aggiungiPonte(".$utente.",this.value)' >";
				echo "<option value='' disabled selected>Deck</option>";
				while($rowOperatore=sqlsrv_fetch_array($resultOperatore))
				{
					echo "<option value='".$rowOperatore['Deck']."'>".$rowOperatore['Deck']."</option>";
				}
			echo "</select>";
		}
	}
	function getListaDeck($conn,$utente)
	{
		echo '<ul class="listaPermessi" id="listaDeck'.$utente.'">';
		$queryColonne="SELECT id_permessi_ponte,ponte FROM permessi_ponte WHERE commessa=".$_SESSION['id_commessa']." AND utente=".$utente;
			$resultColonne=sqlsrv_query($conn,$queryColonne);
			if($resultColonne==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($rowColonne=sqlsrv_fetch_array($resultColonne))
				{
					echo '<li class="liListaPermessi" id="liItemPonte'.$rowColonne["id_permessi_ponte"].'">'.$rowColonne["ponte"].'<input type="button" class="btnEliminaPermesso" value="Elimina" onclick="eliminaPermessoPonte('.$rowColonne["id_permessi_ponte"].')" /></li>';
				}
			}
		echo '</ul>';
	}
	function getElencoFZ($conn,$utente)
	{
		$queryOperatore="SELECT DISTINCT FZ FROM [tip cab] WHERE commessa=".$_SESSION['id_commessa']." ORDER BY FZ";
		$resultOperatore=sqlsrv_query($conn,$queryOperatore);
		if($resultOperatore==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryOperatore."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			echo "<select class='selectPermessi' onchange='aggiungiFirezone(".$utente.",this.value)' >";
				echo "<option value='' disabled selected>FZ</option>";
				while($rowOperatore=sqlsrv_fetch_array($resultOperatore))
				{
					echo "<option value='".$rowOperatore['FZ']."'>".$rowOperatore['FZ']."</option>";
				}
			echo "</select>";
		}
	}
	function getListaFZ($conn,$utente)
	{
		echo '<ul class="listaPermessi" id="listaFZ'.$utente.'">';
		$queryColonne="SELECT id_permessi_firezone,firezone FROM permessi_firezone WHERE commessa=".$_SESSION['id_commessa']." AND utente=".$utente;
			$resultColonne=sqlsrv_query($conn,$queryColonne);
			if($resultColonne==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$queryColonne."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($rowColonne=sqlsrv_fetch_array($resultColonne))
				{
					echo '<li class="liListaPermessi" id="liItemFirezone'.$rowColonne["id_permessi_firezone"].'">'.$rowColonne["firezone"].'<input type="button" class="btnEliminaPermesso" value="Elimina" onclick="eliminaPermessoFirezone('.$rowColonne["id_permessi_firezone"].')" /></li>';
				}
			}
		echo '</ul>';
	}
?>