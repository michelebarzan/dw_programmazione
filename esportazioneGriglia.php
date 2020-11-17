<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Esportazione griglia";
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
		<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.0/css/jquery.dataTables.css" />
		<script type="text/javascript" src="http://cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/modernizr-2.7.1.js"></script>
		<script src="js_libraries/jquery.table2excel.js"></script>
		<script src="struttura.js"></script>
		<script>
			function getEsportazioneGriglia()
			{
				document.getElementById('containerEsportazioneGriglia').innerHTML="";
				document.getElementById("totRigheContainer").innerHTML="";
				
				var gruppo=document.getElementById('selectEsportazioneGrigliaGruppo').value;
				var ponte=document.getElementById('selectEsportazioneGrigliaPonte').value;
				var firezone=document.getElementById('selectEsportazioneGrigliaFirezone').value;
				var ditta=document.getElementById('selectEsportazioneGrigliaDitta').value;
				var famiglia=document.getElementById('selectEsportazioneGrigliaFamiglia').value;
				var tipologia=document.getElementById('selectEsportazioneGrigliaTipologia').value;
				var dataInizio=document.getElementById('selectEsportazioneGrigliaDataInizio').value;
				var dataFine=document.getElementById('selectEsportazioneGrigliaDataFine').value;

				var orderBy=document.getElementById("funcionListEsportazioneGrigliaSelectOrdinamento").value;
				
				document.getElementById("btnRangeDate").value=dataInizio.split("-")[2]+"/"+dataInizio.split("-")[1]+"/"+dataInizio.split("-")[0]+"-"+dataFine.split("-")[2]+"/"+dataFine.split("-")[1]+"/"+dataFine.split("-")[0];
				
				/*console.log(dataInizio);
				console.log(dataFine);*/
				
				newGridSpinner("Caricamento dati in corso...","containerEsportazioneGriglia","","","font-size:80%;color:#2B586F");		
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					
					if (this.readyState == 4 && this.status == 200) 
					{
						/*console.log(this.responseText);*/
						document.getElementById('containerEsportazioneGriglia').innerHTML=this.responseText.split("|")[0];
						document.getElementById("totRigheContainer").innerHTML=this.responseText.split("|")[1];
						document.getElementById('div1').style.width=document.getElementById('myTableEsportazioneGriglia').offsetWidth;
						$(".wrapper1").scroll(function(){
							$("#containerEsportazioneGriglia")
								.scrollLeft($(".wrapper1").scrollLeft());
						});
						$("#containerEsportazioneGriglia").scroll(function(){
							$(".wrapper1")
								.scrollLeft($("#containerEsportazioneGriglia").scrollLeft());
						});
					}
				};
				xmlhttp.open("POST", "getEsportazioneGriglia.php?gruppo="+gruppo+"&ponte="+ponte+"&firezone="+firezone+"&ditta="+ditta+"&famiglia="+famiglia+"&tipologia="+tipologia+"&dataInizio="+dataInizio+"&dataFine="+dataFine+"&orderBy="+orderBy, true);
				xmlhttp.send();
			}
			function openContextMenu(event)
			{
				document.getElementById("contextMenuGraficoAvanzamento").style.display="inline-block";
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function scaricaExcel(table)
			{
				console.log("#"+table);
				$("#"+table).table2excel({
				// exclude CSS class
				exclude: ".noExl",
				name: "Esportazione griglia",
				filename: "Esportazione griglia" //do not include extension
				});
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
			function apriPopupRangeDate()
			{
				document.getElementById("modalRangeDateEsportazioneGrigiaContainer").style.display="table";
			}
			function chiudiPopupRangeDate()
			{
				document.getElementById("modalRangeDateEsportazioneGrigiaContainer").style.display="none";
			}
		</script>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getEsportazioneGriglia()">
		<?php include('struttura.php'); ?>
		<div class="modalRangeDateEsportazioneGrigiaContainer" id="modalRangeDateEsportazioneGrigiaContainer">
			<div class="modalRangeDateEsportazioneGrigiaContainerMiddle">
				<div class="modalRangeDateEsportazioneGrigiaContainerInner">
					<div class="modalRangeDateEsportazioneGrigia">
						<div class="modalRangeDateEsportazioneGrigiaHeader">
							<div>Scegli un range di date</div>
							<button onclick="chiudiPopupRangeDate()"></button>
						</div>
						<div class="modalRangeDateEsportazioneGrigiaBody">
							<div style="float:left;height:50px;line-height:70px;">Data inizio</div>
							<div style="float:right;height:50px;line-height:70px;">Data fine</div>
							<div style="float:left;height:80px;line-height:80px;"><input type="date" class="dateFunctionListModal" id="selectEsportazioneGrigliaDataInizio" value="2018-01-01" style="margin-bottom:10px;" /></div>
							<div style="float:right;height:80px;line-height:80px;"><input type="date" class="dateFunctionListModal" id="selectEsportazioneGrigliaDataFine" value="<?php echo date('Y-m-d'); ?>" /></div>
						</div>
						<div class="modalRangeDateEsportazioneGrigiaFooter">
							<button onclick="chiudiPopupRangeDate()">Annulla</button>
							<button id="btnConfermaPopupRangeDate" onclick="getEsportazioneGriglia();chiudiPopupRangeDate()">Conferma</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="content">
				<!--<div id="immagineLogo" class="immagineLogo" ></div>-->
				<div class="funcionListContainer" style="top:90">
					<div class="functionList">
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Gruppo</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaGruppo">
								<?php getElencoGruppi($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Ponte</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaPonte">
								<option value="%">Tutti</option>
								<?php getElencoPonti($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Firezone</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaFirezone">
								<option value="%">Tutte</option>
								<?php getElencoFirezone($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Ditta</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaDitta">
								<option value="%">Tutte</option>
								<?php getElencoDitte($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Famiglia</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaFamiglia">
								<option value="%">Tutte</option>
								<?php getElencoFamiglie($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Tipologia</div>
							<select class="selectFunctionList" onchange="getEsportazioneGriglia()" id="selectEsportazioneGrigliaTipologia">
								<option value="%">Tutte</option>
								<?php getElencoTipologie($conn); ?>
							</select>
						</div>
						<div class="functionListFieldContainer">
							<div class="titleFuncionList">Range date</div>
							<input type="button" id="btnRangeDate" value="01/01/2018-<?php echo date('d/m/Y'); ?>" class="selectFunctionList" style="padding:0px; text-align:center" onclick="apriPopupRangeDate()">
							<!--<input type="date" class="dateFunctionList" id="selectEsportazioneGrigliaDataInizio" value="2018-01-01" onkeydown="return false" onchange="getEsportazioneGriglia()" style="width:70px;padding:0px" />
							<input type="date" class="dateFunctionList" id="selectEsportazioneGrigliaDataFine" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" onchange="getEsportazioneGriglia()" style="width:70px;padding:0px;margin-left:10px" />-->
						</div>
					</div>
				</div>
				<div id="funcionListEsportazioneGriglia">
					<div class="funcionListEsportazioneGrigliaDiv">
						<span>Tot. righe: </span>
						<span id="totRigheContainer">0</span>
					</div>
					<div class="funcionListEsportazioneGrigliaDiv">
						<span>Ordinamento attività</span>
						<select id="funcionListEsportazioneGrigliaSelectOrdinamento" onchange="getEsportazioneGriglia()">
							<option value="posizione DESC">Posizione descrescente</option>
							<option value="posizione ASC">Posizione crescente</option>
							<option value="descrizione DESC">Nome descrescente</option>
							<option value="descrizione ASC">Nome crescente</option>
						</select>
					</div>
					<div class="funcionListGraficoAvanzamentoMenu" style="margin-left:auto;margin-right:15px">
						<i class="far fa-bars" style="float:right" onclick="openContextMenu(event)"></i>
						<div id="contextMenuGraficoAvanzamento" class="contextMenuGraficoAvanzamento">
							<table class="tableContextMenu">
								<tr onclick="scaricaExcel('myTableEsportazioneGriglia')">
									<td><i class="fal fa-file-excel" title="Scarica Excel" ></i></td>
									<td>Scarica Excel</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div id="esportazioneGrigliaContainer">
					<div class="wrapper1">
						<div id="div1">
						</div>
					</div>
					<div id="containerEsportazioneGriglia"></div>
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
		$query2="SELECT * FROM gruppi WHERE commessa=".$_SESSION['id_commessa']." AND esportazione='true' ORDER BY nomeGruppo";	
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
	function getElencoFamiglie($conn)
	{
		$query2="SELECT DISTINCT Famiglia FROM [tip cab] WHERE commessa=".$_SESSION['id_commessa']." ORDER BY Famiglia";	
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
				echo '<option value="'.$row2["Famiglia"].'">'.$row2["Famiglia"].'</td>';
			}
		}
	}
	function getElencoTipologie($conn)
	{
		$query2="SELECT DISTINCT [Pax/Crew] FROM [tip cab] WHERE commessa=".$_SESSION['id_commessa']." ORDER BY [Pax/Crew]";	
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
				echo '<option value="'.$row2["Pax/Crew"].'">'.$row2["Pax/Crew"].'</td>';
			}
		}
	}
?>