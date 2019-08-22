<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione attivita";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV30.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="struttura.js"></script>
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
			var attivitaSelezionata;
			var famigliaSelezionata;
			var tipologiaSelezionata;
			var cabineAssegnazioniSpecificheOk=[];
			var cabineAssegnazioniSpecificheKo=[];
			
			function nuovaAttivita()
			{
				getElencoAttivita();
				ripristinaMaschera();
				document.getElementById("titoloAttivita").innerHTML="Dati nuova attivita";
				document.getElementById('tipologieCabine').style.visibility="hidden";
				document.getElementById('famiglieCabine').style.visibility="hidden";
				document.getElementById('ditteAttivita').style.visibility="hidden";
				document.getElementById('containerBottomButtonsAttivita').style.visibility="hidden";
			}
			function salvaAttivita()
			{
				if(attivitaSelezionata=="")
				{
					if(document.getElementById('tipologieCabine').style.visibility=="hidden")
					{
						var descrizione=document.getElementById('descrizioneAttivita').value;
						if(descrizione.indexOf("à")!=-1 || descrizione.indexOf("è")!=-1 || descrizione.indexOf("è")!=-1 || descrizione.indexOf("ì")!=-1 || descrizione.indexOf("ò")!=-1 || descrizione.indexOf("ù")!=-1)
							window.alert("La descrizione deve contenere solo numeri e lettere non accentate.");
						else
						{
							var coloreRGB=document.getElementById('coloreAttivita').style.backgroundColor;
							var colorsOnly = coloreRGB.substring(coloreRGB.indexOf('(') + 1, coloreRGB.lastIndexOf(')')).split(/,\s*/);
							var red = colorsOnly[0];
							var green = colorsOnly[1];
							var blue = colorsOnly[2];
							var colore=rgbToHex(parseInt(red),parseInt(green),parseInt(blue));
							var dashType=document.getElementById('dashTypeAttivita').value;
							var kitpref=document.getElementById('kitprefAttivita').value;
							var marinaarredo=document.getElementById("marinaArredoAttivita").value;
							var note=document.getElementById('noteAttivita').value;
							if(descrizione=='' || kitpref=='')
							{
								window.alert("I campi descrizione e kit/pref sono obbligatori");
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
											window.alert("Errore. Impossibile inserire l' attivita. Se il problema persiste contattare l' amministratore.\n\nErrore: "+this.responseText);
											console.log(this.responseText);
										}
										else
										{
											document.getElementById('tipologieCabine').style.visibility="";
											document.getElementById('famiglieCabine').style.visibility="";
											document.getElementById('ditteAttivita').style.visibility="";
											document.getElementById('containerBottomButtonsAttivita').style.visibility="";
											getElencoAttivita();
											setTimeout(function(){ clickMaxAttivita(); }, 500);
										}
									}
								};
								xmlhttp.open("POST", "inserisciNuovaAttivita.php?descrizione="+descrizione+"&kitpref="+kitpref+"&note="+note+"&colore="+colore+"&dashType="+dashType+"&marinaarredo="+marinaarredo, true);
								xmlhttp.send();
							}
						}
					}
					else
						window.alert("Nessuna attivita selezionata");
				}
				else
				{
					var descrizione=document.getElementById('descrizioneAttivita').value;
					if(descrizione.indexOf("à")!=-1 || descrizione.indexOf("è")!=-1 || descrizione.indexOf("è")!=-1 || descrizione.indexOf("ì")!=-1 || descrizione.indexOf("ò")!=-1 || descrizione.indexOf("ù")!=-1)
						window.alert("La descrizione deve contenere solo numeri e lettere non accentate.");
					else
					{
						var kitpref=document.getElementById('kitprefAttivita').value;
						var marinaarredo=document.getElementById("marinaArredoAttivita").value;
						var coloreRGB=document.getElementById('coloreAttivita').style.backgroundColor;
						var colorsOnly = coloreRGB.substring(coloreRGB.indexOf('(') + 1, coloreRGB.lastIndexOf(')')).split(/,\s*/);
						var red = colorsOnly[0];
						var green = colorsOnly[1];
						var blue = colorsOnly[2];
						var colore=rgbToHex(parseInt(red),parseInt(green),parseInt(blue));
						var dashType=document.getElementById('dashTypeAttivita').value;
						var note=document.getElementById('noteAttivita').value;
						if(descrizione=='' || kitpref=='')
						{
							window.alert("I campi descrizione e kit/pref sono obbligatori");
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
										console.log(this.responseText);
										window.alert("Errore. Impossibile modificare l' attivita. Se il problema persiste contattare l' amministratore.\n\nErrore: "+this.responseText);
									}
									else
									{
										var id=attivitaSelezionata;
										getElencoAttivita();
										trySelezionaAttivita(id);
										//triggerScroll("rigaAttivita"+id,"elencoAttivita");
									}
								}
							};
							xmlhttp.open("POST", "modificaAttivita.php?codice_attivita="+attivitaSelezionata+"&descrizione="+descrizione+"&kitpref="+kitpref+"&note="+note+"&colore="+colore+"&dashType="+dashType+"&marinaarredo="+marinaarredo, true);
							xmlhttp.send();
						}
					}
				}
			}
			function triggerScroll(elemId,containerId)
			{
				var elem= $("#"+elemId);
				if(elem.length)
				{
					console.log(elem.offset().top);
					var elemOsset=elem.offset().top-document.getElementById(containerId).offsetHeight;
					$("#"+containerId).animate({ scrollTop: $("#"+containerId).scrollTop()+elemOsset }, { duration: 'medium', easing: 'swing' });
				}
			}
			function clickMaxAttivita()
			{
				var arrayId=[];
				var all = document.getElementsByClassName("btnSelezionaAttivita");
				for (var i = 0; i < all.length; i++)
				{
					var fullElementId=all[i].id;
					var elementId = fullElementId.replace("btnSelezionaAttivita", "");
					arrayId.push(elementId);
				}
				document.getElementById("btnSelezionaAttivita"+Math.max.apply(null, arrayId)).click();
				triggerScroll("rigaAttivita"+Math.max.apply(null, arrayId),"elencoAttivita");
			}
			function eliminaAttivita()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText!="ok")
							{
								window.alert("Errore. Impossibile eliminare l' attivita. Se il problema persiste contattare l' amministratore.");
								console.log(this.responseText);
							}
							else
							{
								getElencoAttivita();
							}
						}
					};
					xmlhttp.open("POST", "eliminaAttivita.php?codice_attivita="+attivitaSelezionata, true);
					xmlhttp.send();
				}
			}
			function ripristinaMaschera()
			{
				attivitaSelezionata="";
				famigliaSelezionata="";
				tipologiaSelezionata="";
				document.getElementById("titoloAttivita").innerHTML="Dati attivita";
				document.getElementById('marinaArredoAttivita').value="";
				document.getElementById('descrizioneAttivita').value="";
				document.getElementById('kitprefAttivita').value="";
				document.getElementById('coloreAttivita').style.backgroundColor="#F9F9F9";
				document.getElementById('dashTypeAttivita').value="";
				document.getElementById('noteAttivita').value="";
				document.getElementById("containerFamiglieCabine").innerHTML="";
				document.getElementById("containerTipologieCabine").innerHTML="";
				document.getElementById("containerDitteAttivita").innerHTML="";
				document.getElementById('tipologieCabine').style.visibility="visible";
				document.getElementById('famiglieCabine').style.visibility="visible";
				document.getElementById('ditteAttivita').style.visibility="visible";
				document.getElementById('containerBottomButtonsAttivita').style.visibility="visible";
			}
			function getElencoAttivita()
			{
				newGridSpinner("Caricamento attivita in corso...","elencoAttivita","","","font-size:12px;color:#2B586F");	
				ripristinaMaschera();
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("elencoAttivita").innerHTML  =this.responseText;
					}
				};
				xmlhttp.open("POST", "getElencoAttivita.php?", true);
				xmlhttp.send();
			}
			function sortTable(index,table,order) 
			{
				var table, rows, switching, i, x, y, shouldSwitch;
				table = document.getElementById(table);
				switching = true;
				/* Make a loop that will continue until
				no switching has been done: */
				while (switching) 
				{
					// Start by saying: no switching is done:
					switching = false;
					rows = table.rows;
					/* Loop through all table rows (except the
					first, which contains table headers): */
					for (i = 1; i < (rows.length - 1); i++) 
					{
						// Start by saying there should be no switching:
						shouldSwitch = false;
						/* Get the two elements you want to compare,
						one from current row and one from the next: */
						x = rows[i].getElementsByTagName("TD")[index];
						y = rows[i + 1].getElementsByTagName("TD")[index];
						// Check if the two rows should switch place:
						if(order=="asc")
						{
							if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) 
							{
								// If so, mark as a switch and break the loop:
								shouldSwitch = true;
								break;
							}
						}
						if(order=="desc")
						{
							if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) 
							{
								// If so, mark as a switch and break the loop:
								shouldSwitch = true;
								break;
							}
						}
					}
					if (shouldSwitch) 
					{
						/* If a switch has been marked, make the switch
						and mark that a switch has been done: */
						rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						switching = true;
					}
				}
			}
			function trySelezionaAttivita(codice_attivita)
			{
				var intervalSelezionaAttivita = setInterval(function()
				{
					if(document.getElementById("myTableElencoAttivita")!=null)
					{
						clearInterval(intervalSelezionaAttivita);
						selezionaAttivita(codice_attivita);
						triggerScroll("rigaAttivita"+codice_attivita,"elencoAttivita");
					}
				}, 500);
			}
			function selezionaAttivita(codice_attivita)
			{
				ripristinaMaschera();
				attivitaSelezionata=codice_attivita;
				var all = document.getElementsByClassName("btnSelezionaAttivita");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].className = "btnSelezionaAttivita";
				}
				var all2 = document.getElementsByClassName("btnSelezionaAttivitaClicked");
				for (var i = 0; i < all2.length; i++) 
				{
					all2[i].className = "btnSelezionaAttivita";
				}
				document.getElementById("btnSelezionaAttivita"+codice_attivita).className = "btnSelezionaAttivitaClicked";
				var all3 = document.getElementsByClassName("righeAttivita");
				for (var i = 0; i < all3.length; i++) 
				{
					all3[i].style.background = "";
				}
				document.getElementById("rigaAttivita"+codice_attivita).style.background = "#CCE5FF";
				//riempio intestazione
				document.getElementById("titoloAttivita").innerHTML="Dati attivita n. "+codice_attivita;
		
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('marinaArredoAttivita').value=this.responseText.split('|')[0];
						document.getElementById('descrizioneAttivita').value=this.responseText.split('|')[1];
						document.getElementById('kitprefAttivita').value=this.responseText.split('|')[2];
						document.getElementById('coloreAttivita').style.backgroundColor="#"+this.responseText.split('|')[3];
						document.getElementById('dashTypeAttivita').value=this.responseText.split('|')[4];
						document.getElementById('noteAttivita').value=this.responseText.split('|')[5];
					}
				};
				xmlhttp.open("POST", "selezionaAttivita.php?codice_attivita="+codice_attivita, true);
				xmlhttp.send();
				getFamiglieCabine(codice_attivita);
				getDitteCabine(attivitaSelezionata);
			}
			function getFamiglieCabine(codice_attivita)
			{
				document.getElementById("containerTipologieCabine").innerHTML="";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('containerFamiglieCabine').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getFamiglieCabine.php?codice_attivita="+codice_attivita, true);
				xmlhttp.send();
			}
			function getTipologieCabine(codice_attivita,famiglia)
			{
				tipologiaSelezionata='';
				famigliaSelezionata=famiglia;
				//document.getElementById("containerDitteAttivita").innerHTML="";
				var all = document.getElementsByClassName("righeFamiglia");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.background = "";
				}
				document.getElementById("rigaFamiglie"+famiglia).style.background="#CCE5FF";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('containerTipologieCabine').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getTipologieCabine.php?codice_attivita="+codice_attivita+"&famiglia="+famiglia, true);
				xmlhttp.send();
			}
			function eliminaTipologiaCabina(id_tipologie_attivita)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
							getTipologieCabine(attivitaSelezionata,famigliaSelezionata);
						else
						{
							console.log(this.responseText);
							window.alert("Errore. Impossibile eliminare la tipologia. Se il problema persiste contattare l' amministratore.");
						}
					}
				};
				xmlhttp.open("POST", "eliminaTipologiaCabina.php?id_tipologie_attivita="+id_tipologie_attivita+"&codice_attivita="+attivitaSelezionata, true);
				xmlhttp.send();
			}
			function assegnaOreTipologia(id_tipologie_attivita,ore)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
							getTipologieCabine(attivitaSelezionata,famigliaSelezionata);
						else
						{
							console.log(this.responseText);
							window.alert("Errore. Impossibile assegnare le ore alla tipologia. Se il problema persiste contattare l' amministratore.");
						}
					}
				};
				xmlhttp.open("POST", "assegnaOreTipologia.php?id_tipologie_attivita="+id_tipologie_attivita+"&codice_attivita="+attivitaSelezionata+"&ore="+ore, true);
				xmlhttp.send();
			}
			/*function getDitteCabine(codice_attivita,tipologia,famiglia)
			{
				tipologiaSelezionata=tipologia;
				var all = document.getElementsByClassName("righeTipologie");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.background = "";
				}
				document.getElementById("rigaTipologia"+tipologia).style.background="#CCE5FF";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("containerDitteAttivita").innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getDitteCabine.php?codice_attivita="+codice_attivita+"&tipologia="+tipologia+"&famiglia="+famiglia, true);
				xmlhttp.send();
			}*/
			function getDitteCabine(codice_attivita)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("containerDitteAttivita").innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getDitteCabine.php?codice_attivita="+codice_attivita, true);
				xmlhttp.send();
			}
			function eliminaDittaCabina(id_ditte_attivita)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
							getDitteCabine(attivitaSelezionata)
						else
						{
							console.log(this.responseText);
							window.alert("Errore. Impossibile eliminare la ditta. Se il problema persiste contattare l' amministratore.");
						}
					}
				};
				xmlhttp.open("POST", "eliminaDittaCabina.php?id_ditte_attivita="+id_ditte_attivita, true);
				xmlhttp.send();
			}
			function apriPopupDittaAttivita()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				if(attivitaSelezionata!='')
				{
					document.getElementById("dittaModalDitta").value="";
					document.getElementById("ponteModalDitta").value="";
					document.getElementById("firezoneModalDitta").value="";
					document.getElementById("modalDittaAttivita").style.display = "block";
				}
			}
			function chiudiPopupDittaAttivita()
			{
				document.getElementById("modalDittaAttivita").style.display = "none";
			}
			function inserisciDittaAttivita()
			{
				var id_ditta=document.getElementById("dittaModalDitta").value;
				var ponte=document.getElementById("ponteModalDitta").value;
				var firezone=document.getElementById("firezoneModalDitta").value;
				if(id_ditta=='' || ponte=='' || firezone=='')
					window.alert("Tutti i campi sono obbligatori");
				else
				{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText!="ok")
							{
								window.alert("Errore. Impossibile inserire la ditta. Se il problema persiste contattare l' amministratore.");
								console.log("this.responseText");
							}
							else
								getDitteCabine(attivitaSelezionata);
						}
					};
					xmlhttp.open("POST", "inserisciDittaAttivita.php?codice_attivita="+attivitaSelezionata+"&id_ditta="+id_ditta+"&ponte="+ponte+"&firezone="+firezone, true);
					xmlhttp.send();
				}
				/*if(tipologiaSelezionata=='')
				{
					if(confirm("Nessuna tipologia selezionata. Assegnare la ditta a tutta la famiglia "+famigliaSelezionata+"?"))
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText!="ok")
								{
									window.alert("Errore. Impossibile inserire la ditta. Se il problema persiste contattare l' amministratore.");
									console.log("this.responseText");
								}
								//else
									//getDitteCabine(attivitaSelezionata,tipologiaSelezionata,famigliaSelezionata);
							}
						};
						xmlhttp.open("POST", "inserisciDittaAttivitaTutte.php?codice_attivita="+attivitaSelezionata+"&famiglia="+famigliaSelezionata+"&id_ditta="+id_ditta+"&ponte="+ponte+"&firezone="+firezone, true);
						xmlhttp.send();
					}
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
								window.alert("Errore. Impossibile inserire la ditta. Se il problema persiste contattare l' amministratore.");
								console.log("this.responseText");
							}
							else
								getDitteCabine(attivitaSelezionata);
						}
					};
					xmlhttp.open("POST", "inserisciDittaAttivita.php?codice_attivita="+attivitaSelezionata+"&famiglia="+famigliaSelezionata+"&tipologia="+tipologiaSelezionata+"&id_ditta="+id_ditta+"&ponte="+ponte+"&firezone="+firezone, true);
					xmlhttp.send();
				}*/
			}
			function apriPopupTipologiaAttivita()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				if(famigliaSelezionata=='')
					window.alert("Nessuna famiglia selezionata");
				if(attivitaSelezionata!='' && famigliaSelezionata!='')
				{
					document.getElementById("oreModal").value="";
					document.getElementById("modalTipologiaAttivita").style.display = "block";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("modalContainerSelectTipologia").innerHTML=this.responseText;
						}
					};
					xmlhttp.open("POST", "getSelectTipologieAttivita.php?codice_attivita="+attivitaSelezionata+"&famiglia="+famigliaSelezionata, true);
					xmlhttp.send();
				}
			}
			function chiudiPopupTipologiaAttivita()
			{
				document.getElementById("modalTipologiaAttivita").style.display = "none";
			}
			function inserisciTipologiaAttivita()
			{
				var tipologia=document.getElementById("tipologiaModal").value;
				var ore=document.getElementById("oreModal").value;
				if(tipologia=='' || tipologia==null)
					window.alert("Seleziona una tipologia");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
								getTipologieCabine(attivitaSelezionata,famigliaSelezionata);
							else
							{
								console.log(this.responseText);
								window.alert("Errore. Impossibile aggiungere la tipologia. Se il problema persiste contattare l' amministratore.");
							}
						}
					};
					xmlhttp.open("POST", "inserisciTipologiaAttivita.php?codice_attivita="+attivitaSelezionata+"&tipologia="+tipologia+"&ore="+ore+"&famiglia="+famigliaSelezionata, true);
					xmlhttp.send();
				}
			}
			function apriPopupFamigliaAttivita()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				else
				{
					document.getElementById("oreModalFamiglia").value="";
					document.getElementById("modalFamigliaAttivita").style.display = "block";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							document.getElementById("modalContainerSelectFamiglia").innerHTML=this.responseText;
						}
					};
					xmlhttp.open("POST", "getSelectFamiglieAttivita.php?codice_attivita="+attivitaSelezionata, true);
					xmlhttp.send();
				}
			}
			function chiudiPopupFamigliaAttivita()
			{
				document.getElementById("modalFamigliaAttivita").style.display = "none";
			}
			function inserisciFamigliaAttivita()
			{
				var famiglia=document.getElementById("famigliaModal").value;
				var ore=document.getElementById("oreModalFamiglia").value;
				if(famiglia=='' || famiglia==null)
					window.alert("Seleziona una famiglia");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText=="ok")
							{
								document.getElementById("containerTipologieCabine").innerHTML="";
								getFamiglieCabine(attivitaSelezionata);
								if(famiglia!="*")
									setTimeout(function()
									{
										document.getElementById("rigaFamiglie"+famiglia).click(); 
										//triggerScroll("rigaFamiglie"+famiglia,"containerFamiglieCabine");
									}, 500);
							}
							else
							{
								console.log(this.responseText);
								window.alert("Errore. Impossibile aggiungere la famiglia. Se il problema persiste contattare l' amministratore.");
							}
						}
					};
					xmlhttp.open("POST", "inserisciFamigliaAttivita.php?codice_attivita="+attivitaSelezionata+"&famiglia="+famiglia+"&ore="+ore, true);
					xmlhttp.send();
				}
			}
			function eliminaFamigliaCabina(famiglia,codice_attivita)
			{
				var famiglia2=encodeURIComponent(famiglia);
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
						{
							getFamiglieCabine(codice_attivita);
						}
						else
						{
							console.log(this.responseText);
							window.alert("Errore. Impossibile eliminare la famiglia. Se il problema persiste contattare l' amministratore.");
						}
					}
				};
				xmlhttp.open("POST", "eliminaFamigliaCabina.php?famiglia="+famiglia2+"&codice_attivita="+codice_attivita, true);
				xmlhttp.send();
			}
			function eliminaTutteFamiglieCabine(codice_attivita)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
						{
							getFamiglieCabine(codice_attivita);
						}
						else
						{
							console.log(this.responseText);
							window.alert("Errore. Impossibile eliminare le famiglie. Se il problema persiste contattare l' amministratore.");
						}
					}
				};
				xmlhttp.open("POST", "eliminaTutteFamiglieCabine.php?codice_attivita="+codice_attivita, true);
				xmlhttp.send();
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
			function toggleCercaAttivita()
			{
				document.getElementById("cercaAttivitaInput").value="";
				
				if(document.getElementById("cercaAttivitaContainer").offsetHeight==0)
				{
					document.getElementById("cercaAttivitaContainer").style.visibility="visible";
					document.getElementById("cercaAttivitaContainer").style.height="40px";
					document.getElementById("iconCercaAttivitaContainer").innerHTML='<i class="far fa-window-close"></i>';
					document.getElementById("cercaAttivitaInput").focus();
				}
				else
				{
					document.getElementById("cercaAttivitaContainer").style.visibility="hidden";
					document.getElementById("cercaAttivitaContainer").style.height="0px";
					document.getElementById("iconCercaAttivitaContainer").innerHTML='<i class="far fa-search"></i>';
					setTimeout(function(){ getElencoAttivita(); }, 400);
				}
			}
			function cercaAttivita()
			{
				// Declare variables 
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("cercaAttivitaInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("myTableElencoAttivita");
				tr = table.getElementsByTagName("tr");

				// Loop through all table rows, and hide those who don't match the search query
				for (i = 0; i < tr.length; i++) 
				{
					td = tr[i].getElementsByTagName("td")[1];
					if (td) 
					{
						txtValue = td.textContent || td.innerText;
						//txtValue=td.innerHTML;
						if (txtValue.toUpperCase().indexOf(filter) > -1) 
						{
							tr[i].style.display = "";
						} 
						else 
						{
							tr[i].style.display = "none";
						}
					} 
				}
			}
			function confermaAssegnazioni()
			{
				swal("Caricamento in corso...","•••", 
				{
				  buttons: [false],
				});
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						//swal.close();
						if(this.responseText!='ok')
						{
							console.log(this.responseText);
							//swal.close();
							swal("Errore. Assegnazioni non confermate", " ", "error",);
						}
						else
						{
							//swal.close();
							swal("Assegnazioni confermate", " ", "success",{
							  buttons: [false],
							});
							setTimeout(function()
							{
								swal.close();
								if(attivitaSelezionata=='')
									getElencoAttivita();
								else
								{
									var codice_attivita=attivitaSelezionata;
									getElencoAttivita();
									trySelezionaAttivita(codice_attivita);
									//setTimeout(function(){ selezionaAttivita(codice_attivita); }, 500);
								}
							}, 2000);
						}
					}
				};
				xmlhttp.open("POST", "confermaAssegnazioni.php?", true);
				xmlhttp.send();
			}
			function assegnazioniSpecifiche()
			{
				if(attivitaSelezionata=='')
					window.alert("Nessuna attivita selezionata");
				else
				{
					document.getElementById("modalAssegnazioniSpecificheContainer").style.display="table";
					document.getElementById("assegnazioniSpecificheSelectPonte").value="%";
					document.getElementById("assegnazioniSpecificheSelectFirezone").value="%";
					document.getElementById("assegnazioniSpecificheSelectFamiglia").value="%";
					document.getElementById("assegnazioniSpecificheSelectTipologia").value="%";
					document.getElementById("assegnazioniSpecificheListContainer").innerHTML="";
					//document.getElementById("assegnazioniSpecificheCheckboxTutteOk").checked=false;
					cabineAssegnazioniSpecificheOk=[];
					cabineAssegnazioniSpecificheKo=[];
				}
			}
			function filtraCabine()
			{
				newGridSpinner("Ricerca cabine in corso...","assegnazioniSpecificheListContainer","","","font-size:12px;color:#2B586F");		
				cabineAssegnazioniSpecificheOk=[];
				cabineAssegnazioniSpecificheKo=[];
				var ponte=document.getElementById("assegnazioniSpecificheSelectPonte").value;
				var firezone=document.getElementById("assegnazioniSpecificheSelectFirezone").value;
				var famiglia=document.getElementById("assegnazioniSpecificheSelectFamiglia").value;
				var tipologia=document.getElementById("assegnazioniSpecificheSelectTipologia").value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						//console.log(this.responseText);
						document.getElementById("assegnazioniSpecificheListContainer").innerHTML=this.responseText.split("|")[0];
						var cabineOk=this.responseText.split("|")[1].split(",");
						var cabineKo=this.responseText.split("|")[2].split(",");
						for (var i = 0; i < cabineOk.length; i++)
						{
							toggleCheckBoxCabinaOk(cabineOk[i]);
						}
						for (var i = 0; i < cabineKo.length; i++)
						{
							toggleCheckBoxCabinaKo(cabineKo[i]);
						}
					}
				};
				xmlhttp.open("POST", "getCabineAssegnazioniSpecifiche.php?ponte="+ponte+"&firezone="+firezone+"&famiglia="+famiglia+"&tipologia="+tipologia+"&codice_attivita="+attivitaSelezionata,true);
				xmlhttp.send();
			}
			function toggleCheckBoxCabinaOk(numero_cabina)
			{
				if(document.getElementById("checkboxCabinaOk"+numero_cabina).checked)
					cabineAssegnazioniSpecificheOk.push(numero_cabina);
				else
					cabineAssegnazioniSpecificheOk.splice(cabineAssegnazioniSpecificheOk.indexOf(numero_cabina), 1);
			}
			function toggleCheckBoxTutteCabineOk()
			{
				if(document.getElementById("assegnazioniSpecificheCheckboxTutteOk").checked)
				{
					var all = document.getElementsByClassName("checkboxCabinaAssegnazioniSpecificheOk");
					for (var i = 0; i < all.length; i++)
					{
						all[i].checked=true;
						cabineAssegnazioniSpecificheOk.push(all[i].getAttribute("numero_cabina"));
					}
				}
				else
				{
					var all = document.getElementsByClassName("checkboxCabinaAssegnazioniSpecificheOk");
					for (var i = 0; i < all.length; i++)
					{
						all[i].checked=false;
					}
					cabineAssegnazioniSpecificheOk=[];
				}
			}
			function toggleCheckBoxCabinaKo(numero_cabina)
			{
				if(document.getElementById("checkboxCabinaKo"+numero_cabina).checked)
					cabineAssegnazioniSpecificheKo.push(numero_cabina);
				else
					cabineAssegnazioniSpecificheKo.splice(cabineAssegnazioniSpecificheKo.indexOf(numero_cabina), 1);
			}
			function toggleCheckBoxTutteCabineKo()
			{
				if(document.getElementById("assegnazioniSpecificheCheckboxTutteKo").checked)
				{
					var all = document.getElementsByClassName("checkboxCabinaAssegnazioniSpecificheKo");
					for (var i = 0; i < all.length; i++)
					{
						all[i].checked=true;
						cabineAssegnazioniSpecificheKo.push(all[i].getAttribute("numero_cabina"));
					}
				}
				else
				{
					var all = document.getElementsByClassName("checkboxCabinaAssegnazioniSpecificheKo");
					for (var i = 0; i < all.length; i++)
					{
						all[i].checked=false;
					}
					cabineAssegnazioniSpecificheKo=[];
				}
			}
			function aggiugniCabina(numero_cabina)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=='ok')
						{
							cabineAssegnazioniSpecificheOk.push(numero_cabina);
							cabineAssegnazioniSpecificheKo.push(numero_cabina);
							if(document.getElementById("myTableAssegnazioniSpecifiche")==null)
							{
								var table=document.createElement("table");
								table.setAttribute("id","myTableAssegnazioniSpecifiche");
								document.getElementById("assegnazioniSpecificheListContainer").appendChild(table);
							}
							else
								var table=document.getElementById("myTableAssegnazioniSpecifiche");
							var tr=document.createElement("tr");
							var td1=document.createElement("td");
							td1.setAttribute("style","text-align:left");
							td1.innerHTML=numero_cabina;
							var td2=document.createElement("td");
							td2.setAttribute("style","text-align:right;width:40px");
							var checkbox1=document.createElement("input");
							checkbox1.setAttribute("type","checkbox");
							checkbox1.setAttribute("class","checkboxCabinaAssegnazioniSpecificheOk");
							checkbox1.setAttribute("numero_cabina",numero_cabina);
							checkbox1.setAttribute("id","checkboxCabinaOk"+numero_cabina);
							checkbox1.setAttribute("onchange", "toggleCheckBoxCabinaOk('"+numero_cabina+"')");
							td2.appendChild(checkbox1);
							var td3=document.createElement("td");
							td3.setAttribute("style","text-align:right;width:40px");
							var checkbox2=document.createElement("input");
							checkbox2.setAttribute("type","checkbox");
							checkbox2.setAttribute("class","checkboxCabinaAssegnazioniSpecificheKo");
							checkbox2.setAttribute("numero_cabina",numero_cabina);
							checkbox2.setAttribute("id","checkboxCabinaKo"+numero_cabina);
							checkbox2.setAttribute("onchange", "toggleCheckBoxCabinaKo('"+numero_cabina+"')");
							td3.appendChild(checkbox2);
							tr.appendChild(td1);
							tr.appendChild(td2);
							tr.appendChild(td3);
							table.appendChild(tr);
						}
						else
							window.alert("Cabina inesistente");
					}
				};
				xmlhttp.open("POST", "checkNumeroCabina.php?numero_cabina="+numero_cabina, true);
				xmlhttp.send();
			}
			function confermaAssegnazioniSpecifiche()
			{
				var cabineAssegnazioniSpecificheTutte=[];
				var table=document.getElementById("myTableAssegnazioniSpecifiche");
				for (var i = 1, row; row = table.rows[i]; i++)
				{
					cabineAssegnazioniSpecificheTutte.push("'"+row.cells[0].innerHTML+"'");
				}
				
				//console.log(cabineAssegnazioniSpecificheTutte.toString());
				
				var oldContent=document.getElementById("assegnazioniSpecificheListContainer").innerHTML;
				newGridSpinner("Inserimento in corso...","assegnazioniSpecificheListContainer","","","font-size:12px;color:#2B586F");		
				
				var uniqueCabineAssegnazioniSpecificheTutte = [];
				$.each(cabineAssegnazioniSpecificheTutte, function(i, el){
				if($.inArray(el, uniqueCabineAssegnazioniSpecificheTutte) === -1) uniqueCabineAssegnazioniSpecificheTutte.push(el);
				});
				
				var uniqueCabineAssegnazioniSpecificheOk = [];
				$.each(cabineAssegnazioniSpecificheOk, function(i, el){
				if($.inArray(el, uniqueCabineAssegnazioniSpecificheOk) === -1) uniqueCabineAssegnazioniSpecificheOk.push(el);
				});
				
				var uniqueCabineAssegnazioniSpecificheKo = [];
				$.each(cabineAssegnazioniSpecificheKo, function(i, el){
				if($.inArray(el, uniqueCabineAssegnazioniSpecificheKo) === -1) uniqueCabineAssegnazioniSpecificheKo.push(el);
				});
				
				var http = new XMLHttpRequest();
				var url = 'confermaAssegnazioniSpecifiche.php';
				var params = "codice_attivita="+attivitaSelezionata+"&cabineOk="+uniqueCabineAssegnazioniSpecificheOk.toString()+"&cabineKo="+uniqueCabineAssegnazioniSpecificheKo.toString()+"&cabineTutte="+uniqueCabineAssegnazioniSpecificheTutte.toString();
				http.open('POST', url, true);
				
				//console.log("Ok: "+uniqueCabineAssegnazioniSpecificheOk);
				//console.log("Ko: "+uniqueCabineAssegnazioniSpecificheKo);

				//Send the proper header information along with the request
				http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

				http.onreadystatechange = function() 
				{//Call a function when the state changes.
					if(http.readyState == 4 && http.status == 200) 
					{
						if(http.responseText!='ok')
						{
							console.log(http.responseText);
							//swal.close();
							swal("Errore. Se il problema persiste contattare l amministratore.", " ", "error",);
							document.getElementById("assegnazioniSpecificheListContainer").innerHTML=oldContent;
						}
						else
						{
							//swal.close();
							swal("Assegnazioni specifiche registrate", " ", "success",{
							  buttons: [false],
							});
							setTimeout(function()
							{
								swal.close();
								document.getElementById("modalAssegnazioniSpecificheContainer").style.display="none"; 
								var codice_attivita=attivitaSelezionata;
								getElencoAttivita();
								trySelezionaAttivita(codice_attivita);
								//setTimeout(function(){ selezionaAttivita(codice_attivita); }, 500);
							}, 2000);
							
						}
					}
				}
				http.send(params);
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function riepilogoAssegnazioni()
			{
				document.getElementById("totaleCabineRiepilogoAssegnazioni").innerHTML="";
				document.getElementById("totaleOreRiepilogoAssegnazioni").innerHTML="";
				document.getElementById("righeRiepilogoAssegnazioni").innerHTML="";
				if(document.getElementById("myTableRiepilogoAssegnazioni")==null)
				{
					if(attivitaSelezionata=='')
						var Descrizione="%";
					else
						var Descrizione=document.getElementById("descrizioneAttivita").value;
					var nomeDitta="%";
					var ponte="%";
					var firezone="%";
					var totCabine="%";
					var totOre="%";
				}
				else
				{
					var Descrizione=document.getElementById("selectFiltroDescrizione").value;
					var nomeDitta=document.getElementById("selectFiltronomeDitta").value;
					var ponte=document.getElementById("selectFiltroponte").value;
					var firezone=document.getElementById("selectFiltrofirezone").value;
					var totCabine=document.getElementById("selectFiltrototCabine").value;
					var totOre=document.getElementById("selectFiltrototOre").value;
				}
				newGridSpinner("Caricamento in corso...","riepilogoAssegnazioniContainer","","","font-size:12px;color:#2B586F");
				document.getElementById("modalRiepilogoAssegnazioniContainer").style.display="table";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById("riepilogoAssegnazioniContainer").innerHTML=this.responseText;
						var totaleCabine=0;
						var totaleOre=0;
						var table = document.getElementById("myTableRiepilogoAssegnazioni");
						for (var i = 1, row; row = table.rows[i]; i++) 
						{
							totaleCabine += parseInt(row.cells[4].innerHTML);
							totaleOre += parseInt(row.cells[5].innerHTML);
						}
						document.getElementById("totaleCabineRiepilogoAssegnazioni").innerHTML=totaleCabine;
						document.getElementById("totaleOreRiepilogoAssegnazioni").innerHTML=totaleOre;
						document.getElementById("righeRiepilogoAssegnazioni").innerHTML=parseInt(table.rows.length)-1;
					}
				};
				xmlhttp.open("POST", "riepilogoAssegnazioni.php?Descrizione="+Descrizione+"&nomeDitta="+nomeDitta+"&ponte="+ponte+"&firezone="+firezone+"&totCabine="+totCabine+"&totOre="+totOre,true);
				xmlhttp.send();
			}
			function scaricaExcelRiepilogoAssegnazioni()
			{
				var table = document.getElementById("myTableRiepilogoAssegnazioni");
				var row = table.insertRow(1);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				var cell5 = row.insertCell(4);
				var cell6 = row.insertCell(5);
				cell1.innerHTML = "Attivita";
				cell2.innerHTML = "Ditta";
				cell3.innerHTML = "Ponte";
				cell4.innerHTML = "Firezone";
				cell5.innerHTML = "Tot cabine";
				cell6.innerHTML = "Tot ore";
				$("#myTableRiepilogoAssegnazioni").table2excel({
					exclude: ".selectFiltriRiepilogoAssegnazioni",
					name: "RiepilogoAssegnazioni",
					filename: "RiepilogoAssegnazioni" //do not include extension
				});
				table.deleteRow(1);
			}
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



