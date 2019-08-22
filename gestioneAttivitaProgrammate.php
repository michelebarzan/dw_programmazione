<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione attivita programmate";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV30.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<script src="struttura.js"></script>
		<script src="jscolor.js"></script>
		<style>
			.fas,.fal,.far
			{
				font-size:120%;
				cursor:pointer;
			}
		</style>
		<script>
			var attivitaSelezionata;
			function getElencoAttivitaProgrammate()
			{
				ripristinaMaschera();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("elencoAttivitaProgrammate").innerHTML  =this.responseText;
					}
				};
				xmlhttp.open("POST", "getElencoAttivitaProgrammate.php?", true);
				xmlhttp.send();
			}
			function selezionaAttivitaProgrammata(id_attivita_programmata)
			{
				ripristinaMaschera();
				attivitaSelezionata=id_attivita_programmata;
				var all = document.getElementsByClassName("btnSelezionaAttivitaProgrammate");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].className = "btnSelezionaAttivitaProgrammate";
				}
				var all2 = document.getElementsByClassName("btnSelezionaAttivitaProgrammateClicked");
				for (var i = 0; i < all2.length; i++) 
				{
					all2[i].className = "btnSelezionaAttivitaProgrammate";
				}
				document.getElementById("btnSelezionaAttivitaProgrammate"+id_attivita_programmata).className = "btnSelezionaAttivitaProgrammateClicked";
				var all3 = document.getElementsByClassName("righeAttivitaProgrammate");
				for (var i = 0; i < all3.length; i++) 
				{
					all3[i].style.background = "";
				}
				document.getElementById("rigaAttivitaProgrammata"+id_attivita_programmata).style.background = "#CCE5FF";
				//riempio intestazione
				document.getElementById("titoloAttivitaProgrammata").innerHTML="Dati attivita programmata n. "+id_attivita_programmata;
		
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('codiceAttivitaProgrammata').value=this.responseText.split('|')[0];
						document.getElementById('descrizioneAttivitaProgrammata').value=this.responseText.split('|')[1];
						document.getElementById('kitprefAttivitaProgrammata').value=this.responseText.split('|')[2];
						document.getElementById('coloreAttivitaProgrammata').style.backgroundColor="#"+this.responseText.split('|')[3];
						document.getElementById('dashTypeAttivitaProgrammata').value=this.responseText.split('|')[4];
						document.getElementById('noteAttivitaProgrammata').value=this.responseText.split('|')[5];
					}
				};
				xmlhttp.open("POST", "selezionaAttivitaProgrammata.php?id_attivita_programmata="+id_attivita_programmata, true);
				xmlhttp.send();
				getRigheAttivitaProgrammata(id_attivita_programmata);
			}
			function getRigheAttivitaProgrammata(id_attivita_programmata)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('containerCabineCorrelate').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getRigheAttivitaProgrammata.php?id_attivita_programmata="+id_attivita_programmata, true);
				xmlhttp.send();
			}
			function eliminaTutteRigheAttivitaProgrammata(id_attivita_programmata)
			{
				if(confirm("Eliminare tutte le righe?"))
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText!="ok")
								window.alert("Errore. Impossibile eliminare le righe. Se il problema persiste contattare l' amministratore.");
							else
							{
								getRigheAttivitaProgrammata(id_attivita_programmata);
								document.getElementById('containerCabineCorrelate').style.backgroundColor="white";
								document.getElementById('containerAttivitaCorrelate').style.backgroundColor="white";
							}
						}
					};
					xmlhttp.open("POST", "eliminaTutteRigheAttivitaProgrammata.php?id_attivita_programmata="+id_attivita_programmata, true);
					xmlhttp.send();
				}
			}
			function eliminaRigaAttivitaProgrammata(id_cabina_coinvolta)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
							window.alert("Errore. Impossibile eliminare la riga. Se il problema persiste contattare l' amministratore.");
						else
						{
							getRigheAttivitaProgrammata(attivitaSelezionata);
							document.getElementById('containerCabineCorrelate').style.backgroundColor="white";
							document.getElementById('containerAttivitaCorrelate').style.backgroundColor="white";
						}
					}
				};
				xmlhttp.open("POST", "eliminaRigaAttivitaProgrammata.php?id_cabina_coinvolta="+id_cabina_coinvolta, true);
				xmlhttp.send();
			}
			function nuovaAttivitaProgrammata()
			{
				getElencoAttivitaProgrammate();
				ripristinaMaschera();
				document.getElementById("titoloAttivitaProgrammata").innerHTML="Dati nuova attivita programmata";
				document.getElementById('cabineCorrelate').style.visibility="hidden";
				document.getElementById('attivitaCorrelate').style.visibility="hidden";
			}
			function componentToHex(c) 
			{
				var hex = c.toString(16);
				return hex.length == 1 ? "0" + hex : hex;
			}
			function rgbToHex(r, g, b) 
			{
				return componentToHex(r) + componentToHex(g) + componentToHex(b);
			}
			function salvaAttivitaProgrammata()
			{
				if(attivitaSelezionata=="")
				{
					if(document.getElementById('cabineCorrelate').style.visibility=="hidden")
					{
						var codice=document.getElementById('codiceAttivitaProgrammata').value;
						var descrizione=document.getElementById('descrizioneAttivitaProgrammata').value;
						var coloreRGB=document.getElementById('coloreAttivitaProgrammata').style.backgroundColor;
						var colorsOnly = coloreRGB.substring(coloreRGB.indexOf('(') + 1, coloreRGB.lastIndexOf(')')).split(/,\s*/);
						var red = colorsOnly[0];
						var green = colorsOnly[1];
						var blue = colorsOnly[2];
						var colore=rgbToHex(parseInt(red),parseInt(green),parseInt(blue));
						var dashType=document.getElementById('dashTypeAttivitaProgrammata').value;
						var kitpref=document.getElementById('kitprefAttivitaProgrammata').value;
						var note=document.getElementById('noteAttivitaProgrammata').value;
						if(codice=='' || descrizione=='')
						{
							window.alert("I campi codice e descrizione sono obbligatori");
						}
						else
						{
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									if(this.responseText!="ok")
									{
										window.alert("Errore. Impossibile inserire l' attivita. Se il problema persiste contattare l' amministratore.");
										console.log(this.responseText);
										console.log(dashType);
									}
									else
									{
										document.getElementById('cabineCorrelate').style.visibility="";
										document.getElementById('attivitaCorrelate').style.visibility="";
										getElencoAttivitaProgrammate();
										setTimeout(function(){ clickMaxAttivita(); }, 500);
									}
								}
							};
							xmlhttp.open("POST", "inserisciNuovaAttivitaProgrammata.php?codice="+codice+"&descrizione="+descrizione+"&kitpref="+kitpref+"&note="+note+"&colore="+colore+"&dashType="+dashType, true);
							xmlhttp.send();
						}
					}
					else
						window.alert("Nessuna attivita selezionata");
				}
				else
				{
					var codice=document.getElementById('codiceAttivitaProgrammata').value;
					var descrizione=document.getElementById('descrizioneAttivitaProgrammata').value;
					var kitpref=document.getElementById('kitprefAttivitaProgrammata').value;
					var coloreRGB=document.getElementById('coloreAttivitaProgrammata').style.backgroundColor;
					var colorsOnly = coloreRGB.substring(coloreRGB.indexOf('(') + 1, coloreRGB.lastIndexOf(')')).split(/,\s*/);
					var red = colorsOnly[0];
					var green = colorsOnly[1];
					var blue = colorsOnly[2];
					var colore=rgbToHex(parseInt(red),parseInt(green),parseInt(blue));
					var dashType=document.getElementById('dashTypeAttivitaProgrammata').value;
					var note=document.getElementById('noteAttivitaProgrammata').value;
					if(codice=='' || descrizione=='')
					{
						window.alert("I campi codice e descrizione sono obbligatori");
					}
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								
								if(this.responseText!="ok")
									window.alert("Errore. Impossibile modificare l' attivita. Se il problema persiste contattare l' amministratore.");
								else
								{
									var id=attivitaSelezionata;
									getElencoAttivitaProgrammate();
									setTimeout(function(){ document.getElementById("btnSelezionaAttivitaProgrammate"+id).click(); }, 500);
								}
							}
						};
						xmlhttp.open("POST", "modificaAttivitaProgrammata.php?id_attivita_programmata="+attivitaSelezionata+"&codice="+codice+"&descrizione="+descrizione+"&kitpref="+kitpref+"&note="+note+"&colore="+colore+"&dashType="+dashType, true);
						xmlhttp.send();
					}
				}
			}
			function clickMaxAttivita()
			{
				var arrayId=[];
				var all = document.getElementsByClassName("btnSelezionaAttivitaProgrammate");
				for (var i = 0; i < all.length; i++)
				{
					var fullElementId=all[i].id;
					var elementId = fullElementId.replace("btnSelezionaAttivitaProgrammate", "");
					arrayId.push(elementId);
				}
				document.getElementById("btnSelezionaAttivitaProgrammate"+Math.max.apply(null, arrayId)).click();
			}
			function ripristinaMaschera()
			{
				attivitaSelezionata="";
				document.getElementById("titoloAttivitaProgrammata").innerHTML="Dati attivita programmata";
				document.getElementById('codiceAttivitaProgrammata').value="";
				document.getElementById('descrizioneAttivitaProgrammata').value="";
				document.getElementById('kitprefAttivitaProgrammata').value="";
				document.getElementById('coloreAttivitaProgrammata').style.backgroundColor="#F9F9F9";
				document.getElementById('dashTypeAttivitaProgrammata').value="";
				document.getElementById('noteAttivitaProgrammata').value="";
				document.getElementById('cabineCorrelate').style.visibility="visible";
				document.getElementById('attivitaCorrelate').style.visibility="visible";
				document.getElementById('pasteExcelCabineCorrelate').value="";
				document.getElementById('containerCabineCorrelate').innerHTML="";
				document.getElementById('containerAttivitaCorrelate').innerHTML="";
				document.getElementById('containerCabineCorrelate').style.backgroundColor="white";
				document.getElementById('containerAttivitaCorrelate').style.backgroundColor="white";
			}
			function eliminaAttivitaProgrammata()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{console.log(this.responseText);
							if(this.responseText!="ok")
								window.alert("Errore. Impossibile eliminare l' attivita. Se il problema persiste contattare l' amministratore.");
							else
							{
								getElencoAttivitaProgrammate();
							}
						}
					};
					xmlhttp.open("POST", "eliminaAttivitaProgrammata.php?id_attivita_programmata="+attivitaSelezionata, true);
					xmlhttp.send();
				}
			}
			function removeLastChar(content)
			{
				setTimeout(function(){
					var lastChar = content.substr(content.length - 1);
					if(isNaN(lastChar) || lastChar==String.fromCharCode(10) || lastChar==String.fromCharCode(9))
					{
						content=content.slice(0,-1);
						document.getElementById('pasteExcelCabineCorrelate').value=content;
					}
				}, 100);
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function inserisciDatiExcel()
			{
				if(attivitaSelezionata!='')
				{
					if(confirm("Attenzione.\nL' ordine corretto delle colonne è: Settimana, N cabine, Ponte, FZ, Anno.\nLe intestazioni di colonna vanno eliminate.\nGli spazi alla fine dei dati vanno eliminati\nSe i dati incollati hanno la forma esatta prosegui."))
					{
						newGridSpinner("Importazione dati in corso...","containerCabineCorrelate","","","");
						var errore="";
						var queries=[];
						var excelData=document.getElementById('pasteExcelCabineCorrelate').value;
						// split into rows
						excelRow = excelData.split(String.fromCharCode(10));
						if(excelRow.length>1000)
							errore="Limite massimo di righe (1000) superato.";
						for (i=0; i<excelRow.length; i++) 
						{
							var excelColumns = excelRow[i].split(String.fromCharCode(9));
							if(excelColumns.length!=5)
								errore="Numero di colonne errato.";
							if(isNaN(excelColumns[0]) || isNaN(excelColumns[1]) || isNaN(excelColumns[4]))
								errore="Valori non accettati";
						}
						if(errore=="")
						{
							// split rows into columns
							for (i=0; i<excelRow.length; i++) 
							{
								var excelColumns = excelRow[i].split(String.fromCharCode(9));
								var query="INSERT INTO [dbo].[programmazione_righe_attivita_programmate] ([attivita_programmata],[settimana],[nCabine],[ponte],[firezone],[anno],[commessa]) VALUES ('"+attivitaSelezionata;
								for (j=0; j<excelColumns.length; j++) 
								{
									query=query+"','"+excelColumns[j];
								}
								query=query+"',"+<?php echo $_SESSION['id_commessa']; ?>+")";
								queries.push(query);
							}
							var http = new XMLHttpRequest();
							var url = 'inserisciDatiExcel.php';
							var params = "queries="+queries.join("|");
							http.open('POST', url, true);
							
							//Send the proper header information along with the request
							http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

							http.onreadystatechange = function() 
							{//Call a function when the state changes.
								if(http.readyState == 4 && http.status == 200) 
								{
									if(http.responseText!="ok")
										newGridSpinner(http.responseText,"containerCabineCorrelate","","display:none","color:red;font-weight:bold");
									else
									{
										getRigheAttivitaProgrammata(attivitaSelezionata);
										document.getElementById("pasteExcelCabineCorrelate").value="";
									}
								}
							}
							http.send(params);
							/*var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									if(this.responseText!="ok")
										newGridSpinner(this.responseText,"containerCabineCorrelate","","display:none","color:red;font-weight:bold");
									else
									{
										getRigheAttivitaProgrammata(attivitaSelezionata);
										document.getElementById("pasteExcelCabineCorrelate").value="";
									}
								}
							};
							xmlhttp.open("POST", "inserisciDatiExcel.php?queries="+queries.join("|"), true);
							xmlhttp.send();*/
							//console.log(queries);
						}
						else
						{
							setTimeout(function(){newGridSpinner("Errore: "+errore,"containerCabineCorrelate","","display:none","color:red;font-weight:bold");}, 500);
						}
					}
				}
				else
					window.alert("Nessuna attivita selezionata");
			}
			function apriPopupRigheAttivitaProgrammate()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				else
					document.getElementById("modalRigheAttivitaProgrammate").style.display = "block";	
				
			}
			function chiudiPopupRigheAttivitaProgrammate()
			{
				document.getElementById("modalRigheAttivitaProgrammate").style.display = "none";
			}
			function inserisciRigaAttivitaProgrammate()
			{
				var settimana=document.getElementById("settimanaModal").value;
				var nCabine=document.getElementById("nCabineModal").value;
				var ponte=document.getElementById("ponteModal").value;
				var firezone=document.getElementById("firezoneModal").value;
				var anno=document.getElementById("annoModal").value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText!="ok")
							window.alert("Errore. Impossibile inserire la riga. Se il problema persiste contattare l' amministratore.");
						else
						{
							getRigheAttivitaProgrammata(attivitaSelezionata);
						}
					}
				};
				xmlhttp.open("POST", "inserisciRigaAttivitaProgrammate.php?id_attivita_programmata="+attivitaSelezionata+"&settimana="+settimana+"&nCabine="+nCabine+"&ponte="+ponte+"&firezone="+firezone+"&anno="+anno, true);
				xmlhttp.send();
			}
		</script>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getElencoAttivitaProgrammate()">
		<?php include('struttura.php'); ?>
		<!-- Modal RigheAttivitaProgrammate -->
		<div id="modalRigheAttivitaProgrammate" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<button id="closeModalRigheAttivitaProgrammate" class="closeModal" onclick='chiudiPopupRigheAttivitaProgrammate()'></button>
					<h2>Inserisci i dati</h2>
				</div>
				<div class="modal-body">
					<table class="modalInputTable">
						<tr><td><input type="number" class="modalInput" id="settimanaModal" placeholder="Settimana" title="Settimana" /></td></tr>
						<tr><td><input type="number" class="modalInput" id="nCabineModal" placeholder="N cabine" title="N cabine" /></td></tr>
						<tr><td><input type="text" class="modalInput" id="ponteModal" placeholder="Ponte" title="Ponte" /></td></tr>
						<tr><td><input type="text" class="modalInput" id="firezoneModal" placeholder="Firezone" title="Firezone" /></td></tr>
						<tr><td><input type="number" class="modalInput" id="annoModal" placeholder="Anno" title="Anno" /></td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<input type='button' id='btnConfermaModalRigheAttivitaProgrammate' class='btnModal' onclick='inserisciRigaAttivitaProgrammate();chiudiPopupRigheAttivitaProgrammate()' value='Conferma' />
					<input type='button' id='btnAnnullaModalRigheAttivitaProgrammate' class='btnModal' onclick='chiudiPopupRigheAttivitaProgrammate()' value='Annulla' />
				</div>
			</div>
		</div>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div id="containerGestioneAttivitaProgrammate" >
					<div id="elencoAttivitaProgrammate"></div>
					<div id="datiAttivitaProgrammata">
						<div id="titoloAttivitaProgrammata">
							Dati attivita programmata
						</div>
						<input type="text" class="inputTextAttivitaProgrammate" id="codiceAttivitaProgrammata" placeholder="Codice" />
						<input type="text" class="inputTextAttivitaProgrammate" id="descrizioneAttivitaProgrammata" placeholder="Descrizione" />
						<select class="inputTextAttivitaProgrammate" id="kitprefAttivitaProgrammata">
							<option value="" disabled selected>Kit/Pref</option>
							<option value="kit">Kit</option>
							<option value="pref">Pref</option>
						</select>
						<button class="inputTextAttivitaProgrammate jscolor {valueElement:null,value:'F9F9F9'}" id="coloreAttivitaProgrammata" >Colore</button>
						<select class="inputTextAttivitaProgrammate" placeholder="Colore" id="dashTypeAttivitaProgrammata">
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
						<textarea class="inputTextAttivitaProgrammate" id="noteAttivitaProgrammata" placeholder="Note" ></textarea>
					</div>
					<div id="cabineCorrelate">
						<div class="titoliElenchiAttivitaProgrammate">
							Cabine correlate<i class="fal fa-plus-circle" title="Aggiungi manualmente" onclick="apriPopupRigheAttivitaProgrammate()" style="float:right;display:inline-block;color:gray"></i>
						</div>
						<textarea id="pasteExcelCabineCorrelate" class="inputTextAttivitaProgrammate" placeholder="Incolla qui i dati da Excel" onmousedown="removeLastChar(this.value)" onmouseup="removeLastChar(this.value)" oncontextmenu="removeLastChar(this.value)" onclick="removeLastChar(this.value)" onpaste="removeLastChar(this.value)" onfocus="removeLastChar(this.value)" onfocusout="removeLastChar(this.value)" onkeyup="removeLastChar(this.value)"></textarea>
						<input type='button' id='btnImportaDati' onclick='inserisciDatiExcel()' value='Importa dati' />
						<div id="containerCabineCorrelate"></div>
					</div>
					<div id="attivitaCorrelate">
						<div class="titoliElenchiAttivitaProgrammate">
							Attivita correlate
						</div>
						<div id="containerAttivitaCorrelate"></div>
					</div>
					<div id="containerBottoniAttivitaProgrammate">
						<input type='button' id='btnSalva' class='btnAttivitaProgrammata' onclick='salvaAttivitaProgrammata()' value='Salva modifiche' />
						<input type='button' id='btnElimina' class='btnAttivitaProgrammata' onclick='eliminaAttivitaProgrammata()' value='Elimina attivita' />
						<input type='button' id='btnNuova' class='btnAttivitaProgrammata' onclick='nuovaAttivitaProgrammata()' value='Nuova attivita' />
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



