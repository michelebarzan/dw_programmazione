 <?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Riepilogo presenze ditte";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV31.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<script src="struttura.js"></script>
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />
		<script src="https://unpkg.com/simplebar@latest/dist/simplebar.js"></script>
		<script src="tableToExcel.js"></script>
		<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css" />
		<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
		<script src="html2canvas.js"></script>
		<script src="canvasjs.min.js"></script>
		<script src="js/riepilogoPresenzeDitte.js"></script>
		<style>
			.far,.fas,.fal{display:inline-block;float:left;}
		</style>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');grafico0=1;getGrafico0('chartContainer0');grafico1=2;getGrafico1('chartContainer1');grafico2=1;getGrafico2('chartContainer2');grafico3=1;getGrafico3('chartContainer3');getTabellaErrori();getTabella4()">
		<?php include('struttura.php'); ?>
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
				<div class="containerRiepilogoPresenzeDitteRow">
					<div class="containerRiepilogoPresenzeDitteColumn">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<i class="far fa-chart-bar" title="Operatori" onclick="grafico0=1;getGrafico0('chartContainer0')"></i>
								<i class="fas fa-chart-bar" title="Ore" onclick="grafico0=2;getGrafico0('chartContainer0')"></i>
								<i class="fal fa-table" title="Operatori" onclick="tabella0=1;getTabella0()"></i>
								<i class="far fa-table" title="Ore" onclick="tabella0=2;getTabella0()"></i>
								<select class="selectValoriGrafici" id="selectAnno0" title="Anno" onchange="getGrafico0('chartContainer0')">
									<option value="2017">2017</option>
									<option value="2018" selected="selected">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
								</select>
								<select class="selectValoriGrafici" id="selectMese0" title="Mese" onchange="getGrafico0('chartContainer0')">
									<option value="%">Tutti</option>
									<option value="1">Gennaio</option>
									<option value="2">Febbraio</option>
									<option value="3">Marzo</option>
									<option value="4">Aprile</option>
									<option value="5">Maggio</option>
									<option value="6">Giugno</option>
									<option value="7">Luglio</option>
									<option value="8">Agosto</option>
									<option value="9">Settembre</option>
									<option value="10">Ottobre</option>
									<option value="11">Novembre</option>
									<option value="12">Dicembre</option>
								</select>
								<button class="selectValoriGrafici" id="selectPonte0" onclick="apriPopupPonti(0)">Ponti<i style="float:right;margin-top:-8px;font-size:95%" class="fas fa-sort-down"></i></button>
								<!--<select class="selectValoriGrafici" id="selectPonte0" title="Ponte" onchange="getGrafico0('chartContainer0')">
									<option value="%">Tutti</option>
									<option value="gen">gen</option>
									<option value="pref">pref</option>
									<?php getListaPonti($conn); ?>
								</select>-->
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,0)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte0"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte0)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(0)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(0)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer0" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<i class="fas fa-chart-area" title="Operatori" onclick="grafico1=2;getGrafico1('chartContainer1')"></i>
								<i class="far fa-chart-area" title="Ore" onclick="grafico1=1;getGrafico1('chartContainer1')"></i>
								<i class="fal fa-table" onclick="getTabella1()"></i>
								<select class="selectValoriGrafici" id="selectAnno1" title="Anno" onchange="getGrafico1('chartContainer1')">
									<option value="2017">2017</option>
									<option value="2018" selected="selected">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
								</select>
								<select class="selectValoriGrafici" id="selectDitta1" title="Ditta" onchange="getGrafico1('chartContainer1')">
									<option value="%">Tutte</option>
									<?php getListaDitte($conn); ?>
								</select>
								<button class="selectValoriGrafici" id="selectPonte1" onclick="apriPopupPonti(1)">Ponti<i style="float:right;margin-top:-8px;font-size:95%" class="fas fa-sort-down"></i></button>
								<!--<select class="selectValoriGrafici" id="selectPonte1" title="Ponte" onchange="getGrafico1('chartContainer1')">
									<option value="%">Tutti</option>
									<option value="gen">gen</option>
									<option value="pref">pref</option>
									<?php getListaPonti($conn); ?>
								</select>-->
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,1)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte1"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte1)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(1)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(1)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer1" class="chartContainer"></div>
					</div>
					<div class="containerRiepilogoPresenzeDitteColumn">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<i class="far fa-chart-bar" title="Operatori" onclick="grafico2=1;getGrafico2('chartContainer2')"></i>
								<i class="fas fa-chart-bar" title="Ore" onclick="grafico2=2;getGrafico2('chartContainer2')"></i>
								<i class="fal fa-table" title="Operatori" onclick="tabella2=1;getTabella2();"></i>
								<i class="far fa-table" title="Ore" onclick="tabella2=2;getTabella2()"></i>
								<select class="selectValoriGrafici" id="selectAnno2" title="Anno" onchange="getGrafico2('chartContainer2')">
									<option value="2017">2017</option>
									<option value="2018" selected="selected">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
								</select>
								<select class="selectValoriGrafici" id="selectDitta2" title="Ditta" onchange="getGrafico2('chartContainer2')">
									<option value="%">Tutte</option>
									<?php getListaDitte($conn); ?>
								</select>
								<button class="selectValoriGrafici" id="selectPonte2" onclick="apriPopupPonti(2)">Ponti<i style="float:right;margin-top:-8px;font-size:95%" class="fas fa-sort-down"></i></button>
								<!--<select class="selectValoriGrafici" id="selectPonte2" title="Ponte" onchange="getGrafico2('chartContainer2')">
									<option value="%">Tutti</option>
									<option value="gen">gen</option>
									<option value="pref">pref</option>
									<?php getListaPonti($conn); ?>
								</select>-->
								<select class="selectValoriGrafici" id="selectMese2" title="Mese" onchange="getGrafico2('chartContainer2')">
									<option value="1">Gennaio</option>
									<option value="2">Febbraio</option>
									<option value="3">Marzo</option>
									<option value="4">Aprile</option>
									<option value="5">Maggio</option>
									<option value="6">Giugno</option>
									<option value="7">Luglio</option>
									<option value="8">Agosto</option>
									<option value="9">Settembre</option>
									<option value="10">Ottobre</option>
									<option value="11">Novembre</option>
									<option value="12">Dicembre</option>
								</select>
								<input type="checkbox" class="css-checkbox" id="checkbox2" onchange="tabella2=1;getTabella2();" checked="checked"/>
								<label for="checkbox2" id="checkboxLabel2" name="checkbox1_lbl" class="css-label lite-gray-check"><div>Nomi</div></label>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,2)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte2" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte2)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(2)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(2)">
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
				<div class="containerRiepilogoPresenzeDitteRow">
					<div class="containerRiepilogoPresenzeDitteColumn">
						<div class="functionRiepilogoPresenzeDitte">
							<div class="functionListRiepilogoPresenzeDitte">
								<i class="fas fa-chart-area" title="Media mesi" onclick="grafico3=1;getGrafico3('chartContainer3')"></i>
								<i class="fas fa-chart-bar" title="Media ditte" onclick="grafico3=2;getGrafico3('chartContainer3')"></i>
								<i class="fal fa-table" onclick="getTabella3();"></i>
								<select class="selectValoriGrafici" id="selectAnno3" title="Anno" onchange="getGrafico3('chartContainer3')">
									<option value="2017">2017</option>
									<option value="2018" selected="selected">2018</option>
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
								</select>
								<select class="selectValoriGrafici" id="selectDitta3" title="Ditta" onchange="getGrafico3('chartContainer3')">
									<option value="%">Tutte</option>
									<?php getListaDitte($conn); ?>
								</select>
								<button class="selectValoriGrafici" id="selectPonte3" onclick="apriPopupPonti(3)">Ponti<i style="float:right;margin-top:-8px;font-size:95%" class="fas fa-sort-down"></i></button>
								<!--<select class="selectValoriGrafici" id="selectPonte3" title="Ponte" onchange="getGrafico3('chartContainer3')">
									<option value="%">Tutti</option>
									<option value="gen">gen</option>
									<option value="pref">pref</option>
									<?php getListaPonti($conn); ?>
								</select>-->
								<select class="selectValoriGrafici" id="selectMese3" title="Mese" onchange="getGrafico3('chartContainer3')">
									<option value="%">Tutti</option>
									<option value="1">Gennaio</option>
									<option value="2">Febbraio</option>
									<option value="3">Marzo</option>
									<option value="4">Aprile</option>
									<option value="5">Maggio</option>
									<option value="6">Giugno</option>
									<option value="7">Luglio</option>
									<option value="8">Agosto</option>
									<option value="9">Settembre</option>
									<option value="10">Ottobre</option>
									<option value="11">Novembre</option>
									<option value="12">Dicembre</option>
								</select>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,3)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte3"  class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
										<tr onclick="scaricaExcel(myTableRiepilogoPresenzeDitte3)">
											<td><i class="fal fa-file-excel" title="Scarica Excel riepilogo" ></i></td>
											<td>Scarica Excel riepilogo</td>
										</tr>
										<tr onclick="stampaRiepilogo(3)">
											<td><i class="fal fa-print" title="Stampa riepilogo" ></i></td>
											<td>Stampa riepilogo</td>
										</tr>
										<tr onclick="scaricaImmagine(3)">
											<td><i class="fal fa-image" title="Scarica riepilogo" ></i></td>
											<td>Scarica immagine</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div id="chartContainer3" class="chartContainer"></div>
					</div>
					<div id="containerTabellaErrori" class="containerRiepilogoPresenzeDitteDoubleColumn">
						<div class="functionRiepilogoPresenzeDitte" id="functionRiepilogoPresenzeDitteErrori">
							<div class="functionListRiepilogoPresenzeDitte">
								<span>Riepilogo errori di registrazione</span>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,4)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte4" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
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
				<div class="containerRiepilogoPresenzeDitteRow">
					<div class="containerRiepilogoPresenzeDitteTripleColumn">
						<div class="functionRiepilogoPresenzeDitte" id="functionRiepilogoPresenzeDitte5">
							<div class="functionListRiepilogoPresenzeDitte">
								<span>Dettaglio media operatori delle ditte</span>
							</div>
							<div class="functionMenuRiepilogoPresenzeDitte">
								<i class="far fa-bars" style="float:right" onclick="openContextMenu(event,5)"></i>
								<div id="contextMenuRiepilogoPresenzeDitte5" style="right:30" class="contextMenuRiepilogoPresenzeDitte">
									<table class="tableContextMenu">
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


