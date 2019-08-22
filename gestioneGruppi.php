<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Gestione gruppi";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV30.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<script src="struttura.js"></script>
		<script>
			function anagraficaGruppi()
			{
				document.getElementById('containerGestioneGruppi').innerHTML='';
				document.getElementById("btnFunctionListAttivitaCollegate").classList.remove("btnFunctionListActive");
				document.getElementById("btnFunctionListAttivitaCollegate").className="btnFunctionList";
				document.getElementById("btnFunctionListAnagraficaGruppi").classList.remove("btnFunctionList");
				document.getElementById("btnFunctionListAnagraficaGruppi").className="btnFunctionListActive";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerGestioneGruppi').innerHTML=this.responseText;
						}
					}
				};
				xmlhttp.open("POST", "getAnagraficaGruppi.php?", true);
				xmlhttp.send();
			}
			function salvaModificheAnagraficaGruppo(id_gruppo)
			{
				var nomeGruppo=document.getElementById('nomeGruppo'+id_gruppo).value;
				var griglia=document.getElementById('grigliaGruppo'+id_gruppo).checked;
				var esportazione=document.getElementById('esportazioneGruppo'+id_gruppo).checked;
				var grafico=document.getElementById('graficoGruppo'+id_gruppo).checked;
				if(nomeGruppo=='')
					window.alert("Il campo nome e obbligatorio");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							console.log(this.responseText);
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							{
								window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
								console.log(this.responseText);
							}
							else
							{
								document.getElementById('risultato'+id_gruppo).innerHTML='<i class="fas fa-check"></i>';
							}
						}
					};
					xmlhttp.open("POST", "salvaModificheAnagraficaGruppo.php?id_gruppo="+id_gruppo+"&nomeGruppo="+nomeGruppo+"&griglia="+griglia+"&esportazione="+esportazione+"&grafico="+grafico, true);
					xmlhttp.send();
				}
			}
			function inserisciGruppo()
			{
				var nomeGruppo=document.getElementById('nomeNuovoGruppo').value;
				var griglia=document.getElementById('grigliaNuovoGruppo').checked;
				var esportazione=document.getElementById('esportazioneNuovoGruppo').checked;
				var grafico=document.getElementById('graficoNuovoGruppo').checked;
				if(nomeGruppo=='')
					window.alert("Il campo nome e obbligatorio");
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							console.log(this.responseText);
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							{
								window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
								console.log(this.responseText);
							}
							else
							{
								document.getElementById('risultatoNuovoGruppo').innerHTML='<i class="fas fa-check"></i>';
								anagraficaGruppi();
							}
						}
					};
					xmlhttp.open("POST", "inserisciAnagraficaGruppo.php?nomeGruppo="+nomeGruppo+"&griglia="+griglia+"&esportazione="+esportazione+"&grafico="+grafico, true);
					xmlhttp.send();
				}
			}
			function attivitaCollegate()
			{
				document.getElementById('containerGestioneGruppi').innerHTML='';
				document.getElementById("btnFunctionListAnagraficaGruppi").classList.remove("btnFunctionListActive");
				document.getElementById("btnFunctionListAnagraficaGruppi").className="btnFunctionList";
				document.getElementById("btnFunctionListAttivitaCollegate").classList.remove("btnFunctionList");
				document.getElementById("btnFunctionListAttivitaCollegate").className="btnFunctionListActive";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerGestioneGruppi').innerHTML=this.responseText;
							createSearchFieldEngine("searchFieldGruppi","containerElementsGruppi");
							createSearchFieldEngine("searchFieldAttivitaCollegate","containerElementsAttivitaCollegate");
							createSearchFieldEngine("searchFieldAttivita","containerElementsAttivita");
							createSearchFieldEngine("searchFieldAttivitaProgrammate","containerElementsAttivitaProgrammate");
						}
					}
				};
				xmlhttp.open("POST", "getAttivitaCollegate.php?", true);
				xmlhttp.send();
			}
			function selezionaGruppo(id_gruppo)
			{
				var all=document.getElementsByClassName("elementGestioneGruppiActive");
				for (var i = 0; i < all.length; i++) 
				{
					var id=all[i].id;
					document.getElementById(id).className="elementGestioneGruppi";
					document.getElementById(id).classList.remove("elementGestioneGruppiActive");
				}
				document.getElementById("nomeGruppo"+id_gruppo).className="elementGestioneGruppiActive";
				document.getElementById("nomeGruppo"+id_gruppo).classList.remove("elementGestioneGruppi");
				document.getElementById("nomeGruppo"+id_gruppo).className="elementGestioneGruppiActive";
				getElementsAttivitaCollegate(id_gruppo);
				getElementsAttivita(id_gruppo);
				getElementsAttivitaProgrammate(id_gruppo);
			}
			function getElementsAttivitaCollegate(id_gruppo)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerElementsAttivitaCollegate').innerHTML=this.responseText;
						}
					}
				};
				xmlhttp.open("POST", "getElementsAttivitaCollegate.php?id_gruppo="+id_gruppo, true);
				xmlhttp.send();
			}
			function getElementsAttivita(id_gruppo)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerElementsAttivita').innerHTML=this.responseText;
						}
					}
				};
				xmlhttp.open("POST", "getElementsAttivita.php?id_gruppo="+id_gruppo, true);
				xmlhttp.send();
			}
			function getElementsAttivitaProgrammate(id_gruppo)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerElementsAttivitaProgrammate').innerHTML=this.responseText;
						}
					}
				};
				xmlhttp.open("POST", "getElementsAttivitaProgrammate.php?id_gruppo="+id_gruppo, true);
				xmlhttp.send();
			}
			function rimuoviAttivitaCollegata(id_gruppo,id,campo,tabella)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							selezionaGruppo(id_gruppo);
						}
					}
				};
				xmlhttp.open("POST", "rimuoviAttivitaCollegata.php?id="+id+"&campo="+campo+"&tabella="+tabella, true);
				xmlhttp.send();
			}
			function createSearchFieldEngine(searchField,elementsContainer)
			{
				$("#"+searchField).on("keyup", function() 
				{
					var value = $(this).val().toLowerCase();
					$("#"+elementsContainer+" *").filter(function() 
					{
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
			}
			//DRAG AND DROP---------------------------------------------------------------------------------------------------------------------------------------------------------------------
			function allowDrop(ev,weekN) 
			{
				ev.preventDefault();
			}
			function fixContainerStyle()
			{
				document.getElementById('containerElementsAttivitaCollegate').style.outline='';
				document.getElementById('containerElementsAttivitaCollegate').style.backgroundColor='';
			}
			function dragLeave(ev,weekN)
			{
			}
			function drag(ev) 
			{
				ev.dataTransfer.setData("div", ev.target.id);
				var data = ev.dataTransfer.getData("div");
				document.getElementById('containerElementsAttivitaCollegate').style.outline="2px dashed #2B586F";
				document.getElementById('containerElementsAttivitaCollegate').style.backgroundColor="#C0D0D8";
			}
			function drop(ev) 
			{
				ev.preventDefault();
				var data = ev.dataTransfer.getData("div");
				
				if(data.indexOf("attivitaProgrammata")!=-1)
				{
					var id_gruppo=data.split("attivitaProgrammata")[0];
					var codice_attivita=data.split("attivitaProgrammata")[1];
					var tabella="gruppo_attivita_programmata";
				}
				else
				{
					var id_gruppo=data.split("attivita")[0];
					var codice_attivita=data.split("attivita")[1];
					var tabella="gruppo_attivita";
				}
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerElementsAttivitaCollegate').appendChild(document.getElementById(data));
							selezionaGruppo(id_gruppo);
						}
					}
				};
				xmlhttp.open("POST", "aggiungiAttivita.php?id_gruppo="+id_gruppo+"&codice_attivita="+codice_attivita+"&tabella="+tabella, true);
				xmlhttp.send();
			}
			function spostaAttivita(data)
			{
				console.log(data);
				if(data.indexOf("attivitaProgrammata")!=-1)
				{
					var id_gruppo=data.split("attivitaProgrammata")[0];
					var codice_attivita=data.split("attivitaProgrammata")[1];
					var tabella="gruppo_attivita_programmata";
				}
				else
				{
					var id_gruppo=data.split("attivita")[0];
					var codice_attivita=data.split("attivita")[1];
					var tabella="gruppo_attivita";
				}
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore di sistema. Se il problema persiste contattare l' amministratore");
							console.log(this.responseText);
						}
						else
						{
							document.getElementById('containerElementsAttivitaCollegate').appendChild(document.getElementById(data));
							selezionaGruppo(id_gruppo);
						}
					}
				};
				xmlhttp.open("POST", "aggiungiAttivita.php?id_gruppo="+id_gruppo+"&codice_attivita="+codice_attivita+"&tabella="+tabella, true);
				xmlhttp.send();
			}
		</script>
		<style>
		html
		{
			-webkit-touch-callout: none; /* iOS Safari */
			-webkit-user-select: none; /* Safari */
			-khtml-user-select: none; /* Konqueror HTML */
			-moz-user-select: none; /* Firefox */
			-ms-user-select: none; /* Internet Explorer/Edge */
			user-select: none; /* Non-prefixed version, currently supported by Chrome and Opera */
		}
		</style>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>')">
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div class="funcionListContainer">
					<div class="functionList">
						<button class="btnFunctionList" id="btnFunctionListAnagraficaGruppi" onclick="anagraficaGruppi()"><span>Anagrafica gruppi<i class="fal fa-table" style="margin-left:10px;"></i></span></button>
						<button class="btnFunctionList" id="btnFunctionListAttivitaCollegate" onclick="attivitaCollegate()"><span>Attivita collegate<i class="fas fa-layer-group" style="margin-left:10px;"></i></span></button>
					</div>
				</div>
				<div id="containerGestioneGruppi"></div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>



