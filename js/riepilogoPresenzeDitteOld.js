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