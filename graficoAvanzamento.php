<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Grafico avanzamento";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV31.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">--->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="struttura.js"></script>
		<script src="canvasjs.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.0/xlsx.full.min.js"></script>
		<script src="tableToExcel.js"></script>
		<script>
			var arrayOutput=[];
			var arrayValues=[];
			var proprietaGrafico=[];
			var elementiGrafico=[];
			var chart;
			var nLinee=0;
			var requeryGrafico=0;
			
			function getDati()
			{
				var proprietaGraficoGraficotype = document.getElementById("proprietaGraficoGraficotype").value;
				var proprietaGraficoGraficomarkerSize = document.getElementById("proprietaGraficoGraficomarkerSize").value;
				var proprietaGraficoGraficoindexLabel = document.getElementById("proprietaGraficoGraficoindexLabel").value;
				var type=proprietaGraficoGraficotype;
				var markerSize=proprietaGraficoGraficomarkerSize;
				var indexLabel=proprietaGraficoGraficoindexLabel;
				
				arrayOutput=[];
				arrayValues=[];
				
				var tipo=document.getElementById("selectGraficoAvanzamentoTipo").value;
				var gruppo=document.getElementById('selectGraficoAvanzamentoGruppo').value;
				var ponte=document.getElementById('selectGraficoAvanzamentoPonte').value;
				var firezone=document.getElementById('selectGraficoAvanzamentoFirezone').value;
				var ditta=document.getElementById('selectGraficoAvanzamentoDitta').value;
				var settimanaInizio=document.getElementById('selectGraficoAvanzamentoAnnoInizio').value+"."+document.getElementById('selectGraficoAvanzamentoSettimanaInizio').value;
				var settimanaFine=document.getElementById('selectGraficoAvanzamentoAnnoFine').value+"."+document.getElementById('selectGraficoAvanzamentoSettimanaFine').value;
				
				newGridSpinner("Composizione dati in corso...","chartContainer","","","font-size:80%;color:#2B586F");				
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						console.log(this.responseText);
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							console.log(this.responseText);
							document.getElementById("chartContainer").innerHTML="Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.";
						}
						else
						{
							console.log(this.responseText);
							var attivitaString=this.responseText.split("#")[1];
							var lineeString=this.responseText.split("#")[0];
							var attivita=attivitaString.split("%");
							attivita.pop();
							
							var legenda=[];
							var colori=[];
							var dashTypes=[];
							for (var i = 0; i < attivita.length; i++) 
							{
								var res=attivita[i].split("|");
								legenda.push(res[0]);
								colori.push(res[1]);
								dashTypes.push(res[2]);
							}
							
							//console.log(legenda);
							//console.log(colori);
							//console.log(dashTypes);
							
							var linee=lineeString.split("^");
							linee.pop();
							nLinee=linee.length;
							var dataArray=[];
							for (var j = 0; j < linee.length; j++) 
							{
								var linea=linee[j];
								var coordinate=linea.split("%");
								coordinate.pop();
								var dataPoints=[];
								for(var k=0;k<coordinate.length; k++)
								{
									var x=coordinate[k].split("|")[0];
									var y=coordinate[k].split("|")[1];
									
									var xValue=x.split("_")[0]+x.split("_")[1];
									xValue=parseInt(xValue);
									
									//console.log(xValue);
									
									arrayValues.push(xValue);
									
									if(indexLabel=="y")
									{
										dataPoints.push
										({
											x: xValue,
											y: parseInt(y),
											xtext: x,
											indexLabel: y
										});
									}
									if(indexLabel=="x")
									{
										dataPoints.push
										({
											x: xValue,
											y: parseInt(y),
											xtext: x,
											indexLabel: x
										});
									}
									if(indexLabel=="xy")
									{
										dataPoints.push
										({
											x: xValue,
											y: parseInt(y),
											xtext: x,
											indexLabel: "Settimana: "+x+" | N cabine: "+y
										});
									}
									if(indexLabel=="")
									{
										dataPoints.push
										({
											x: xValue,
											y: parseInt(y),
											xtext: x
										});
									}
								}
								dataArray.push(dataPoints);
							}
							for (var l = 0; l < dataArray.length; l++) 
							{
								var lineColor="#"+colori[l];
								var lineDashType=dashTypes[l];
								arrayOutput.push
								({
									color:lineColor,
									lineColor: lineColor,
									markerColor:lineColor,
									markerSize: markerSize,
									type: type,
									lineDashType:lineDashType,
									name: legenda[l],
									toolTipContent: "Settimana: {xtext}<br>{name}: {y} cabine",
									showInLegend: true,
									dataPoints:dataArray[l]
								});
							}
							getGrafico();
						}
					}
				};
				xmlhttp.open("POST", "getGraficoAvanzamento.php?tipo="+tipo+"&gruppo="+gruppo+"&ponte="+ponte+"&firezone="+firezone+"&ditta="+ditta+"&settimanaInizio="+settimanaInizio+"&settimanaFine="+settimanaFine, true);
				xmlhttp.send();
			}
			function getGrafico()
			{
				var proprietaGraficoTitolotext = document.getElementById("proprietaGraficoTitolotext").value;
				var proprietaGraficoTitolofontSize = document.getElementById("proprietaGraficoTitolofontSize").value;
				var proprietaGraficoTitolofontWeight = document.getElementById("proprietaGraficoTitolofontWeight").value;
				var proprietaGraficoTitolofontColor = document.getElementById("proprietaGraficoTitolofontColor").value;
				var proprietaGraficoTitolofontStyle = document.getElementById("proprietaGraficoTitolofontStyle").value;
				proprietaGraficoGraficotype = document.getElementById("proprietaGraficoGraficotype").value;
				proprietaGraficoGraficomarkerSize = document.getElementById("proprietaGraficoGraficomarkerSize").value;
				proprietaGraficoGraficoindexLabel = document.getElementById("proprietaGraficoGraficoindexLabel").value;
				var proprietaGraficoAsseXinterval=parseInt(document.getElementById("proprietaGraficoAsseXinterval").value);
				var proprietaGraficoAsseXtitle = document.getElementById("proprietaGraficoAsseXtitle").value;
				var proprietaGraficoAsseXtitleFontSize = document.getElementById("proprietaGraficoAsseXtitleFontSize").value;
				var proprietaGraficoAsseXlabelAngle = document.getElementById("proprietaGraficoAsseXlabelAngle").value;
				var proprietaGraficoAsseXlabelFontSize = document.getElementById("proprietaGraficoAsseXlabelFontSize").value;
				var proprietaGraficoAsseXprefix = document.getElementById("proprietaGraficoAsseXprefix").value;
				var proprietaGraficoAsseXsuffix = document.getElementById("proprietaGraficoAsseXsuffix").value;
				var proprietaGraficoAsseXtitleFontStyle = document.getElementById("proprietaGraficoAsseXtitleFontStyle").value;
				var proprietaGraficoAsseXtitleFontColor = document.getElementById("proprietaGraficoAsseXtitleFontColor").value;
				var proprietaGraficoAsseXlabelFontColor = document.getElementById("proprietaGraficoAsseXlabelFontColor").value;
				var proprietaGraficoAsseXinterlacedColor = document.getElementById("proprietaGraficoAsseXinterlacedColor").value;
				var proprietaGraficoAsseXgridDashType=document.getElementById("proprietaGraficoAsseXgridDashType").value
				var proprietaGraficoAsseXgridThickness=document.getElementById("proprietaGraficoAsseXgridThickness").value
				var proprietaGraficoAsseYinterval=parseInt(document.getElementById("proprietaGraficoAsseYinterval").value);
				var proprietaGraficoAsseYtitle = document.getElementById("proprietaGraficoAsseYtitle").value;
				var proprietaGraficoAsseYtitleFontSize = document.getElementById("proprietaGraficoAsseYtitleFontSize").value;
				var proprietaGraficoAsseYlabelAngle = document.getElementById("proprietaGraficoAsseYlabelAngle").value;
				var proprietaGraficoAsseYlabelFontSize = document.getElementById("proprietaGraficoAsseYlabelFontSize").value;
				var proprietaGraficoAsseYprefix = document.getElementById("proprietaGraficoAsseYprefix").value;
				var proprietaGraficoAsseYsuffix = document.getElementById("proprietaGraficoAsseYsuffix").value;
				var proprietaGraficoAsseYtitleFontStyle = document.getElementById("proprietaGraficoAsseYtitleFontStyle").value;
				var proprietaGraficoAsseYtitleFontColor = document.getElementById("proprietaGraficoAsseYtitleFontColor").value;
				var proprietaGraficoAsseYlabelFontColor = document.getElementById("proprietaGraficoAsseYlabelFontColor").value;
				var proprietaGraficoAsseYinterlacedColor = document.getElementById("proprietaGraficoAsseYinterlacedColor").value;
				var proprietaGraficoAsseYgridDashType=document.getElementById("proprietaGraficoAsseYgridDashType").value
				var proprietaGraficoAsseYgridThickness=document.getElementById("proprietaGraficoAsseYgridThickness").value
				var proprietaGraficoInterruzionescalaXstartValue = document.getElementById("proprietaGraficoInterruzionescalaXstartValue").value;
				var proprietaGraficoInterruzionescalaXendValue = document.getElementById("proprietaGraficoInterruzionescalaXendValue").value;
				var proprietaGraficoInterruzionescalaXspacing = parseInt(document.getElementById("proprietaGraficoInterruzionescalaXspacing").value);
				var proprietaGraficoInterruzionescalaXtype = document.getElementById("proprietaGraficoInterruzionescalaXtype").value;
				var proprietaGraficoInterruzionescalaYstartValue = parseInt(document.getElementById("proprietaGraficoInterruzionescalaYstartValue").value);
				var proprietaGraficoInterruzionescalaYendValue = parseInt(document.getElementById("proprietaGraficoInterruzionescalaYendValue").value);
				var proprietaGraficoInterruzionescalaYspacing = parseInt(document.getElementById("proprietaGraficoInterruzionescalaYspacing").value);
				var proprietaGraficoInterruzionescalaYtype = document.getElementById("proprietaGraficoInterruzionescalaYtype").value;
				var proprietaGraficoLineeriferimentoXvalue = document.getElementById("proprietaGraficoLineeriferimentoXvalue").value;
				var proprietaGraficoLineeriferimentoXlabel = document.getElementById("proprietaGraficoLineeriferimentoXlabel").value;
				var proprietaGraficoLineeriferimentoYvalue = parseInt(document.getElementById("proprietaGraficoLineeriferimentoYvalue").value);
				var proprietaGraficoLineeriferimentoYlabel = document.getElementById("proprietaGraficoLineeriferimentoYlabel").value;
				
				newGridSpinner("Creazione grafico in corso...","chartContainer","","","font-size:80%;color:#2B586F");
				
				chart = new CanvasJS.Chart("chartContainer", 
				{
					backgroundColor:null,
					animationEnabled: true,
					theme: "light2",
					zoomEnabled: true,
					zoomType: "xy",
					title:
					{
						text: proprietaGraficoTitolotext,
						fontSize: proprietaGraficoTitolofontSize,
						fontWeight:proprietaGraficoTitolofontWeight,
						fontColor:proprietaGraficoTitolofontColor,
						fontStyle:proprietaGraficoTitolofontStyle
					},
					axisX:
					{
						title: proprietaGraficoAsseXtitle,
						titleFontSize: proprietaGraficoAsseXtitleFontSize,
						labelAngle: proprietaGraficoAsseXlabelAngle,
						labelFontSize: proprietaGraficoAsseXlabelFontSize,
						prefix: proprietaGraficoAsseXprefix,
						suffix: proprietaGraficoAsseXsuffix,
						titleFontStyle: proprietaGraficoAsseXtitleFontStyle,
						titleFontColor: proprietaGraficoAsseXtitleFontColor,
						labelFontColor: proprietaGraficoAsseXlabelFontColor,
						interlacedColor: proprietaGraficoAsseXinterlacedColor,
						gridDashType: proprietaGraficoAsseXgridDashType,
						gridThickness: proprietaGraficoAsseXgridThickness,
						interval: proprietaGraficoAsseXinterval,
						scaleBreaks: {
							customBreaks: 
							[{
								spacing: 3,
								type: "straight",
								startValue:201854,
								endValue: 201900,
								lineColor: "#2B586F",
							},
							{
								spacing: 3,
								type: "straight",
								startValue:201954,
								endValue: 202000,
								lineColor: "#2B586F",
							},
							{
								spacing: 3,
								type: "straight",
								startValue:202054, 
								endValue: 202100,
								lineColor: "#2B586F",
							},
							{
								spacing: proprietaGraficoInterruzionescalaXspacing,
								type: proprietaGraficoInterruzionescalaXtype,
								startValue:parseInt(proprietaGraficoInterruzionescalaXstartValue.split("_")[0]+proprietaGraficoInterruzionescalaXstartValue.split("_")[1]), 
								endValue: parseInt(proprietaGraficoInterruzionescalaXstartValue.split("_")[0]+proprietaGraficoInterruzionescalaXendValue.split("_")[1])
							}]
						},
						stripLines:
						[
							{                
								value: parseInt(proprietaGraficoLineeriferimentoXvalue.split("_")[0]+proprietaGraficoLineeriferimentoXvalue.split("_")[1]),
								label:proprietaGraficoLineeriferimentoXlabel
							}
						],
						viewportMinimum: Math.min.apply(null, arrayValues)-1,
						viewportMaximum: Math.max.apply(null, arrayValues)+1,
						valueFormatString:"####_##",
						/*labelFormatter: function(e)
										{
											var valore=e.value;
											return valore;
										}*/
					},
					axisY:
					{
						title: proprietaGraficoAsseYtitle,
						titleFontSize: proprietaGraficoAsseYtitleFontSize,
						labelAngle: proprietaGraficoAsseYlabelAngle,
						labelFontSize: proprietaGraficoAsseYlabelFontSize,
						prefix: proprietaGraficoAsseYprefix,
						suffix: proprietaGraficoAsseYsuffix,
						titleFontStyle: proprietaGraficoAsseYtitleFontStyle,
						titleFontColor: proprietaGraficoAsseYtitleFontColor,
						labelFontColor: proprietaGraficoAsseYlabelFontColor,
						interlacedColor: proprietaGraficoAsseYinterlacedColor,
						gridDashType: proprietaGraficoAsseYgridDashType,
						gridThickness: proprietaGraficoAsseYgridThickness,
						interval: proprietaGraficoAsseYinterval,
						scaleBreaks: {
							customBreaks: [{
								spacing: proprietaGraficoInterruzionescalaYspacing,
								type: proprietaGraficoInterruzionescalaYtype,
								startValue: proprietaGraficoInterruzionescalaYstartValue,
								endValue: proprietaGraficoInterruzionescalaYendValue
							}]
						},
						stripLines:
						[
							{                
								value:proprietaGraficoLineeriferimentoYvalue,
								label:proprietaGraficoLineeriferimentoYlabel
							}
						],
						valueFormatString: "#"
					},
					legend:
					{
						fontweight: "normal",
						cursor: "pointer",
						dockInsidePlotArea: false,
						itemclick: toggleDataSeries
					},
					data:arrayOutput
				});
				chart.render();
								
				document.getElementById("btnStampaGraficoGraficoAvanzamento").addEventListener("click",function()
				{
					chart.print();
					var all = document.getElementsByClassName("contextMenuGraficoAvanzamento");
					for (var i = 0; i < all.length; i++) 
					{
						all[i].style.display='none';
					}
				});
				document.getElementById("btnScaricaImmagineGraficoAvanzamento").addEventListener("click",function()
				{
					chart.exportChart({format: "jpg"});
					var all = document.getElementsByClassName("contextMenuGraficoAvanzamento");
					for (var i = 0; i < all.length; i++) 
					{
						all[i].style.display='none';
					}
				});

				function toggleDataSeries(e)
				{
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) 
					{
						e.dataSeries.visible = false;
					}
					else
					{
						e.dataSeries.visible = true;
					}
					chart.render();
				}
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function openContextMenu(event)
			{
				document.getElementById("contextMenuGraficoAvanzamento").style.display="inline-block";
			}
			function scaricaExcelGraficoAvanzamento()
			{
				var tipo=document.getElementById("selectGraficoAvanzamentoTipo").value;
				var gruppo=document.getElementById('selectGraficoAvanzamentoGruppo').value;
				var ponte=document.getElementById('selectGraficoAvanzamentoPonte').value;
				var firezone=document.getElementById('selectGraficoAvanzamentoFirezone').value;
				var ditta=document.getElementById('selectGraficoAvanzamentoDitta').value;
				var settimanaInizio=document.getElementById('selectGraficoAvanzamentoAnnoInizio').value+"."+document.getElementById('selectGraficoAvanzamentoSettimanaInizio').value;
				var settimanaFine=document.getElementById('selectGraficoAvanzamentoAnnoFine').value+"."+document.getElementById('selectGraficoAvanzamentoSettimanaFine').value;
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{console.log(this.responseText);
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore. Impossibile visualizzare scaricare Excel. Se il problema persiste contattare l' amministratore.");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('excelTableContainer').innerHTML=this.responseText;
							//console.log(this.responseText);
							tableToExcel("myTableScaricaExcelGraficoAvanzamento");
						}
					}
				};
				xmlhttp.open("POST", "scaricaExcelGraficoAvanzamento.php?tipo="+tipo+"&gruppo="+gruppo+"&ponte="+ponte+"&firezone="+firezone+"&ditta="+ditta+"&settimanaInizio="+settimanaInizio+"&settimanaFine="+settimanaFine, true);
				xmlhttp.send();
				var all = document.getElementsByClassName("contextMenuGraficoAvanzamento");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
			}
			function apriPopupProprietaGrafico()
			{
				document.getElementById('popupProprietaGrafico').style.height="450px";
				document.getElementById('popupProprietaGrafico').style.width="500px";
				setTimeout(function()
				{ 
					document.getElementById('header').style.opacity="0.5";
					document.getElementById('container').style.opacity="0.5";
					document.getElementById('footer').style.opacity="0.5";	
					document.getElementById('chartContainer').style.opacity="0.5";
				}, 100);
				setTimeout(function()
				{ 
					document.getElementById('popupProprietaGrafico').style.opacity="1";	
				}, 200);
				dragElement(document.getElementById("popupProprietaGrafico"));
			}
			function chiudiPopupProprietaGrafico()
			{
				document.getElementById('popupProprietaGrafico').style.height='0px';
				document.getElementById('popupProprietaGrafico').style.width='0px';
				document.getElementById('header').style.opacity="";
				document.getElementById('container').style.opacity="";
				document.getElementById('footer').style.opacity="";
				setTimeout(function()
				{ 
					document.getElementById('header').style.opacity='1';
					document.getElementById('container').style.opacity='1';
					document.getElementById('footer').style.opacity='1';
					document.getElementById('chartContainer').style.opacity="1";
				}, 100);
			}
			function confermaPopupProprietaGrafico()
			{
				if(requeryGrafico==0)
					getGrafico();
				if(requeryGrafico==1)
				{
					getDati();
					requeryGrafico=0;
				}
			}
			function dragElement(elmnt) 
			{
				var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
				document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
				function dragMouseDown(e) 
				{
					//document.getElementById('popupColoriheader').style.cursor='-webkit-grabbing';
					e = e || window.event;
					e.preventDefault();
					// get the mouse cursor position at startup:
					pos3 = e.clientX;
					pos4 = e.clientY;
					document.onmouseup = closeDragElement;
					// call a function whenever the cursor moves:
					document.onmousemove = elementDrag;
				}

				function elementDrag(e) 
				{
					e = e || window.event;
					e.preventDefault();
					// calculate the new cursor position:
					pos1 = pos3 - e.clientX;
					pos2 = pos4 - e.clientY;
					pos3 = e.clientX;
					pos4 = e.clientY;
					// set the element's new position:
					elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
					elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
				}

				function closeDragElement() 
				{
					// stop moving when mouse button is released:
					document.onmouseup = null;
					document.onmousemove = null;
				}
			}
			function selezionaElementi(riga)
			{
				var elemento=riga.innerHTML;
				var all=document.getElementById("myTablePopupProprietaGrafico1").getElementsByTagName("td");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.backgroundColor='';
				}
				riga.style.backgroundColor='#ddd';
				var titoli=[];
				var proprieta=[];
				var tipi=[];
				var optionValues=[];
				var optionTexts=[];
				document.getElementById("myTablePopupProprietaGrafico2").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico3").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico4").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico5").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico6").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico7").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico8").style.display="none";
				document.getElementById("myTablePopupProprietaGrafico9").style.display="none";
				switch(elemento)
				{
					case "Titolo":document.getElementById("myTablePopupProprietaGrafico2").style.display="inline-block";break;
					case "Grafico":document.getElementById("myTablePopupProprietaGrafico3").style.display="inline-block";break;
					case "Asse X":document.getElementById("myTablePopupProprietaGrafico4").style.display="inline-block";break;
					case "Asse Y":document.getElementById("myTablePopupProprietaGrafico5").style.display="inline-block";break;
					case "Interruzione scala X":document.getElementById("myTablePopupProprietaGrafico6").style.display="inline-block";break;
					case "Interruzione scala Y":document.getElementById("myTablePopupProprietaGrafico7").style.display="inline-block";break;
					case "Linee riferimento X":document.getElementById("myTablePopupProprietaGrafico8").style.display="inline-block";break;
					case "Linee riferimento Y":document.getElementById("myTablePopupProprietaGrafico9").style.display="inline-block";break;
				}
			}
			function aggiungiProprieta(tipo,none,proprieta,valore)
			{
				if(proprieta=="type" || proprieta=="markerSize" || proprieta=="indexLabel")
					requeryGrafico=1;
				else
					requeryGrafico=0;
			}
			$("html").click(function(e) 
			{
				if($(e.target).is('.contextMenuGraficoAvanzamento'))
				{
					e.preventDefault();
					return;
				}
				if($(e.target).is('.fa-bars'))
				{
					e.preventDefault();
					return;
				}
				if($(e.target).is('.tableContextMenu'))
				{
					e.preventDefault();
					return;
				}
				if($(e.target).is('.tableContextMenu td'))
				{
					e.preventDefault();
					return;
				}
				if($(e.target).is('.tableContextMenu tr'))
				{
					e.preventDefault();
					return;
				}
				var all = document.getElementsByClassName("contextMenuGraficoAvanzamento");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
			});
		</script>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getDati()">
		<div id="excelTableContainer" style="display:none"></div>
		<div id="popupProprietaGrafico" onclick="document.getElementById('popupProprietaGrafico').style.opacity='1';document.getElementById('header').style.opacity='0.2';document.getElementById('container').style.opacity='0.2';document.getElementById('footer').style.opacity='0.2';" onmouseup="document.getElementById('popupProprietaGrafico').style.opacity='1';document.getElementById('header').style.opacity='0.2';document.getElementById('container').style.opacity='0.2';document.getElementById('footer').style.opacity='0.2';	" onmousedown="document.getElementById('popupProprietaGrafico').style.opacity='1';document.getElementById('header').style.opacity='0.2';document.getElementById('container').style.opacity='0.2';document.getElementById('footer').style.opacity='0.2';	">
			<div id="popupProprietaGraficoheader">
				<div style="color:white;box-sizing:border-box;height:60px;padding-left:40px;padding-top:20px;width:400px;float:left;display:inline-block;font-weight:bold">Proprieta grafico</div>
				<input type="button" id="btnChiudiPopupProprietaGrafico" onclick="chiudiPopupProprietaGrafico()" value="" />
			</div>
			<div id="containerPopupProprietaGrafico">
				<div id="containerTableProprietaGrafico1">
					<table id="myTablePopupProprietaGrafico1">
						<tr><th>Elementi</th></tr>
						<tr><td onclick="selezionaElementi(this)">Titolo</td></tr>
						<tr><td onclick="selezionaElementi(this)">Grafico</td></tr>
						<tr><td onclick="selezionaElementi(this)">Asse X</td></tr>
						<tr><td onclick="selezionaElementi(this)">Asse Y</td></tr>
						<tr><td onclick="selezionaElementi(this)">Interruzione scala X</td></tr>
						<tr><td onclick="selezionaElementi(this)">Interruzione scala Y</td></tr>
						<tr><td onclick="selezionaElementi(this)">Linee riferimento X</td></tr>
						<tr><td onclick="selezionaElementi(this)">Linee riferimento Y</td></tr>
						<tr><td style="color:red" onclick="location.reload();">Reset proprieta</td></tr>
					</table>
				</div>
				<div id="containerTableProprietaGrafico2">
					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico2">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Testo</td>
								<td>
									<input type="text" placeholder="Es: Avanzamento 2018" onfocusout="aggiungiProprieta('input','Titolo','text',this.value)" id="proprietaGraficoTitolotext">
								</td>
							</tr>
							<tr>
								<td>Dimensione</td>
								<td>
									<input type="number" placeholder="Es: 10,15,20" onfocusout="aggiungiProprieta('input','Titolo','fontSize',this.value)" id="proprietaGraficoTitolofontSize">
								</td>
							</tr>
							<tr>
								<td>Spessore</td>
								<td>
									<select onchange="aggiungiProprieta('select','Titolo','fontWeight',this.value)" id="proprietaGraficoTitolofontWeight">
										<option value="">Default</option>
										<option value="lighter">Leggero</option>
										<option value="normal">Normale</option>
										<option value="bold">Grassetto</option>
										<option value="bolder">Grassetto+</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Colore</td>
								<td>
									<select onchange="aggiungiProprieta('select','Titolo','fontColor',this.value)" id="proprietaGraficoTitolofontColor">
										<option value="">Default</option>
										<option value="gray">Grigio</option>
										<option value="black">Nero</option>
										<option value="red">Rosso</option>
										<option value="blue">Blu</option>
										<option value="green">Verde</option>
										<option value="yellow">Giallo</option>
										<option value="orange">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Stile</td>
								<td>
									<select onchange="aggiungiProprieta('select','Titolo','fontStyle',this.value)" id="proprietaGraficoTitolofontStyle">
										<option value="">Default</option>
										<option value="normal">Normale</option>
										<option value="italic">Corsivo</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico3">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Tipo</td>
								<td>
									<select onchange="aggiungiProprieta('select','Grafico','type',this.value)" id="proprietaGraficoGraficotype">
										<option value="line">Linee</option>
										<option value="area">Area</option>
										<option value="spline">Curve</option>
										<option value="splineArea">Curve area</option>
										<option value="stepLine">Scalini</option>
										<option value="scatter">Punti</option>
										<option value="column">Colonne</option>
										<option value="bar">Barre</option>
										<option value="stackedColumn">Colonne sovrapposte</option>
										<option value="stackedBar">Barre sovrapposte</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Dimensione punti</td>
								<td>
									<select onchange="aggiungiProprieta('select','Grafico','markerSize',this.value)" id="proprietaGraficoGraficomarkerSize">
										<option value="1">Nessuno</option>
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="15">15</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Valori punti</td>
								<td>
									<select onchange="aggiungiProprieta('select','Grafico','indexLabel',this.value)" id="proprietaGraficoGraficoindexLabel">
										<option value="">Nessuno</option>
										<option value="y">N cabine</option>
										<option value="x">Settimana</option>
										<option value="xy">Entrambe</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico4">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Linee griglia</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse X','gridDashType',this.value)" id="proprietaGraficoAsseXgridDashType">
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
								</td>
							</tr>
							<tr>
								<td>Spessore linee griglia</td>
								<td>
									<input type="number" value="1" placeholder="Es: 0,1,2" onfocusout="aggiungiProprieta('input','Asse X','gridThickness',this.value)" id="proprietaGraficoAsseXgridThickness">
								</td>
							</tr>
							<tr>
								<td>Intervallo valori</td>
								<td>
									<input type="number" value="" placeholder="Es: 1,2,5" onfocusout="aggiungiProprieta('input','Asse X','interval',this.value)" id="proprietaGraficoAsseXinterval">
								</td>
							</tr>
							<tr>
								<td>Titolo</td>
								<td>
									<input type="text" value="Settimana" placeholder="Es: Settimana" onfocusout="aggiungiProprieta('input','Asse X','title',this.value)" id="proprietaGraficoAsseXtitle">
								</td>
							</tr>
							<tr>
								<td>Dimensione titolo</td>
								<td>
									<input type="number" value="15" placeholder="Es: 10,15,20" onfocusout="aggiungiProprieta('input','Asse X','titleFontSize',this.value)" id="proprietaGraficoAsseXtitleFontSize">
								</td>
							</tr>
							<tr>
								<td>Stile titolo</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse X','titleFontStyle',this.value)" id="proprietaGraficoAsseXtitleFontStyle">
										<option value="normal">Default</option>
										<option value="normal">Normale</option>
										<option value="italic">Corsivo</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Colore titolo</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse X','titleFontColor',this.value)" id="proprietaGraficoAsseXtitleFontColor">
										<option value="gray">Default</option>
										<option value="gray">Grigio</option>
										<option value="black">Nero</option>
										<option value="red">Rosso</option>
										<option value="blue">Blu</option>
										<option value="green">Verde</option>
										<option value="yellow">Giallo</option>
										<option value="orange">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Angolo testi asse</td>
								<td>
									<input type="number" value="0" placeholder="ES: 45" onfocusout="aggiungiProprieta('input','Asse X','labelAngle',this.value)" id="proprietaGraficoAsseXlabelAngle">
								</td>
							</tr>
							<tr>
								<td>Colore testi asse</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse X','labelFontColor',this.value)" id="proprietaGraficoAsseXlabelFontColor">
										<option value="gray">Default</option>
										<option value="gray">Grigio</option>
										<option value="black">Nero</option>
										<option value="red">Rosso</option>
										<option value="blue">Blu</option>
										<option value="green">Verde</option>
										<option value="yellow">Giallo</option>
										<option value="orange">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Dimensione testi asse</td>
								<td>
									<input type="number" value="10" placeholder="Es: 10,15,20" onfocusout="aggiungiProprieta('input','Asse X','labelFontSize',this.value)" id="proprietaGraficoAsseXlabelFontSize">
								</td>
							</tr>
							<tr>
								<td>Prefisso testi asse</td>
								<td>
									<input type="text" placeholder="Es: Settimana" onfocusout="aggiungiProprieta('input','Asse X','prefix',this.value)" id="proprietaGraficoAsseXprefix">
								</td>
							</tr>
							<tr>
								<td>Suffisso testi asse</td>
								<td>
									<input type="text" placeholder="Es: Settimana" onfocusout="aggiungiProprieta('input','Asse X','suffix',this.value)" id="proprietaGraficoAsseXsuffix">
								</td>
							</tr>
							<tr>
								<td>Colore intervalli</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse X','interlacedColor',this.value)" id="proprietaGraficoAsseXinterlacedColor">
										<option value="#EBEBEB">Default</option>
										<option value="Gainsboro">Grigio</option>
										<option value="LightCoral">Rosso</option>
										<option value="Cyan">Blu</option>
										<option value="LightGreen">Verde</option>
										<option value="LightGoldenRodYellow">Giallo</option>
										<option value="SandyBrown">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico5">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Linee griglia</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse Y','gridDashType',this.value)" id="proprietaGraficoAsseYgridDashType">
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
								</td>
							</tr>
							<tr>
								<td>Spessore linee griglia</td>
								<td>
									<input type="number" value="1" placeholder="Es: 0,1,2" onfocusout="aggiungiProprieta('input','Asse Y','gridThickness',this.value)" id="proprietaGraficoAsseYgridThickness">
								</td>
							</tr>
							<tr>
								<td>Intervallo valori</td>
								<td>
									<input type="number" value="" placeholder="Es: 50,200,300" onfocusout="aggiungiProprieta('input','Asse Y','interval',this.value)" id="proprietaGraficoAsseYinterval">
								</td>
							</tr>
							<tr>
								<td>Titolo</td>
								<td>
									<input type="text" value="N cabine" placeholder="Es: N cabine" onfocusout="aggiungiProprieta('input','Asse Y','title',this.value)" id="proprietaGraficoAsseYtitle">
								</td>
							</tr>
							<tr>
								<td>Dimensione titolo</td>
								<td>
									<input type="number" value="15" placeholder="Es: 10,15,20" onfocusout="aggiungiProprieta('input','Asse Y','titleFontSize',this.value)" id="proprietaGraficoAsseYtitleFontSize">
								</td>
							</tr>
							<tr>
								<td>Stile titolo</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse Y','titleFontStyle',this.value)" id="proprietaGraficoAsseYtitleFontStyle">
										<option value="normal">Default</option>
										<option value="normal">Normale</option>
										<option value="italic">Corsivo</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Colore titolo</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse Y','titleFontColor',this.value)" id="proprietaGraficoAsseYtitleFontColor">
										<option value="gray">Default</option>
										<option value="gray">Grigio</option>
										<option value="black">Nero</option>
										<option value="red">Rosso</option>
										<option value="blue">Blu</option>
										<option value="green">Verde</option>
										<option value="yellow">Giallo</option>
										<option value="orange">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Angolo testi asse</td>
								<td>
									<input type="number" value="0" placeholder="ES: 45" onfocusout="aggiungiProprieta('input','Asse Y','labelAngle',this.value)" id="proprietaGraficoAsseYlabelAngle">
								</td>
							</tr>
							<tr>
								<td>Colore testi asse</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse Y','labelFontColor',this.value)" id="proprietaGraficoAsseYlabelFontColor">
										<option value="gray">Default</option>
										<option value="gray">Grigio</option>
										<option value="black">Nero</option>
										<option value="red">Rosso</option>
										<option value="blue">Blu</option>
										<option value="green">Verde</option>
										<option value="yellow">Giallo</option>
										<option value="orange">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Dimensione testi asse</td>
								<td>
									<input type="number" value="10" placeholder="Es: 10,15,20" onfocusout="aggiungiProprieta('input','Asse Y','labelFontSize',this.value)" id="proprietaGraficoAsseYlabelFontSize">
								</td>
							</tr>
							<tr>
								<td>Prefisso testi asse</td>
								<td>
									<input type="text" placeholder="Es: Settimana" onfocusout="aggiungiProprieta('input','Asse Y','prefix',this.value)" id="proprietaGraficoAsseYprefix">
								</td>
							</tr>
							<tr>
								<td>Suffisso testi asse</td>
								<td>
									<input type="text" placeholder="Es: Settimana" onfocusout="aggiungiProprieta('input','Asse Y','suffix',this.value)" id="proprietaGraficoAsseYsuffix">
								</td>
							</tr>
							<tr>
								<td>Colore intervalli</td>
								<td>
									<select onchange="aggiungiProprieta('select','Asse Y','interlacedColor',this.value)" id="proprietaGraficoAsseYinterlacedColor">
										<option value="#EBEBEB">Default</option>
										<option value="Gainsboro">Grigio</option>
										<option value="LightCoral">Rosso</option>
										<option value="Cyan">Blu</option>
										<option value="LightGreen">Verde</option>
										<option value="LightGoldenRodYellow">Giallo</option>
										<option value="SandyBrown">Arancione</option>
										<option value="pink">Rosa</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico6">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Valore iniziale</td>
								<td>
									<input type="text" placeholder="Es: 2018_12,2018_20,2018_25" onfocusout="aggiungiProprieta('input','Interruzione scala X','startValue',this.value)" id="proprietaGraficoInterruzionescalaXstartValue">
								</td>
							</tr>
							<tr>
								<td>Valore finale</td>
								<td>
									<input type="text" placeholder="Es: 2018_31,2018_43,2018_51" onfocusout="aggiungiProprieta('input','Interruzione scala X','endValue',this.value)" id="proprietaGraficoInterruzionescalaXendValue">
								</td>
							</tr>
							<tr>
								<td>Tipologia</td>
								<td>
									<select onchange="aggiungiProprieta('select','Interruzione scala X','type',this.value)" id="proprietaGraficoInterruzionescalaXtype">
										<option value="">Default</option>
										<option value="straight">Dritto</option>
										<option value="wavy">Onda</option>
										<option value="zigzag">Zigzag</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Spaziatura</td>
								<td>
									<input type="number" placeholder="Es: 3,5,10" onfocusout="aggiungiProprieta('input','Interruzione scala X','spacing',this.value)" id="proprietaGraficoInterruzionescalaXspacing">
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico7">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Valore iniziale</td>
								<td>
									<input type="number" placeholder="Es: 100,250,400" onfocusout="aggiungiProprieta('input','Interruzione scala Y','startValue',this.value)" id="proprietaGraficoInterruzionescalaYstartValue">
								</td>
							</tr>
							<tr>
								<td>Valore finale</td>
								<td>
									<input type="number" placeholder="Es: 500,650,800" onfocusout="aggiungiProprieta('input','Interruzione scala Y','endValue',this.value)" id="proprietaGraficoInterruzionescalaYendValue">
								</td>
							</tr>
							<tr>
								<td>Tipologia</td>
								<td>
									<select onchange="aggiungiProprieta('select','Interruzione scala Y','type',this.value)" id="proprietaGraficoInterruzionescalaYtype">
										<option value="">Default</option>
										<option value="straight">Dritto</option>
										<option value="wavy">Onda</option>
										<option value="zigzag">Zigzag</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Spaziatura</td>
								<td>
									<input type="number" placeholder="Es: 3,5,10" onfocusout="aggiungiProprieta('input','Interruzione scala Y','spacing',this.value)" id="proprietaGraficoInterruzionescalaYspacing">
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico8">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Valore</td>
								<td>
									<input type="text" placeholder="Es: 2018_20,2018_35,2018_41" onfocusout="aggiungiProprieta('input','Linee riferimento X','value',this.value)" id="proprietaGraficoLineeriferimentoXvalue">
								</td>
							</tr>
							<tr>
								<td>Titolo</td>
								<td>
									<input type="text" placeholder="Es: Consegna" onfocusout="aggiungiProprieta('input','Linee riferimento X','label',this.value)" id="proprietaGraficoLineeriferimentoXlabel">
								</td>
							</tr>
						</tbody>
					</table>

					<table class="myTablePopupProprietaGrafico" id="myTablePopupProprietaGrafico9">
						<tbody>
							<tr>
								<th>Proprieta</th>
								<th>Valore</th>
							</tr>
							<tr>
								<td>Valore</td>
								<td>
									<input type="number" placeholder="Es: 100,350,670" onfocusout="aggiungiProprieta('input','Linee riferimento Y','value',this.value)" id="proprietaGraficoLineeriferimentoYvalue">
								</td>
							</tr>
							<tr>
								<td>Titolo</td>
								<td>
									<input type="text" placeholder="Es: Record" onfocusout="aggiungiProprieta('input','Linee riferimento Y','label',this.value)" id="proprietaGraficoLineeriferimentoYlabel">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="containerInputPopupProprietaGrafico">
				<input type="button" id="btnConfermaPopupProprietaGrafico" style="float:left;margin-left:35px;" value="Conferma" onclick="confermaPopupProprietaGrafico();chiudiPopupProprietaGrafico()" />
				<input type="button" id="btnAnnullaPopupProprietaGrafico" style="float:right;margin-right:35px;" value="Annulla" onclick="chiudiPopupProprietaGrafico()" />
			</div>
		</div>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div class="funcionListContainer">
					<div class="functionList">
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Tipo avanzamento</div>
							<select class="selectFunctionList" onchange="getDati()" id="selectGraficoAvanzamentoTipo">
								<option value='complessivo' selected>Complessivo</option>
								<option value='parziale'>Parziale</option>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Gruppo</div>
							<select class="selectFunctionList" onchange="getDati()" id="selectGraficoAvanzamentoGruppo">
								<?php getElencoGruppi($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Ponte</div>
							<select class="selectFunctionList" onchange="getDati()" id="selectGraficoAvanzamentoPonte">
								<option value="%">Tutti</option>
								<?php getElencoPonti($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Firezone</div>
							<select class="selectFunctionList" onchange="getDati()" id="selectGraficoAvanzamentoFirezone">
								<option value="%">Tutte</option>
								<?php getElencoFirezone($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Ditta</div>
							<select class="selectFunctionList" onchange="getDati()" id="selectGraficoAvanzamentoDitta">
								<option value="%">Tutte</option>
								<?php getElencoDitte($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Settimana inizio</div>
							<select class="selectFunctionListSmall" style="margin-right:10px;" onchange="getDati()" id="selectGraficoAvanzamentoAnnoInizio">
								<option value="2017">2017</option>								
								<option value="2018" selected>2018</option>
								<option value="2019">2019</option>	
								<option value="2020">2020</option>	
							</select>
							<select class="selectFunctionListSmall" onchange="getDati()" id="selectGraficoAvanzamentoSettimanaInizio">
								<option value='01' selected>01</option>
								<option value='02'>02</option>
								<option value='03'>03</option>
								<option value='04'>04</option>
								<option value='05'>05</option>
								<option value='06'>06</option>
								<option value='07'>07</option>
								<option value='08'>08</option>
								<option value='09'>09</option>
								<option value='10'>10</option>
								<option value='11'>11</option>
								<option value='12'>12</option>
								<option value='13'>13</option>
								<option value='14'>14</option>
								<option value='15'>15</option>
								<option value='16'>16</option>
								<option value='17'>17</option>
								<option value='18'>18</option>
								<option value='19'>19</option>
								<option value='20'>20</option>
								<option value='21'>21</option>
								<option value='22'>22</option>
								<option value='23'>23</option>
								<option value='24'>24</option>
								<option value='25'>25</option>
								<option value='26'>26</option>
								<option value='27'>27</option>
								<option value='28'>28</option>
								<option value='29'>29</option>
								<option value='30'>30</option>
								<option value='31'>31</option>
								<option value='32'>32</option>
								<option value='33'>33</option>
								<option value='34'>34</option>
								<option value='35'>35</option>
								<option value='36'>36</option>
								<option value='37'>37</option>
								<option value='38'>38</option>
								<option value='39'>39</option>
								<option value='40'>40</option>
								<option value='41'>41</option>
								<option value='42'>42</option>
								<option value='43'>43</option>
								<option value='44'>44</option>
								<option value='45'>45</option>
								<option value='46'>46</option>
								<option value='47'>47</option>
								<option value='48'>48</option>
								<option value='49'>49</option>
								<option value='50'>50</option>
								<option value='51'>51</option>
								<option value='52'>52</option>
								<option value='53'>53</option>
								<option value='54'>54</option>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Settimana fine</div>
							<select class="selectFunctionListSmall" style="margin-right:10px;" onchange="getDati()" id="selectGraficoAvanzamentoAnnoFine">
								<option value="2017">2017</option>								
								<option value="2018" selected>2018</option>
								<option value="2019">2019</option>	
								<option value="2020">2020</option>	
							</select>
							<select class="selectFunctionListSmall" onchange="getDati()" id="selectGraficoAvanzamentoSettimanaFine">
								<option value='01'>01</option>
								<option value='02'>02</option>
								<option value='03'>03</option>
								<option value='04'>04</option>
								<option value='05'>05</option>
								<option value='06'>06</option>
								<option value='07'>07</option>
								<option value='08'>08</option>
								<option value='09'>09</option>
								<option value='10'>10</option>
								<option value='11'>11</option>
								<option value='12'>12</option>
								<option value='13'>13</option>
								<option value='14'>14</option>
								<option value='15'>15</option>
								<option value='16'>16</option>
								<option value='17'>17</option>
								<option value='18'>18</option>
								<option value='19'>19</option>
								<option value='20'>20</option>
								<option value='21'>21</option>
								<option value='22'>22</option>
								<option value='23'>23</option>
								<option value='24'>24</option>
								<option value='25'>25</option>
								<option value='26'>26</option>
								<option value='27'>27</option>
								<option value='28'>28</option>
								<option value='29'>29</option>
								<option value='30'>30</option>
								<option value='31'>31</option>
								<option value='32'>32</option>
								<option value='33'>33</option>
								<option value='34'>34</option>
								<option value='35'>35</option>
								<option value='36'>36</option>
								<option value='37'>37</option>
								<option value='38'>38</option>
								<option value='39'>39</option>
								<option value='40'>40</option>
								<option value='41'>41</option>
								<option value='42'>42</option>
								<option value='43'>43</option>
								<option value='44'>44</option>
								<option value='45'>45</option>
								<option value='46'>46</option>
								<option value='47'>47</option>
								<option value='48'>48</option>
								<option value='49'>49</option>
								<option value='50'>50</option>
								<option value='51'>51</option>
								<option value='52'>52</option>
								<option value='53'>53</option>
								<option value='54' selected>54</option>
							</select>
						</div>
					</div>
				</div>
				<div id="containerGraficoAvanzamento">
					<div id="funcionListGraficoAvanzamento">
						<a href="gestioneAttivita.php" class="funcionListGraficoAvanzamentoLink">Modifica colori attivita</a>
						<a href="gestioneAttivitaProgrammate.php" class="funcionListGraficoAvanzamentoLink">Modifica colori attivita programmate</a>
						<a href="#" onclick="apriPopupProprietaGrafico()" class="funcionListGraficoAvanzamentoLink">Proprieta grafico</a>
						<div class="funcionListGraficoAvanzamentoMenu">
							<i class="far fa-bars" style="float:right" onclick="openContextMenu(event)"></i>
							<div id="contextMenuGraficoAvanzamento" class="contextMenuGraficoAvanzamento">
								<table class="tableContextMenu">
									<tr onclick="scaricaExcelGraficoAvanzamento()">
										<td><i class="fal fa-file-excel" title="Scarica Excel origine dati" ></i></td>
										<td>Scarica Excel origine dati</td>
									</tr>
									<tr id="btnStampaGraficoGraficoAvanzamento">
										<td><i class="fal fa-print" title="Stampa grafico" ></i></td>
										<td>Stampa grafico</td>
									</tr>
									<tr id="btnScaricaImmagineGraficoAvanzamento">
										<td><i class="fal fa-image" title="Scarica immagine grafico" ></i></td>
										<td>Scarica immagine grafico</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div id="chartContainer" style="margin-top:20px;width:100%;height:400px;display:inline-block"></div>
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

	function getElencoGruppi($conn)
	{
		$query2="SELECT * FROM gruppi WHERE commessa=".$_SESSION['id_commessa']." AND grafico='true' ORDER BY nomeGruppo";	
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
				echo '<option value="'.$row2["id_gruppo"].'">'.$row2["nomeGruppo"].'</td>';
			}
		}
	}
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
	function getElencoDitte($conn)
	{
		
	}

?>


