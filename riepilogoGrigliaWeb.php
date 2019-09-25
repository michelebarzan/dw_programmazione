<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Riepilogo griglia web";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV31.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="editableTable/editableTable.js"></script>
		<script src="css_libraries/spinners/spinner.js"></script>
		<script src="jquery.table2excel.js"></script>
		<link rel="stylesheet" href="css_libraries/spinners/spinner.css" />
		<link rel="stylesheet" href="editableTable/editableTable.css" />
		<script src="struttura.js"></script>
		<script>
			/*function getNoteGrigliaWeb()
			{
				if(document.getElementById("myTableRiepilogoGrigliaWeb")==null)
				{
					var numero_cabina="%";
					var attivita="%";
					var ponte="%";
					var firezone="%";
					var famiglia="%";
					var tipologia="%";
					var utente="%";
				}
				else
				{
					var numero_cabina=document.getElementById("selectFiltronumero_cabina").value;
					var attivita=document.getElementById("selectFiltroattivita").value;
					var ponte=document.getElementById("selectFiltroponte").value;
					var firezone=document.getElementById("selectFiltrofirezone").value;
					var famiglia=document.getElementById("selectFiltrofamiglia").value;
					var tipologia=document.getElementById("selectFiltrotipologia").value;
					var utente=document.getElementById("selectFiltroutente").value;
				}
				newGridSpinner("Caricamento in corso...","riepilogoGrigliaWebContainer","","","font-size:12px;color:#2B586F");
				$.post("getNoteGrigliaWeb.php",
				{
					numero_cabina,
					attivita,
					ponte,
					firezone,
					famiglia,
					tipologia,
					utente
				},
				function(response, status)
				{
					if(status=="success")
					{
						document.getElementById("riepilogoGrigliaWebContainer").innerHTML=response;
					}
				});
			}
			function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
			{
				document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
			}
			function sortTable(index,table,order) 
			{
				var table, rows, switching, i, x, y, shouldSwitch;
				table = document.getElementById(table);
				switching = true;
				
				while (switching) 
				{
					// Start by saying: no switching is done:
					switching = false;
					rows = table.rows;
					
					for (i = 1; i < (rows.length - 1); i++) 
					{
						// Start by saying there should be no switching:
						shouldSwitch = false;
						
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
						
						rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						switching = true;
					}
				}
			}
			function eliminaNotaGrigliaWeb(id_nota)
			{
				$.post("eliminaNotaGrigliaWeb.php",
				{
					id_nota
				},
				function(response, status)
				{
					if(status=="success")
					{
						if(response=="ok")
							getNoteGrigliaWeb();
						else
							window.alert(response);
					}
				});
			}*/
			function getTable(table,orderBy,orderType)
			{
				getEditableTable
				({
					table:'programmazione_riepilogo_griglia_web',
					editable:false,
					primaryKey:"id_nota",
					noFilterColumns:["data_ultima_modifica"],
					container:'riepilogoGrigliaWebContainer',
					foreignKeys:[['id_commessa','commesse','id_commessa','commessa']],
					orderBy:orderBy,
					orderType:orderType
				});
			}
			function editableTableLoad()
			{
				var table=document.getElementById("myTableprogrammazione_riepilogo_griglia_web");
				var row = table.rows[0];
				var th=document.createElement("th");
				//th.setAttribute("colspan","2");
				row.appendChild(th);
				for (var i = 1, row; row = table.rows[i]; i++)
				{
					var td=document.createElement("td");
					//td.setAttribute("colspan","2");
					td.setAttribute("text-align","center");
					var trashIcon=document.createElement("i");
					trashIcon.setAttribute("class","far fa-trash btnDeleteEditableTable");
					trashIcon.setAttribute("title","Elimina");
					trashIcon.setAttribute("onclick","eliminaNotaGrigliaWeb("+row.cells[0].innerHTML+")");
					td.appendChild(trashIcon);
					row.appendChild(td);
				}
			}
			function eliminaNotaGrigliaWeb(id_nota)
			{
				$.post("eliminaNotaGrigliaWeb.php",
				{
					id_nota
				},
				function(response, status)
				{
					if(status=="success")
					{
						if(response=="ok")
							getTable('programmazione_riepilogo_griglia_web')
						else
							window.alert(response);
					}
				});
			}
		</script>
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getTable('programmazione_riepilogo_griglia_web')">
		<?php include('struttura.php'); ?>
		<div id="immagineLogo" class="immagineLogo" style="margin-top:60px"></div>
		<div class="absoluteActionBar">
			<div class="absoluteActionBarElement">Righe: <span id="rowsNumEditableTable"></span></div>
			<button class="absoluteActionBarButton" onclick="excelExport('riepilogoGrigliaWebContainer')">Esporta <i style="margin-left:5px;color:green" class="far fa-file-excel"></i></button>
			<button class="absoluteActionBarButton" onclick="resetFilters();getTable(selectetTable)">Ripristina <i style="margin-left:5px" class="fal fa-filter"></i></button>
		</div>
		<div id="riepilogoGrigliaWebContainer"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>



