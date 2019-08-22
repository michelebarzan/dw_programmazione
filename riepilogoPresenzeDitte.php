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
		<script src="tableToExcel.js"></script>
		<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css" />
		<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
		<script src="html2canvas.js"></script>
		<script src="canvasjs.min.js"></script>
		<script>
			var containers=["","","","","",""];
			var grafico0;
			var tabella0;
			var grafico1;
			var tabella1;
			var grafico2;
			var tabella2;
			var grafico3;
			
			function getGrafico0(container)
			{
				containers[0]="grafico";
				document.getElementById(container).innerHTML="";
				document.getElementById('selectAnno0').style.display="inline-block";
				document.getElementById('selectMese0').style.display="inline-block";
				document.getElementById('selectPonte0').style.display="inline-block";
				var anno=document.getElementById('selectAnno0').value;
				var mese=document.getElementById('selectMese0').value;
				//var ponte=document.getElementById('selectPonte0').value;
				if(document.getElementById("checkBoxPonteTutti").checked==true)
					var ponte="%";
				else
				{
					var ponte="";
					/*if(document.getElementById("checkBoxPonteGen").checked==true)
						ponte+="'gen',";
					if(document.getElementById("checkBoxPontePref").checked==true)
						ponte+="'pref',";*/
					var all=document.getElementsByClassName("checkBoxPonte");
					for (var i = 0; i < all.length; i++) 
					{
						if(all[i].checked==true)
							ponte+="'"+all[i].id.replace("checkBoxPonte", "")+"',";
					}
					ponte = ponte.substring(0, ponte.length - 1);
				}
				if(grafico0==1)
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							{
								console.log(this.responseText);
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							}
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var nome=res[0];
									var operatori=res[1];
									dataPoints.push
									({
										label: nome,
										y: parseInt(operatori)
									});
								}
								if(mese=="%")
									var meseText="tutti";
								else
									var meseText=mese;
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale giorni lavorati dalle ditte nel mese ["+meseText+"] del "+anno+" per i ponti ["+ponteText+"]"
									},
									axisY: {
										title: "Giorni"
									},
									axisX: {
										title: "Ditte"
									},
									data: [{
										type: "column",
										yValueFormatString: "#,##0.## giorni",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico0tipo1.php?anno="+anno+"&mese="+mese+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				else
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var nome=res[0];
									var ore=res[1];
									dataPoints.push
									({
										label: nome,
										y: parseInt(ore)
									});
								}
								if(mese=="%")
									var meseText="tutti";
								else
									var meseText=mese;
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale ore lavorate dalle ditte nel mese ["+meseText+"] del "+anno+" per il ponte ["+ponteText+"]"
									},
									axisY: {
										title: "Ore"
									},
									axisX: {
										title: "Ditte"
									},
									data: [{
										type: "column",
										yValueFormatString: "#,##0.## ore",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico0tipo2.php?anno="+anno+"&mese="+mese+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				chiudiPopupPonti();
			}
			function getTabella0()
			{
				containers[0]="tabella";
				document.getElementById('chartContainer0').innerHTML="";
				document.getElementById('selectAnno0').style.display="none";
				document.getElementById('selectMese0').style.display="none";
				document.getElementById('selectPonte0').style.display="none";
				if(tabella0==1)
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
							else
							{
								document.getElementById('chartContainer0').innerHTML=this.responseText;
								new SimpleBar(document.getElementById('chartContainer0'),{timeout: 10000000});
								document.getElementById('chartContainer0').style.height="280px";
								document.getElementById('chartContainer0').style.width="90%";
							}
						}
					};
					xmlhttp.open("POST", "getTabella0Tipo1.php?", true);
					xmlhttp.send();
				}
				else
				{
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
							else
							{
								document.getElementById('chartContainer0').innerHTML=this.responseText;
								new SimpleBar(document.getElementById('chartContainer0'),{timeout: 10000000});
								document.getElementById('chartContainer0').style.height="280px";
								document.getElementById('chartContainer0').style.width="90%";
							}
						}
					};
					xmlhttp.open("POST", "getTabella0Tipo2.php?", true);
					xmlhttp.send();
				}
			}
			function getGrafico1(container)
			{
				containers[1]="grafico";
				document.getElementById(container).innerHTML="";
				document.getElementById('selectAnno1').style.display="inline-block";
				document.getElementById('selectDitta1').style.display="inline-block";
				document.getElementById('selectPonte1').style.display="inline-block";
				var anno=document.getElementById('selectAnno1').value;
				var ditta=document.getElementById('selectDitta1').value;
				if(ditta!='%')
						ditta=encodeURIComponent(ditta);
				//var ponte=document.getElementById('selectPonte1').value;
				if(document.getElementById("checkBoxPonteTutti").checked==true)
					var ponte="%";
				else
				{
					var ponte="";
					/*if(document.getElementById("checkBoxPonteGen").checked==true)
						ponte+="'gen',";
					if(document.getElementById("checkBoxPontePref").checked==true)
						ponte+="'pref',";*/
					var all=document.getElementsByClassName("checkBoxPonte");
					for (var i = 0; i < all.length; i++) 
					{
						if(all[i].checked==true)
							ponte+="'"+all[i].id.replace("checkBoxPonte", "")+"',";
					}
					ponte = ponte.substring(0, ponte.length - 1);
				}
				if(grafico1==1)
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var mese=res[0];
									var ore=res[1];
									dataPoints.push
									({
										label: mese,
										y: parseInt(ore)
									});
								}
								
								if(ditta=="%")
									var dittaText="tutte";
								else
									var dittaText=ditta;
								
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale ore lavorate nei mesi dell' anno "+anno+" della ditta ["+dittaText+"] per il ponte ["+ponteText+"]"
									},
									axisX:{
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisY:{
										title: "Ore",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisX:{
										title: "Mesi",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									toolTip:{
										enabled: false
									},
									data: [{
										type: "area",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico1tipo1.php?anno="+anno+"&ditta="+ditta+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				else
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var mese=res[0];
									var nOperatori=res[1];
									dataPoints.push
									({
										label: mese,
										y: parseInt(nOperatori)
									});
								}
								
								if(ditta=="%")
									var dittaText="tutte";
								else
									var dittaText=ditta;
								
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale giorni lavorati nei mesi dell' anno "+anno+" della ditta ["+dittaText+"] per il ponte ["+ponteText+"]"
									},
									axisX:{
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisY:{
										title: "Giorni",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisX:{
										title: "Mesi",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									toolTip:{
										enabled: false
									},
									data: [{
										type: "area",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico1tipo2.php?anno="+anno+"&ditta="+ditta+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				chiudiPopupPonti();
			}
			function getTabella1()
			{
				containers[1]="tabella";
				document.getElementById('chartContainer1').innerHTML="";
				document.getElementById('selectAnno1').style.display="none";
				document.getElementById('selectPonte1').style.display="none";
				document.getElementById('selectDitta1').style.display="none";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
						else
						{
							document.getElementById('chartContainer1').innerHTML=this.responseText;
							new SimpleBar(document.getElementById('chartContainer1'),{timeout: 10000000});
							document.getElementById('chartContainer1').style.height="280px";
							document.getElementById('chartContainer1').style.width="90%";
						}
					}
				};
				xmlhttp.open("POST", "getTabella1.php?", true);
				xmlhttp.send();
			}
			function getGrafico2(container)
			{
				containers[2]="grafico";
				document.getElementById(container).innerHTML="";
				document.getElementById('selectAnno2').style.display="inline-block";
				document.getElementById('selectDitta2').style.display="inline-block";
				document.getElementById('selectPonte2').style.display="inline-block";
				document.getElementById('selectMese2').style.display="inline-block";
				document.getElementById('checkboxLabel2').style.display="none";
				var anno=document.getElementById('selectAnno2').value;
				var ditta=document.getElementById('selectDitta2').value;
				if(ditta!='%')
						ditta=encodeURIComponent(ditta);
				//var ponte=document.getElementById('selectPonte2').value;
				if(document.getElementById("checkBoxPonteTutti").checked==true)
					var ponte="%";
				else
				{
					var ponte="";
					/*if(document.getElementById("checkBoxPonteGen").checked==true)
						ponte+="'gen',";
					if(document.getElementById("checkBoxPontePref").checked==true)
						ponte+="'pref',";*/
					var all=document.getElementsByClassName("checkBoxPonte");
					for (var i = 0; i < all.length; i++) 
					{
						if(all[i].checked==true)
							ponte+="'"+all[i].id.replace("checkBoxPonte", "")+"',";
					}
					ponte = ponte.substring(0, ponte.length - 1);
				}
				var mese=document.getElementById('selectMese2').value;
				if(grafico2==1)
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var giorno=res[0];
									var nOperatori=res[1];
									dataPoints.push
									({
										label: giorno,
										y: parseInt(nOperatori)
									});
								}
								
								if(ditta=="%")
									var dittaText="tutte";
								else
									var dittaText=ditta;
								
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale operatori nel mese "+mese+"/"+anno+" della ditta ["+dittaText+"] per il ponte ["+ponteText+"]"
									},
									axisY: {
										title: "N. operatori"
									},
									axisX: {
										title: "Giorni"
									},
									data: [{
										type: "column",
										yValueFormatString: "#,##0.## operatori",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico2tipo1.php?anno="+anno+"&mese="+mese+"&ditta="+ditta+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				if(grafico2==2)
				{
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var giorno=res[0];
									var ore=res[1];
									dataPoints.push
									({
										label: giorno,
										y: parseInt(ore)
									});
								}
								
								if(ditta=="%")
									var dittaText="tutte";
								else
									var dittaText=ditta;
								
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Totale ore nel mese "+mese+"/"+anno+" della ditta ["+dittaText+"] per il ponte ["+ponteText+"]"
									},
									axisY: {
										title: "Ore"
									},
									axisX: {
										title: "Giorni"
									},
									data: [{
										type: "column",
										yValueFormatString: "#,##0.## ore",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico2tipo2.php?anno="+anno+"&mese="+mese+"&ditta="+ditta+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				chiudiPopupPonti();
			}
			function getTabella2()
			{
				containers[2]="tabella";
				document.getElementById('chartContainer2').innerHTML="";
				document.getElementById('selectAnno2').style.display="none";
				document.getElementById('selectDitta2').style.display="none";
				document.getElementById('selectPonte2').style.display="none";
				document.getElementById('selectMese2').style.display="none";
				if(tabella2==1)
				{
					document.getElementById('checkboxLabel2').style.display="inline-block";
					var nomi=document.getElementById('checkbox2').checked;
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
							else
							{
								document.getElementById('chartContainer2').innerHTML=this.responseText;
								new SimpleBar(document.getElementById('chartContainer2'),{timeout: 10000000});
								document.getElementById('chartContainer2').style.height="280px";
								document.getElementById('chartContainer2').style.width="90%";
							}
						}
					};
					xmlhttp.open("POST", "getTabella2tipo1.php?nomi="+nomi, true);
					xmlhttp.send();
				}
				if(tabella2==2)
				{
					document.getElementById('checkboxLabel2').style.display="none";
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
							else
							{
								document.getElementById('chartContainer2').innerHTML=this.responseText;
								new SimpleBar(document.getElementById('chartContainer2'),{timeout: 10000000});
								document.getElementById('chartContainer2').style.height="280px";
								document.getElementById('chartContainer2').style.width="90%";
							}
						}
					};
					xmlhttp.open("POST", "getTabella2tipo2.php?", true);
					xmlhttp.send();
				}
			}
			function getGrafico3(container)
			{
				containers[3]="grafico";
				document.getElementById(container).innerHTML="";
				document.getElementById('selectAnno3').style.display="inline-block";
				document.getElementById('selectPonte3').style.display="inline-block";
				var anno=document.getElementById('selectAnno3').value;
				//var ponte=document.getElementById('selectPonte3').value;
				if(document.getElementById("checkBoxPonteTutti").checked==true)
					var ponte="%";
				else
				{
					var ponte="";
					/*if(document.getElementById("checkBoxPonteGen").checked==true)
						ponte+="'gen',";
					if(document.getElementById("checkBoxPontePref").checked==true)
						ponte+="'pref',";*/
					var all=document.getElementsByClassName("checkBoxPonte");
					for (var i = 0; i < all.length; i++) 
					{
						if(all[i].checked==true)
							ponte+="'"+all[i].id.replace("checkBoxPonte", "")+"',";
					}
					ponte = ponte.substring(0, ponte.length - 1);
				}
				if(grafico3==1)
				{
					document.getElementById("selectMese3").style.display="none";
					document.getElementById("selectDitta3").style.display="inline-block";
					var ditta=document.getElementById('selectDitta3').value;
					if(ditta!='%')
						ditta=encodeURIComponent(ditta);
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var mese=res[0];
									var nOperatori=res[1];
									dataPoints.push
									({
										label: mese,
										y: parseFloat(nOperatori)
									});
								}
								
								if(ditta=="%")
									var dittaText="tutte";
								else
									var dittaText=ditta;
								
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Media operatori nei mesi dell' anno "+anno+" della ditta ["+dittaText+"] per il ponte ["+ponteText+"]"
									},
									axisX:{
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisY:{
										title: "N. operatori",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									axisX:{
										title: "Mesi",
										crosshair: 
										{
											enabled: true,
											snapToDataPoint: true
										}
									},
									toolTip:{
										enabled: false
									},
									data: [{
										type: "area",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico3tipo1.php?anno="+anno+"&ditta="+ditta+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				else
				{
					document.getElementById("selectMese3").style.display="inline-block";
					document.getElementById("selectDitta3").style.display="none";
					var mese=document.getElementById("selectMese3").value;
					var dataPoints=[];
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() 
					{
						if (this.readyState == 4 && this.status == 200) 
						{
							if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
								window.alert("Errore. Impossibile visualizzare il grafico. Se il problema persiste contattare l' amministratore.");
							else
							{
								var res1=this.responseText.split("%");
								res1.pop();
								for (var i = 0; i < res1.length; i++) 
								{
									var res=res1[i].split("|");
									var nome=res[0];
									var operatori=res[1];
									dataPoints.push
									({
										label: nome,
										y: parseFloat(operatori)
									});
								}
								if(mese=="%")
									var meseText="tutti";
								else
									var meseText=mese;
								if(ponte=="%")
									var ponteText="tutti";
								else
									var ponteText=ponte.replace(/'/g, "");
								if(container=="imageContainerHD")
									var fontSize=30;
								else
									var fontSize=15;
								var chart = new CanvasJS.Chart(container, 
								{
									animationEnabled: true,
									theme: "light2",
									title:{
										fontSize: fontSize,
										fontWeight:'normal',
										color:'gray',
										text: "Media operatori dalle ditte nel mese ["+meseText+"] del "+anno+" per il ponte ["+ponteText+"]"
									},
									axisY: {
										title: "N. operatori"
									},
									axisX: {
										title: "Ditte"
									},
									data: [{
										type: "column",
										yValueFormatString: "#,##0.## operatori",
										dataPoints: dataPoints
									}]
								});
								chart.render();
							}
						}
					};
					xmlhttp.open("POST", "getGrafico3tipo2.php?anno="+anno+"&mese="+mese+"&ponte="+ponte, true);
					xmlhttp.send();
				}
				chiudiPopupPonti();
			}
			function getTabella3()
			{
				containers[3]="tabella";
				document.getElementById('chartContainer3').innerHTML="";
				document.getElementById('selectAnno3').style.display="none";
				document.getElementById('selectDitta3').style.display="none";
				document.getElementById('selectPonte3').style.display="none";
				document.getElementById('selectMese3').style.display="none";
				
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
							window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
						else
						{
							document.getElementById('chartContainer3').innerHTML=this.responseText;
							new SimpleBar(document.getElementById('chartContainer3'),{timeout: 10000000});
							document.getElementById('chartContainer3').style.height="280px";
							document.getElementById('chartContainer3').style.width="90%";
						}
					}
				};
				xmlhttp.open("POST", "getTabella3tipo1.php?", true);
				xmlhttp.send();
			}
			function getTabella4()
			{
				containers[5]="tabella";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('chartContainer5').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getTabella4.php?", true);
				xmlhttp.send();
			}
			function getTabellaErrori()
			{
				containers[4]="tabella";
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (this.readyState == 4 && this.status == 200) 
					{
						document.getElementById('chartContainer4').innerHTML=this.responseText;
					}
				};
				xmlhttp.open("POST", "getTabellaErroriPresenzeDitte.php?", true);
				xmlhttp.send();
			}
			function openContextMenu(event,n)
			{
				document.getElementById("contextMenuRiepilogoPresenzeDitte"+n).style.display="inline-block";
			}
			function stampaRiepilogo(n)
			{
				var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
				if(containers[n]=="tabella")
				{
					if(n==2)
						window.alert("La tabella contiene troppi dati per essere stampata via web. Scarica il file Excel e stampala dal suo interno.");
					else
					{
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() 
						{
							if (this.readyState == 4 && this.status == 200) 
							{
								if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
									window.alert("Errore. Impossibile stampare la tabella. Se il problema persiste contattare l' amministratore.");
								else
								{
									var newWin=window.open('','Print-Window');
									newWin.document.open();
									newWin.document.write('<html>');
									newWin.document.write('<head><link rel="stylesheet" href="css/stylePrintV2.css" /></head>');
									newWin.document.write('<body onload="window.print()">'+this.responseText+'</body></html>');
									newWin.document.close();
									setTimeout(function(){newWin.close();},20);
								}
							}
						};
						if(n==0)
						{
							if(tabella0==1)
								var tabella="getTabella0Tipo1";
							if(tabella0==2)
								var tabella="getTabella0Tipo2";
						}
						if(n==1)
							var tabella="getTabella1";
						if(n==3)
							var tabella="getTabella3tipo1";
						if(n==4)
							var tabella="getTabellaErroriPresenzeDitte";
						if(n==5)
							var tabella="getTabella4";
						xmlhttp.open("POST", tabella+".php?", true);
						xmlhttp.send();
					}
				}
				else
				{
					if(n==0)
					{
						getGrafico0("imageContainerHD");
					}
					if(n==1)
					{
						getGrafico1("imageContainerHD");
					}
					if(n==2)
					{
						getGrafico2("imageContainerHD");
					}
					if(n==3)
					{
						getGrafico3("imageContainerHD");
					}
					setTimeout(function()
					{
						html2canvas(document.getElementById("imageContainerHD")).then(function(canvas) 
						{
							document.getElementById("imageContainer").appendChild(canvas);
							var img    = canvas.toDataURL("image/png");
							printJS(img, 'image')
						});
					},2500);
				}
			}
			function scaricaExcel(tabella)
			{
				var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
				tableToExcel(tabella);
			}
			function scaricaImmagine(n)
			{
				var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
				if(n==0)
				{
					getGrafico0("imageContainerHD");
				}
				if(n==1)
				{
					getGrafico1("imageContainerHD");
				}
				if(n==2)
				{
					getGrafico2("imageContainerHD");
				}
				if(n==3)
				{
					getGrafico3("imageContainerHD");
				}
				setTimeout(function()
				{
					html2canvas(document.getElementById("imageContainerHD")).then(function(canvas) 
					{
						document.getElementById("imageContainer").appendChild(canvas);
						var img    = canvas.toDataURL("image/png");
						document.getElementById("imageContainer2").setAttribute('href', img);
						document.getElementById("imageContainer2").click();
					});
				},2500);
			}
			$("html").click(function(e) 
			{
				if($(e.target).is('.contextMenuRiepilogoPresenzeDitte'))
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
				var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].style.display='none';
				}
			});
			function apriPopupPonti(valore)
			{
				document.getElementById("modalPontiRiepilogoPresenzeDitteContainer").style.display="table";
				//document.getElementById("btnConfermaPopupPonti").removeEventListener("onclick");
				document.getElementById("btnConfermaPopupPonti").setAttribute("onclick","getGrafico"+valore+"('chartContainer"+valore+"')");
			}
			function chiudiPopupPonti()
			{
				/*document.getElementById("checkBoxPonteTutti").checked=true;
				var all=document.getElementsByClassName("checkBoxPonte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].checked=true;
				}*/
				document.getElementById("modalPontiRiepilogoPresenzeDitteContainer").style.display="none";
			}
			function checkTuttiPonti()
			{
				if(document.getElementById("checkBoxPonteTutti").checked==true)
					var checked=true;
				else
					var checked=false;
				var all=document.getElementsByClassName("checkBoxPonte");
				for (var i = 0; i < all.length; i++) 
				{
					all[i].checked=checked;
				}
			}
			function controllaCheckTuttiPonti()
			{
				var checked=true;
				var all=document.getElementsByClassName("checkBoxPonte");
				for (var i = 0; i < all.length; i++) 
				{
					if(all[i].checked==false)
					{
						checked=false;
						break;
					}
				}
				document.getElementById("checkBoxPonteTutti").checked=checked;
			}
		</script>
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


