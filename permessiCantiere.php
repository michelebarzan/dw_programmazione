<?php
	include "Session.php";
	include "connessione.php";
	
	$pageName="Permessi cantiere";
	$appName="Programmazione";
?>
<html>
	<head>
		<title><?php echo $appName."&nbsp&#8594&nbsp".$pageName; ?></title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
		<script src="struttura.js"></script>
		<link rel="stylesheet" href="css/styleV31.css" />
		<script>
			function getTabellaPermessiUtenti()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('containerGestioneUtenti').innerHTML=this.responseText;
						/*if(this.responseText=="ok")
						{
							creaTabella();
						}
						else
							window.alert("Errore"+this.responseText);*/
					}
				};
				xmlhttp.open("POST", "getTabellaPermessiUtenti.php?", true);
				xmlhttp.send();
			}
			function eliminaPermessoGruppo(id_permessi_gruppo)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
						{
							document.getElementById("liItemGruppo"+id_permessi_gruppo).remove();
						}
						else
							window.alert("Errore: "+this.responseText);
					}
				};
				xmlhttp.open("POST", "eliminaPermessoGruppo.php?id_permessi_gruppo="+id_permessi_gruppo, true);
				xmlhttp.send();
			}
			function eliminaPermessoPonte(id_permessi_ponte)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
						{
							document.getElementById("liItemPonte"+id_permessi_ponte).remove();
						}
						else
							window.alert("Errore: "+this.responseText);
					}
				};
				xmlhttp.open("POST", "eliminaPermessoPonte.php?id_permessi_ponte="+id_permessi_ponte, true);
				xmlhttp.send();
			}
			function eliminaPermessoFirezone(id_permessi_firezone)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText=="ok")
						{
							document.getElementById("liItemFirezone"+id_permessi_firezone).remove();
						}
						else
							window.alert("Errore: "+this.responseText);
					}
				};
				xmlhttp.open("POST", "eliminaPermessoFirezone.php?id_permessi_firezone="+id_permessi_firezone, true);
				xmlhttp.send();
			}
			function aggiungiGruppo(utente,valore)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore: "+this.responseText);
						}
						else
						{
							var ul = document.getElementById("listaGruppo"+utente);
							var li = document.createElement("li");
							var btn=document.createElement("input");
							btn.setAttribute("type","button");
							btn.setAttribute("class","btnEliminaPermesso");
							btn.setAttribute("value","Elimina");
							btn.setAttribute("onclick","eliminaPermessoGruppo("+this.responseText+")");
							li.setAttribute("class","liListaPermessi");
							li.setAttribute("id","liItemGruppo"+this.responseText);
							var xmlhttp = new XMLHttpRequest();
							xmlhttp.onreadystatechange = function() 
							{
								if (this.readyState == 4 && this.status == 200) 
								{
									li.appendChild(document.createTextNode(this.responseText));
									li.appendChild(btn);
									ul.appendChild(li);
								}
							};
							xmlhttp.open("POST", "getNomeGruppo.php?id_gruppo="+valore, true);
							xmlhttp.send();
						}
					}
				};
				xmlhttp.open("POST", "aggiungiGruppo.php?utente="+utente+"&gruppo="+valore, true);
				xmlhttp.send();
			}
			function aggiungiPonte(utente,valore)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore: "+this.responseText);
						}
						else
						{
							var ul = document.getElementById("listaDeck"+utente);
							var li = document.createElement("li");
							var btn=document.createElement("input");
							btn.setAttribute("type","button");
							btn.setAttribute("class","btnEliminaPermesso");
							btn.setAttribute("value","Elimina");
							btn.setAttribute("onclick","eliminaPermessoPonte("+this.responseText+")");
							li.setAttribute("class","liListaPermessi");
							li.setAttribute("id","liItemPonte"+this.responseText);
							li.appendChild(document.createTextNode(valore));
							li.appendChild(btn);
							ul.appendChild(li);
						}
					}
				};
				xmlhttp.open("POST", "aggiungiPonte.php?utente="+utente+"&Deck="+valore, true);
				xmlhttp.send();
			}
			function aggiungiFirezone(utente,valore)
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
						{
							window.alert("Errore: "+this.responseText);
						}
						else
						{
							var ul = document.getElementById("listaFZ"+utente);
							var li = document.createElement("li");
							var btn=document.createElement("input");
							btn.setAttribute("type","button");
							btn.setAttribute("class","btnEliminaPermesso");
							btn.setAttribute("value","Elimina");
							btn.setAttribute("onclick","eliminaPermessoFirezone("+this.responseText+")");
							li.setAttribute("class","liListaPermessi");
							li.setAttribute("id","liItemFirezone"+this.responseText);
							li.appendChild(document.createTextNode(valore));
							li.appendChild(btn);
							ul.appendChild(li);
						}
					}
				};
				xmlhttp.open("POST", "aggiungiFirezone.php?utente="+utente+"&FZ="+valore, true);
				xmlhttp.send();
			}
		</script>
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	</head>
	<body onload="aggiungiNotifica('Stai lavorando sulla commessa <?php echo $_SESSION['commessa']; ?>');getTabellaPermessiUtenti()">
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div id="containerGestioneUtenti"></div>
			</div>
		</div>
		<div id="push"></div>
		<div id="footer">
			<b>De&nbspWave&nbspS.r.l.</b>&nbsp&nbsp|&nbsp&nbspVia&nbspDe&nbspMarini&nbsp116149&nbspGenova&nbspItaly&nbsp&nbsp|&nbsp&nbspPhone:&nbsp(+39)&nbsp010&nbsp640201
		</div>
	</body>
</html>

