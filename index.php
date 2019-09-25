<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Homepage";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<link rel="stylesheet" href="css/styleV31.css" />
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
		<script src="struttura.js"></script>
		<script>
			function setCommessa(valore)
			{
				var id_commessa=valore.split("|")[0];
				var commessa=valore.split("|")[1];
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							window.alert("Errore. Impossibile selezionare la commessa. Se il problema persiste contattare l' amministratore.");
					}
				};
				xmlhttp.open("POST", "setCommessa.php?id_commessa="+id_commessa+"&commessa="+commessa, true);
				xmlhttp.send();
			}
		</script>
	</head>
	<body onload='aggiungiNotifica("Ricordati di selezionare la commessa. Il programma ricordera la tua scelta")'>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="immagineLogo" class="immagineLogo" ></div>
				<div class="homepageCommessaContainer">
					<span>Stai lavorando sulla commessa </span>
					<select id="selectCommessaHomepage" onchange="setCommessa(this.value)">
						<?php
							$queryPonte="SELECT * FROM commesse ORDER BY commessa";
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
									if($_COOKIE['id_commessa']==$rowPonte['id_commessa'])
									{
										echo "<option value='".$rowPonte['id_commessa']."|".$rowPonte['commessa']."' selected='selected'>".$rowPonte['commessa']."</option>";
										$_SESSION['id_commessa']=$_COOKIE['id_commessa'];
										$_SESSION['commessa']=$_COOKIE['commessa'];
									}
									else
										echo "<option value='".$rowPonte['id_commessa']."|".$rowPonte['commessa']."' >".$rowPonte['commessa']."</option>";
								}
								if(!isset($_COOKIE['id_commessa']))
								{
									echo '<script>setCommessa(document.getElementById("selectCommessaHomepage").value);</script>';
								}
							}
						?>
					</select>
				</div>
				<div class="homepageLinkContainer">
					<div class="homepageLink" title="Assegna i permessi agli utenti dell' applicazione cantiere" onclick="gotopath('permessiCantiere.php')">
						<i class="fal fa-users fa-2x"></i>
						<span>Permessi cantiere</span>
					</div>
					<div class="homepageLink" title="Consulta il riepilogo delle registrazioni presenze ditte" onclick="gotopath('riepilogoPresenzeDitte.php')">
						<i class="fal fa-chart-area fa-2x"></i>
						<span>Riepilogo presenze ditte</span>
					</div>
					<div class="homepageLink" title="Consulta il grafico dell' avanzamento programmazione" onclick="gotopath('graficoAvanzamento.php')">
						<i class="fal fa-chart-line fa-2x"></i>
						<span>Grafico avanzamento</span>
					</div>
					<div class="homepageLink" title="Consulta ed esporta le registrazioni della griglia web" onclick="gotopath('esportazioneGriglia.php')">
						<i class="fal fa-file-excel fa-2x"></i>
						<span>Esportazione griglia</span>
					</div>
					<div class="homepageLink" title="Gestisci le attivita programmate, le cabine correlate, le tempistiche e le attivita correlate" onclick="gotopath('gestioneAttivitaProgrammate.php')">
						<i class="fal fa-tasks fa-2x"></i>
						<span>Gestione attivita programmate</span>
					</div>
					<div class="homepageLink" title="Gestisci le attivita" onclick="gotopath('gestioneAttivita.php')">
						<i class="fal fa-clipboard-list fa-2x"></i>
						<span>Gestione<br>attivita</span>
					</div>
					<div class="homepageLink" title="Gestisci i raggruppamenti delle attivita" onclick="gotopath('gestioneGruppi.php')">
						<i class="fal fa-layer-group fa-2x"></i>
						<span>Gestione<br>gruppi</span>
					</div>
					<div class="homepageLink" title="Consulta il riepilogo delle registrazioni nella griglia web" onclick="gotopath('riepilogoGrigliaWeb.php')">
						<i class="fal fa-th fa-2x"></i>
						<span>Riepilogo<br>griglia web</span>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>



