 <?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Riepilogo presenze ditte";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV30.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<script src="struttura.js"></script>
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
		<script src="https://unpkg.com/simplebar@latest/dist/simplebar.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script src="tableToExcel.js"></script>
		<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css" />
		<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
		<link href="https://unpkg.com/multiple-select@1.3.1/dist/multiple-select.css" rel="stylesheet">
		<script src="https://unpkg.com/multiple-select@1.3.1/dist/multiple-select.js"></script>
		<script src="html2canvas.js"></script>
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script src="canvasjs.min.js"></script>
		<script src="js/riepilogoPresenzeDitte.js"></script>
		<!--<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>-->
		<style>
			.swal2-title
			{
				font-family:'Montserrat',sans-serif;
				font-size:18px;
			}
			.swal2-content
			{
				font-family:'Montserrat',sans-serif;
				font-size:14px;
			}
			.swal2-confirm,.swal2-cancel
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
			.far,.fas,.fal{display:inline-block;float:left;}
		</style>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');grafico0=1;getGrafico0('chartContainer0');grafico1=2;getGrafico1('chartContainer1');grafico2=1;getGrafico2('chartContainer2');grafico3=1;getGrafico3('chartContainer3');grafico6=1;getGrafico6('chartContainer6');grafico7=1;getGrafico7('chartContainer7');getTabellaErrori();getTabella4();removeCircleSpinner()">
		<?php include('struttura.php'); ?>
		<script>newCircleSpinner("Caricamento in corso...")</script>
		<div class="modalPontiRiepilogoPresenzeDitteContainer" id="modalPontiRiepilogoPresenzeDitteContainer">
			<div class="modalPontiRiepilogoPresenzeDitteContainerMiddle">
				<div class="modalPontiRiepilogoPresenzeDitteContainerInner">
					<div class="modalPontiRiepilogoPresenzeDitte">
						<div class="modalPontiRiepilogoPresenzeDitteHeader">
							<div>Scegli i ponti</div>
							<button onclick="chiudiPopupPonti()"></button>
						</div>
						<div class="modalPontiRiepilogoPresenzeDitteBody">
							<?php getCheckboxPonti($conn); ?>
						</div>
						<div class="modalPontiRiepilogoPresenzeDitteFooter">
							<button onclick="chiudiPopupPonti()">Annulla</button>
							<button id="btnConfermaPopupPonti" onclick="">Conferma</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="imageContainer" style="display:none;width:1000px;height:500px"></div>
		<a id="imageContainer2" style="display:none" download></a>
		<div style="position: absolute; left: 50%;top:90px;width:100%">
			<div id="containerRiepilogoPresenzeDitte">
				<div class="containerRiepilogoPresenzeDitteRow" id="containerRiepilogoPresenzeDitteRow0">
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn0">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
 								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte0" onchange="getDatas0(this.value)">
 									<option value="grafico_operatori">Grafico giorni</option>
									<option value="grafico_ore">Grafico ore</option>
									<option value="tabella_operatori">Tabella operatori</option>
									<option value="tabella_ore">Tabella ore</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte0" onclick="getFiltri(0)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,0)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte0"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte0" onclick="toggleFullscreenChart(0,0,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte0)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(0)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(0,0)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer0" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn1">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte1" onchange="getDatas1(this.value)">
 									<option value="grafico_operatori">Grafico giorni</option>
									<option value="grafico_ore">Grafico ore</option>
									<option value="tabella">Tabella</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte1" onclick="getFiltri(1)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,1)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte1"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte1" onclick="toggleFullscreenChart(1,0,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte1)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(1)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(1,0)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer1" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn2">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte2" onchange="getDatas2(this.value)">
									<option value="grafico_operatori">Grafico operatori</option>
									<option value="grafico_ore">Grafico ore</option>
									<option value="tabella_operatori">Tabella operatori</option>
									<option value="tabella_ore">Tabella ore</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte2" onclick="getFiltri(2)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
								<input type="checkbox" class="css-checkbox" id="checkbox2" onchange="tabella2=1;getTabella2();" checked="checked"/>
								<label for="checkbox2" id="checkboxLabel2" name="checkbox1_lbl" class="css-label lite-gray-check"><div>Nomi</div></label>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,2)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte2" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte2" onclick="toggleFullscreenChart(2,0,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte2)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(2)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(2,0)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer2" class="chartContainer"></div>
					</div>
				</div>
				<div class="containerRiepilogoPresenzeDitteRow" id="containerRiepilogoPresenzeDitteRow1">
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn3">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte3" onchange="getDatas3(this.value)">
 									<option value="media_mesi">Media mesi</option>
									<option value="media_ditte">Media ditte</option>
									<option value="tabella">Tabella</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte3" onclick="getFiltri(3)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
								<div id="functionListRiepilogoPresenzeDitteCheckbox3Container">
									<input type="checkbox" id="checkboxGrafico3RiepilogoPresenzeDitte" checked="checked" onchange="getDatas3(document.getElementById('visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte3').value)" />Festivi
								</div>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,3)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte3"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte3" onclick="toggleFullscreenChart(3,1,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte3)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(3)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(3,1)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer3" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn6">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte6" onchange="getDatas6(this.value)">
 									<option value="grafico_operatori">Grafico operatori</option>
									<option value="grafico_ore">Grafico ore</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte6" onclick="getFiltri(6)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,6)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte6"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte6" onclick="toggleFullscreenChart(6,1,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte6)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(6)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(6,1)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer6" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn" id="containerRiepilogoPresenzeDitteColumn7">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<div class="visualizzazioneOrigineDatiHintRiepilogoPresenzeDitte">Visualizzazione dati</div>
								<select class="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte" id="visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte7" onchange="getDatas7(this.value)">
								<option value="grafico_operatori">Grafico operatori</option>
									<option value="grafico_ore">Grafico ore</option>
								</select>
								<button class="buttonFilterRiepilogoPresenzeDitte" id="buttonFilterRiepilogoPresenzeDitte7" onclick="getFiltri(7)">Filtri <i style="margin-left:5px;float:right;height:20px;margin-top:-5px;font-size:10px" class="fas fa-filter iconFilterRiepilogoPresenzeDitte"></i></button>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,7)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte7" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr id="rowButtonRiepilogoPresenzeDitte7" onclick="toggleFullscreenChart(7,1,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte7)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(7)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(7,1)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer7" class="chartContainer"></div>
					</div>					
				</div>
				<div class="containerRiepilogoPresenzeDitteRow" id="containerRiepilogoPresenzeDitteRow3">
					<div class="containerRiepilogoPresenzeDitteTripleColumn" id="containerRiepilogoPresenzeDitteColumn4">
						<div class="functionRiepilogoPresenzeDitte" id="functionRiepilogoPresenzeDitteErrori">
							<div class="functionListRiepilogoPresenzeDitte">
								<span>Riepilogo errori di registrazione</span>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,4)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte4" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="toggleFullscreenChart(4,1,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel('myTableErroriPresenzeDitte')">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(4)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer4" class="chartContainerDoubleTriple"></div>
					</div>
				</div>
				<div class="containerRiepilogoPresenzeDitteRow" id="containerRiepilogoPresenzeDitteRow2">
					<div class="containerRiepilogoPresenzeDitteTripleColumn" id="containerRiepilogoPresenzeDitteColumn5">
						<div class="functionRiepilogoPresenzeDitte" id="functionRiepilogoPresenzeDitte5">
							<div class="functionListRiepilogoPresenzeDitte">
								<span>Dettaglio media operatori delle ditte</span>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,5)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte5" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="toggleFullscreenChart(5,2,this)">
											<td><i class="fad fa-expand-wide" ></i></td>
											<td>Estendi</td>
										</tr>
										<tr onclick="scaricaExcel('myTableDettaglioMediaPresenzeDitte')">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(5)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer5" class="chartContainerDoubleTriple"></div>
					</div>
				</div>
				<div id="imageContainerHD" style="display:inline-block;width:2000px;height:1000px;position:absolute;left:-10000;top:-10000"></div>
			</div>
		</div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>

<?php

	function getCheckboxPonti($conn)
	{
		echo '<div><input type="checkbox" id="checkBoxPonteTutti" onclick="checkTuttiPonti()" checked><span>Tutti</span></div>';
		echo '<div><input type="checkbox" id="checkBoxPontegen" class="checkBoxPonte" onchange="controllaCheckTuttiPonti()" checked><span>Gen</span></div>';
		echo '<div><input type="checkbox" id="checkBoxPontepref" class="checkBoxPonte" onchange="controllaCheckTuttiPonti()" checked><span>Pref</span></div>';
		$queryPonte="SELECT * FROM cantiere_ponti WHERE commessa=".$_SESSION['id_commessa']." ORDER BY LEN(ponte), ponte";
		$resultPonte=sqlsrv_query($conn,$queryPonte);
		if($resultPonte==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryPonte."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowPonte=sqlsrv_fetch_array($resultPonte))
			{
				echo '<div><input type="checkbox" id="checkBoxPonte'.$rowPonte["ponte"].'" class="checkBoxPonte" onchange="controllaCheckTuttiPonti()" checked><span>'.$rowPonte["ponte"].'</span></div>';
			}
		}
	}
	function getListaPonti($conn)
	{
		$queryPonte="SELECT * FROM cantiere_ponti WHERE commessa=".$_SESSION['id_commessa']." ORDER BY ponte";
		$resultPonte=sqlsrv_query($conn,$queryPonte);
		if($resultPonte==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryPonte."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowPonte=sqlsrv_fetch_array($resultPonte))
			{
				echo "<option value='".$rowPonte['ponte']."' >".$rowPonte['ponte']."</option>";
			}
		}
	}
	function getListaDitte($conn)
	{
		$queryPonte="SELECT * FROM cantiere_ditte ORDER BY nome";
		$resultPonte=sqlsrv_query($conn,$queryPonte);
		if($resultPonte==FALSE)
		{
			echo "<br><br>Errore esecuzione query<br>Query: ".$queryPonte."<br>Errore: ";
			die(print_r(sqlsrv_errors(),TRUE));
		}
		else
		{
			while($rowPonte=sqlsrv_fetch_array($resultPonte))
			{
				echo "<option value='".$rowPonte['nome']."' >".$rowPonte['nome']."</option>";
			}
		}
	}

?>


