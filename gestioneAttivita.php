<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione attivita";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV31.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="struttura.js"></script>
		<script src="js/gestioneAttivita.js"></script>
		<link rel="stylesheet" href="css/gestioneAttivita.css" />
		<script src="jscolor.js"></script>
		<script src="jquery.table2excel.js"></script>
		<style>
			.fas,.fal,.far
			{
				font-size:120%;
				cursor:pointer;
			}
		</style>
		<script>
			
		</script>
		<style>
			.swal-text 
			{
				animation: fa-spin 2s infinite linear;
				color: #79A5D1;
			}
			.swal-title 
			{
				font-family:'Montserrat',sans-serif;
				font-size:14px;
			}
		</style>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getElencoAttivita()">
		<?php include('struttura.php'); ?>
		<!-- Modal TipologiaAttivita -->
		<div id="modalTipologiaAttivita" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModalTipologiaAttivita" class="closeModal" onclick='chiudiPopupTipologiaAttivita()'></button>
					<h2>Inserisci i dati</h2>
				</div>
				<div class="modal-body">
					<table class="modalInputTable">
						<tr><td id="modalContainerSelectTipologia"></td></tr>
						<tr><td><input type="number" class="modalInput" id="oreModal" placeholder="Ore" title="Ore" /></td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<input type='button' id='btnConfermaModalTipologiaAttivita' class='btnModal' onclick='inserisciTipologiaAttivita();chiudiPopupTipologiaAttivita()' value='Conferma' />
					<input type='button' id='btnAnnullaModalTipologiaAttivita' class='btnModal' onclick='chiudiPopupTipologiaAttivita()' value='Annulla' />
				</div>
			</div>
		</div>
		<!-- Modal FamigliaAttivita -->
		<div id="modalFamigliaAttivita" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModalFamigliaAttivita" class="closeModal" onclick='chiudiPopupFamigliaAttivita()'></button>
					<h2>Inserisci i dati</h2>
				</div>
				<div class="modal-body">
					<table class="modalInputTable">
						<tr><td id="modalContainerSelectFamiglia"></td></tr>
						<tr><td><input type="number" class="modalInput" id="oreModalFamiglia" placeholder="Ore" title="Ore" /></td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<input type='button' id='btnConfermaModalFamigliaAttivita' class='btnModal' onclick='inserisciFamigliaAttivita();chiudiPopupFamigliaAttivita()' value='Conferma' />
					<input type='button' id='btnAnnullaModalFamigliaAttivita' class='btnModal' onclick='chiudiPopupFamigliaAttivita()' value='Annulla' />
				</div>
			</div>
		</div>
		<!-- Modal DittaAttivita -->
		<div id="modalDittaAttivita" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModalDittaAttivita" class="closeModal" onclick='chiudiPopupDittaAttivita()'></button>
					<h2>Inserisci i dati</h2>
				</div>
				<div class="modal-body">
					<table class="modalInputTable">
						<tr><td>
							<?php
							echo '<select class="modalInput" id="dittaModalDitta">';
							echo '<option value="" disabled selected>Ditta</option>';
							$query2="SELECT * FROM cantiere_ditte ORDER BY nome";	
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
									echo '<option value="'.$row2["id_ditta"].'">'.$row2["nome"].'</option>';
								}
							}
							echo '</select>';
							?>
						</td></tr>
						<tr><td>
							<?php
							echo '<select class="modalInput" id="ponteModalDitta">';
							echo '<option value="" disabled selected>Ponte</option>';
							echo '<option value="*">Tutti</option>';
							$query2="SELECT ponte FROM cantiere_ponti WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(ponte),ponte";	
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
									echo '<option value="'.$row2["ponte"].'">'.$row2["ponte"].'</option>';
								}
							}
							echo '</select>';
							?>
						</td></tr>
						<tr><td>
							<?php
							echo '<select class="modalInput" id="firezoneModalDitta">';
							echo '<option value="" disabled selected>Firezone</option>';
							echo '<option value="*">Tutte</option>';
							$query2="SELECT firezone FROM cantiere_firezone WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(firezone), firezone";	
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
									echo '<option value="'.$row2["firezone"].'">'.$row2["firezone"].'</option>';
								}
							}
							echo '</select>';
							?>
						</td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<input type='button' id='btnConfermaModalDittaAttivita' class='btnModal' onclick='inserisciDittaAttivita();chiudiPopupDittaAttivita()' value='Conferma' />
					<input type='button' id='btnAnnullaModalDittaAttivita' class='btnModal' onclick='chiudiPopupDittaAttivita()' value='Annulla' />
				</div>
			</div>
		</div>
		<div class="modalAssegnazioniSpecificheContainer" id="modalAssegnazioniSpecificheContainer">
			<div class="modalAssegnazioniSpecificheContainerMiddle">
				<div class="modalAssegnazioniSpecificheContainerInner">
					<div class="modalAssegnazioniSpecificheHeader">
						<div>Assegnazioni specifiche</div>
						<button onclick='document.getElementById("modalAssegnazioniSpecificheContainer").style.display="none";'><i class="fal fa-times"></i></button>
					</div>
					<div class="assegnazioniSpecificheLeftContainer">
						<div class="assegnazioniSpecificheSelectLabel">Ponte</div>
						<select class="assegnazioniSpecificheSelect" id="assegnazioniSpecificheSelectPonte">
							<option value="%">Tutti</option>
							<?php getElencoPonti($conn); ?>
						</select>
						<div class="assegnazioniSpecificheSelectLabel">Firezone</div>
						<select class="assegnazioniSpecificheSelect" id="assegnazioniSpecificheSelectFirezone">
							<option value="%">Tutte</option>
							<?php getElencoFirezone($conn); ?>
						</select>
						<div class="assegnazioniSpecificheSelectLabel">Famiglia</div>
						<select class="assegnazioniSpecificheSelect" id="assegnazioniSpecificheSelectFamiglia">
							<option value="%">Tutte</option>
							<?php getElencoFamiglie($conn); ?>
						</select>
						<div class="assegnazioniSpecificheSelectLabel">Tipologia</div>
						<select class="assegnazioniSpecificheSelect" id="assegnazioniSpecificheSelectTipologia">
							<option value="%">Tutte</option>
							<?php getElencoTipologie($conn); ?>
						</select>
						<button class="assegnazioniSpecificheButton" onclick="filtraCabine()">Filtra<i style="margin-left:15px" class="fal fa-filter"></i></button>
					</div>
					<div class="assegnazioniSpecificheRightContainer">
						<!--<div class="assegnazioniSpecificheListTitle">
							<div style="text-align:left;width:250px">
								Cabine selezionate<i style="margin-left:10px;font-size:15px;" onclick="aggiugniCabina(prompt('Numero cabina:'))" class="fal fa-plus-circle"></i>
							</div>
							<div style="text-align:right;width:20px;">
								<input title="Seleziona tutte" id="assegnazioniSpecificheCheckboxTutteOk" type="checkbox" onchange="toggleCheckBoxTutteCabineOk()" style="margin-top:10px">
							</div>
						</div>-->
						<div class="assegnazioniSpecificheListContainer" id="assegnazioniSpecificheListContainer"></div>
						<button class="assegnazioniSpecificheButton" onclick="confermaAssegnazioniSpecifiche()">Conferma<i style="margin-left:15px" class="fal fa-clipboard-check"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="modalRiepilogoAssegnazioniContainer" id="modalRiepilogoAssegnazioniContainer">
			<div class="modalRiepilogoAssegnazioniContainerMiddle">
				<div class="modalRiepilogoAssegnazioniContainerInner">
					<div class="modalRiepilogoAssegnazioniHeader">
						<div>Riepilogo assegnazioni ditte</div>
						<button onclick='document.getElementById("riepilogoAssegnazioniContainer").innerHTML="";document.getElementById("modalRiepilogoAssegnazioniContainer").style.display="none";'><i class="fal fa-times"></i></button>
					</div>
					<div class="riepilogoAssegnazioniFunctionBar">
						<button onclick="document.getElementById('riepilogoAssegnazioniContainer').innerHTML='';riepilogoAssegnazioni()">Ripristina<i class="fas fa-redo-alt" style="margin-left:5px;font-size:12px"></i></button>
						<button onclick="scaricaExcelRiepilogoAssegnazioni()">Esporta<i class="fal fa-file-excel" style="color:green;margin-left:5px;font-size:12px"></i></button>
						<div>Totale ore: <span style="color:#2B586F;" id="totaleOreRiepilogoAssegnazioni"></span></div>
						<div>Totale cabine: <span style="color:#2B586F;" id="totaleCabineRiepilogoAssegnazioni"></span></div>
						<div>Righe: <span style="color:#2B586F;" id="righeRiepilogoAssegnazioni"></span></div>
					</div>
					<div id="riepilogoAssegnazioniContainer"></div>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="containerGestioneAttivita" >
					<div id="elencoAttivita"></div>
					<div id="datiAttivita">
						<div id="titoloAttivita">
							Dati attivita
						</div>
						<input type="text" class="inputTextAttivita" id="descrizioneAttivita" placeholder="Descrizione" />
						<select class="inputTextAttivita" id="marinaArredoAttivita">
							<option value="" disabled selected>Marina/Arredo</option>
							<option value="Marina">Marina</option>
							<option value="Arredo">Arredo</option>
						</select>
						<select class="inputTextAttivita" id="kitprefAttivita">
							<option value="" disabled selected>Kit/Pref</option>
							<option value="kit">Kit</option>
							<option value="pref">Pref</option>
							<option value="kitpref">Kit & Pref</option>
						</select>
						<button class="inputTextAttivita jscolor {valueElement:null,value:'F9F9F9'}" id="coloreAttivita" >Colore</button>
						<select class="inputTextAttivita" placeholder="Colore" id="dashTypeAttivita">
							<option value="" disabled selected>Tipo linea</option>
							<option value="solid">Continua</option>
							<option value="shortDash">Tratteggio 1</option>
							<option value="shortDot">Tratteggio 2</option>
							<option value="shortDashDot">Tratteggio 3</option>
							<option value="shortDashDotDot">Tratteggio 4</option>
							<option value="dot">Tratteggio 5</option>
							<option value="dash">Tratteggio 6</option>
							<option value="dashDot">Tratteggio 7</option>
							<option value="longDash">Tratteggio 8</option>
							<option value="longDashDot">Tratteggio 9</option>
							<option value="longDashDotDot">Tratteggio 10</option>
						</select>
						<textarea class="inputTextAttivita" id="noteAttivita" placeholder="Note" ></textarea>
						<div id="famiglieCabine">
							<div class="titoliElenchiAttivita" style="width:150px">
								Famiglie<i class="fal fa-plus-circle" title="Aggiungi manualmente" onclick="apriPopupFamigliaAttivita()" style="float:right;display:inline-block;color:gray"></i>
							</div>
							<div id="containerFamiglieCabine"></div>
						</div>
						<div id="tipologieCabine">
							<div class="titoliElenchiAttivita" style="width:150px">
								Tipologie<i class="fal fa-plus-circle" title="Aggiungi manualmente" onclick="apriPopupTipologiaAttivita()" style="float:right;display:inline-block;color:gray"></i>
							</div>
							<div id="containerTipologieCabine"></div>
						</div>
						<div id="ditteAttivita">
							<div class="titoliElenchiAttivita" style="width:340px">
								Ditte<i class="fal fa-plus-circle" title="Aggiungi manualmente" onclick="apriPopupDittaAttivita()" style="float:right;display:inline-block;color:gray"></i>
							</div>
							<div id="containerDitteAttivita"></div>
						</div>
						<div id="containerBottomButtonsAttivita">
							<button onclick="confermaAssegnazioni()">Conferma assegnazioni</button>
							<button style="margin-left:20px;margin-right:20px;" onclick="assegnazioniSpecifiche()">Assegnazioni specifiche</button>
							<button onclick="riepilogoAssegnazioni()">Riepilogo assegnazioni ditte</button>
						</div>
					</div>
					<div id="containerBottoniAttivita">
						<input type='button' id='btnSalvaAttivita' class='btnAttivita' onclick='salvaAttivita()' value='Salva modifiche' />
						<input type='button' id='btnEliminaAttivita' class='btnAttivita' onclick='eliminaAttivita()' value='Elimina attivita' >
						<input type='button' id='btnNuovaAttivita' class='btnAttivita' onclick='nuovaAttivita()' value='Nuova attivita' >
					</div>
				</div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>
<?php

	function getElencoPonti($conn)
	{
		$query2="SELECT * FROM cantiere_ponti WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(ponte),ponte";	
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
				echo '<option value="'.$row2["ponte"].'">'.$row2["ponte"].'</td>';
			}
		}
	}
	function getElencoFirezone($conn)
	{
		$query2="SELECT * FROM cantiere_firezone WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(firezone),firezone";	
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
				echo '<option value="'.$row2["firezone"].'">'.$row2["firezone"].'</td>';
			}
		}
	}
	function getElencoFamiglie($conn)
	{
		$queryRighe="SELECT distinct [Famiglia] FROM [tip cab] WHERE [tip cab].commessa=".$_SESSION['id_commessa'];
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<option value='".$rowRighe['Famiglia']."'>".$rowRighe['Famiglia']."</option>";
			}
		}
	}
	function getElencoTipologie($conn)
	{
		$queryRighe="SELECT distinct [Pax/Crew] AS tipologia FROM [tip cab] WHERE [tip cab].commessa=".$_SESSION['id_commessa'];
		$resultRighe=sqlsrv_query($conn,$queryRighe);
		if($resultRighe==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryRighe."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowRighe=sqlsrv_fetch_array($resultRighe))
			{
				echo "<option value='".$rowRighe['tipologia']."'>".$rowRighe['tipologia']."</option>";
			}
		}
	}

?>



