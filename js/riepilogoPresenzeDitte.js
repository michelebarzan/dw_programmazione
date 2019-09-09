var chartArray=[];
var fullscreen=false;
var containers=["","","","","","","",""];
var grafico0;
var tabella0;
var grafico1;
var tabella1;
var grafico2;
var tabella2;
var grafico3;
var grafico6;
var grafico7;
var filters=['anni','mesi','ponti','ditte'];
var nGrafici=[0,1,2,3,6,7];
var activeFilters=[];
nGrafici.forEach(function(globalI)
{
    activeFilters[globalI]={};
    filters.forEach(function(filter)
    {
        activeFilters[globalI][filter]=[];
    });
});
/*var nGrafici=5;
var activeFilters=[];
for (var globalI = 0; globalI < nGrafici; globalI++) 
{
    activeFilters[globalI]={};
    filters.forEach(function(filter)
    {
        activeFilters[globalI][filter]=[];
    });
}*/

function newCircleSpinner(message)
{
    if(document.getElementById("containerCircleSpinner")!=null)
    {
        removeCircleSpinner();
    }
    var container=document.createElement("div");
    container.setAttribute("id","containerCircleSpinner");
    
    var middle=document.createElement("div");
    middle.setAttribute("id","middleCircleSpinner");
    
    var inner=document.createElement("div");
    inner.setAttribute("id","innerCircleSpinner");
    
    var spinWrapper=document.createElement("div");
    spinWrapper.setAttribute("class","cirlceSpinner-spin-wrapper");
    
    var spinner=document.createElement("div");
    spinner.setAttribute("class","circleSpinner-spinner");
    
    var spinnerLabel=document.createElement("div");
    spinnerLabel.setAttribute("class","circleSpinner-spinnerLabel");
    spinnerLabel.innerHTML=message;
    
    spinWrapper.appendChild(spinner);
    spinWrapper.appendChild(spinnerLabel);
    
    inner.appendChild(spinWrapper);
    
    middle.appendChild(inner);
    
    container.appendChild(middle);
    
    document.body.appendChild(container);
	
	//document.body.innerHTML+='<div id="containerCircleSpinner"><div id="middle"><div id="inner"><div class="spin-wrapper"><div class="spinner"></div><div class="spinnerLabel">'+message+'</div></div></div></div></div>';
}
function removeCircleSpinner()
{
    if(document.getElementById("containerCircleSpinner")!=null)
        document.getElementById("containerCircleSpinner").remove();
}
function getDatas0(view)
{
    switch(view)
    {
        case "grafico_operatori":grafico0=1;getGrafico0('chartContainer0');break;
        case "grafico_ore":grafico0=2;getGrafico0('chartContainer0');break;
        case "tabella_operatori":tabella0=1;getTabella0();break;
        case "tabella_ore":tabella0=2;getTabella0();break;
    }
}
function getDatas1(view)
{
    switch(view)
    {
        case "grafico_operatori":grafico1=2;getGrafico1('chartContainer1');break;
        case "grafico_ore":grafico1=1;getGrafico1('chartContainer1');break;
        case "tabella":getTabella1();break;
    }
}
function getDatas2(view)
{
    switch(view)
    {
        case "grafico_operatori":grafico2=1;getGrafico2('chartContainer2');break;
        case "grafico_ore":grafico2=2;getGrafico2('chartContainer2');break;
        case "tabella_operatori":tabella2=1;getTabella2();break;
        case "tabella_ore":tabella2=2;getTabella2();break;
    }
}
function getDatas3(view)
{
    switch(view)
    {
        case "media_mesi":grafico3=1;getGrafico3('chartContainer3');break;
        case "media_ditte":grafico3=2;getGrafico3('chartContainer3');break;
        case "tabella":getTabella3();break;
    }
}
function getDatas6(view)
{
    switch(view)
    {
        case "grafico_operatori":grafico6=1;getGrafico6('chartContainer6');break;
        case "grafico_ore":grafico6=2;getGrafico6('chartContainer6');break;
    }
}
function getDatas7(view)
{
    switch(view)
    {
        case "grafico_operatori":grafico7=1;getGrafico7('chartContainer7');break;
        case "grafico_ore":grafico7=2;getGrafico7('chartContainer7');break;
    }
}
async function getGrafico7(container)
{
    containers[7]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");

    document.getElementById("buttonFilterRiepilogoPresenzeDitte7").style.display="block";

    if(activeFilters[7].anni.length==0 && activeFilters[7].ditte.length==0 && activeFilters[7].ponti.length==0)
    {
        activeFilters[7].anni=getAllAnni();
        activeFilters[7].ditte=await getAllDitte();
        activeFilters[7].ponti=await getAllPonti();
    }
    var JSONanni=JSON.stringify(activeFilters[7].anni);
    var JSONditte=JSON.stringify(activeFilters[7].ditte);
    var JSONponti=JSON.stringify(activeFilters[7].ponti);

    if(grafico7==1)
    {
        $.post("getGrafico7tipo1.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                //console.log(response);
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var data = JSON.parse(response);
                    //console.log(data);
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[7] = new CanvasJS.Chart("chartContainer7", {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Media operatori ditte"
                        },
                        axisY: {
                            title: "N. operatori"
                        },
                        axisX: {
                            title: "Mesi"
                        },
                        toolTip: {
                            shared: false
                        },
                        legend: {
                            cursor: "pointer",
                            horizontalAlign: "center",
                            dockInsidePlotArea: false,
                            itemclick: toggleDataSeries7
                        },
                        data:data
                    });
                    function toggleDataSeries7(e)
                    {
                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                            e.dataSeries.visible = false;
                        } else{
                            e.dataSeries.visible = true;
                        }
                        chartArray[7].render();
                    }
                    chartArray[7].render();
                }
            }
            else
                console.log(status);
        });
    }
    else
    {
        $.post("getGrafico7tipo2.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var data = JSON.parse(response);
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[7] = new CanvasJS.Chart("chartContainer7", {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Media ore ditte"
                        },
                        axisY: {
                            title: "Ore"
                        },
                        axisX: {
                            title: "Mesi"
                        },
                        toolTip: {
                            shared: false
                        },
                        legend: {
                            cursor: "pointer",
                            horizontalAlign: "center",
                            dockInsidePlotArea: false,
                            itemclick: toggleDataSeries7
                        },
                        data:data
                    });
                    function toggleDataSeries7(e)
                    {
                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                            e.dataSeries.visible = false;
                        } else{
                            e.dataSeries.visible = true;
                        }
                        chartArray[7].render();
                    }
                    chartArray[7].render();
                }
            }
            else
                console.log(status);
        });
    }
}
async function getGrafico6(container)
{
    containers[6]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");

    document.getElementById("buttonFilterRiepilogoPresenzeDitte6").style.display="block";

    if(activeFilters[6].anni.length==0 && activeFilters[6].ditte.length==0 && activeFilters[6].ponti.length==0)
    {
        activeFilters[6].anni=getAllAnni();
        activeFilters[6].ditte=await getAllDitte();
        activeFilters[6].ponti=await getAllPonti();
    }
    var JSONanni=JSON.stringify(activeFilters[6].anni);
    var JSONditte=JSON.stringify(activeFilters[6].ditte);
    var JSONponti=JSON.stringify(activeFilters[6].ponti);

    if(grafico6==1)
    {
        $.post("getGrafico6tipo1.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var data = JSON.parse(response);
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[6] = new CanvasJS.Chart("chartContainer6", {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Capacità ditte (N. operatori)"
                        },
                        axisY: {
                            title: "N. operatori"
                        },
                        axisX: {
                            title: "Mesi"
                        },
                        toolTip: {
                            shared: false
                        },
                        legend: {
                            cursor: "pointer",
                            horizontalAlign: "center",
                            dockInsidePlotArea: false,
                            itemclick: toggleDataSeries6
                        },
                        data:data
                    });
                    function toggleDataSeries6(e)
                    {
                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                            e.dataSeries.visible = false;
                        } else{
                            e.dataSeries.visible = true;
                        }
                        chartArray[6].render();
                    }
                    chartArray[6].render();
                }
            }
            else
                console.log(status);
        });
    }
    else
    {
        $.post("getGrafico6tipo2.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var data = JSON.parse(response);
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[6] = new CanvasJS.Chart("chartContainer6", {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Capacità ditte (ore)"
                        },
                        axisY: {
                            title: "Ore"
                        },
                        axisX: {
                            title: "Mesi"
                        },
                        toolTip: {
                            shared: false
                        },
                        legend: {
                            cursor: "pointer",
                            horizontalAlign: "center",
                            dockInsidePlotArea: false,
                            itemclick: toggleDataSeries6
                        },
                        data:data
                    });
                    function toggleDataSeries6(e)
                    {
                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                            e.dataSeries.visible = false;
                        } else{
                            e.dataSeries.visible = true;
                        }
                        chartArray[6].render();
                    }
                    chartArray[6].render();
                }
            }
            else
                console.log(status);
        });
    }
}
async function getGrafico0(container)
{
    containers[0]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");

    document.getElementById("buttonFilterRiepilogoPresenzeDitte0").style.display="block";

    if(activeFilters[0].anni.length==0 && activeFilters[0].mesi.length==0 && activeFilters[0].ponti.length==0)
    {
        activeFilters[0].anni=getAllAnni();
        activeFilters[0].mesi=getAllMesi();
        activeFilters[0].ponti=await getAllPonti();
    }
    var JSONanni=JSON.stringify(activeFilters[0].anni);
    var JSONmesi=JSON.stringify(activeFilters[0].mesi);
    var JSONponti=JSON.stringify(activeFilters[0].ponti);

    if(grafico0==1)
    {
        var dataPoints=[];
        $.post("getGrafico0tipo1.php",
        {
            JSONanni,
            JSONmesi,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[0]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale giorni lavorati dalle ditte"
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
                    chartArray[0].render();
                }
            }
            else
                console.log(status);
        });
    }
    else
    {
        var dataPoints=[];
        $.post("getGrafico0tipo2.php",
        {
            JSONanni,
            JSONmesi,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[0]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale ore lavorate dalle ditte"
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
                    chartArray[0].render();
                }
            }
            else
                console.log(status);
        });
    }
    //chiudiPopupPonti();
}
function getTabella0()
{
    containers[0]="tabella";
    document.getElementById('chartContainer0').innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    document.getElementById("buttonFilterRiepilogoPresenzeDitte0").style.display="none";

    if(tabella0==1)
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                removeCircleSpinner();
                if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                    window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
                else
                {
                    document.getElementById('chartContainer0').innerHTML=this.responseText;
                    /*new SimpleBar(document.getElementById('chartContainer0'),{timeout: 10000000});
                    document.getElementById('chartContainer0').style.height="280px";
                    document.getElementById('chartContainer0').style.width="90%";*/
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
                removeCircleSpinner();
                if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                    window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
                else
                {
                    document.getElementById('chartContainer0').innerHTML=this.responseText;
                    /*new SimpleBar(document.getElementById('chartContainer0'),{timeout: 10000000});
                    document.getElementById('chartContainer0').style.height="280px";
                    document.getElementById('chartContainer0').style.width="90%";*/
                }
            }
        };
        xmlhttp.open("POST", "getTabella0Tipo2.php?", true);
        xmlhttp.send();
    }
}
async function getGrafico1(container)
{
    containers[1]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");

    document.getElementById("buttonFilterRiepilogoPresenzeDitte1").style.display="block";

    if(activeFilters[1].anni.length==0 && activeFilters[1].ditte.length==0 && activeFilters[1].ponti.length==0)
    {
        activeFilters[1].anni=getAllAnni();
        activeFilters[1].ditte=await getAllDitte();
        activeFilters[1].ponti=await getAllPonti();
    }
    var JSONanni=JSON.stringify(activeFilters[1].anni);
    var JSONditte=JSON.stringify(activeFilters[1].ditte);
    var JSONponti=JSON.stringify(activeFilters[1].ponti);

    if(grafico1==1)
    {
        var dataPoints=[];
        $.post("getGrafico1tipo1.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[1]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale ore lavorate"
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
                    chartArray[1].render();
                }
            }
            else
                console.log(status);
        });
    }
    else
    {
        var dataPoints=[];
        $.post("getGrafico1tipo2.php",
        {
            JSONanni,
            JSONditte,
            JSONponti
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[1]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale giorni lavorati"
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
                    chartArray[1].render();
                }
            }
            else
                console.log(status);
        });
    }
    //chiudiPopupPonti();
}
function getTabella1()
{
    containers[1]="tabella";
    document.getElementById('chartContainer1').innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    document.getElementById("buttonFilterRiepilogoPresenzeDitte1").style.display="none";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            removeCircleSpinner();
            if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
            else
            {
                document.getElementById('chartContainer1').innerHTML=this.responseText;
                /*new SimpleBar(document.getElementById('chartContainer1'),{timeout: 10000000});
                document.getElementById('chartContainer1').style.height="280px";
                document.getElementById('chartContainer1').style.width="90%";*/
            }
        }
    };
    xmlhttp.open("POST", "getTabella1.php?", true);
    xmlhttp.send();
}
async function getGrafico2(container)
{
    containers[2]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    
    document.getElementById("buttonFilterRiepilogoPresenzeDitte2").style.display="block";

    if(activeFilters[2].anni.length==0 && activeFilters[2].ditte.length==0 && activeFilters[2].ponti.length==0 && activeFilters[2].mesi.length==0)
    {
        activeFilters[2].anni=getAllAnni();
        activeFilters[2].ditte=await getAllDitte();
        activeFilters[2].ponti=await getAllPonti();
        activeFilters[2].mesi=await getAllMesi();
    }
    var JSONanni=JSON.stringify(activeFilters[2].anni);
    var JSONditte=JSON.stringify(activeFilters[2].ditte);
    var JSONponti=JSON.stringify(activeFilters[2].ponti);
    var JSONmesi=JSON.stringify(activeFilters[2].mesi);

    document.getElementById('checkboxLabel2').style.display="none";
    if(grafico2==1)
    {
        var dataPoints=[];
        $.post("getGrafico2tipo1.php",
        {
            JSONanni,
            JSONditte,
            JSONponti,
            JSONmesi
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[2]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale operatori"
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
                    chartArray[2].render();
                }
            }
            else
                console.log(status);
        });
    }
    if(grafico2==2)
    {
        var dataPoints=[];
        $.post("getGrafico2tipo2.php",
        {
            JSONanni,
            JSONditte,
            JSONponti,
            JSONmesi
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[2]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Totale ore"
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
                    chartArray[2].render();
                }
            }
            else
                console.log(status);
        });
    }
    //chiudiPopupPonti();
}
function getTabella2()
{
    containers[2]="tabella";
    document.getElementById('chartContainer2').innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    document.getElementById("buttonFilterRiepilogoPresenzeDitte2").style.display="none";
    if(tabella2==1)
    {
        document.getElementById('checkboxLabel2').style.display="inline-block";
        var nomi=document.getElementById('checkbox2').checked;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                removeCircleSpinner();
                if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                    window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
                else
                {
                    document.getElementById('chartContainer2').innerHTML=this.responseText;
                    /*new SimpleBar(document.getElementById('chartContainer2'),{timeout: 10000000});
                    document.getElementById('chartContainer2').style.height="280px";
                    document.getElementById('chartContainer2').style.width="90%";*/
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
                removeCircleSpinner();
                if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                    window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
                else
                {
                    document.getElementById('chartContainer2').innerHTML=this.responseText;
                    /*new SimpleBar(document.getElementById('chartContainer2'),{timeout: 10000000});
                    document.getElementById('chartContainer2').style.height="280px";
                    document.getElementById('chartContainer2').style.width="90%";*/
                }
            }
        };
        xmlhttp.open("POST", "getTabella2tipo2.php?", true);
        xmlhttp.send();
    }
}
async function getGrafico3(container)
{
    containers[3]="grafico";
    document.getElementById(container).innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    document.getElementById("buttonFilterRiepilogoPresenzeDitte3").style.display="block";

    if(activeFilters[3].anni.length==0 && activeFilters[3].ponti.length==0)
    {
        activeFilters[3].anni=getAllAnni();
        activeFilters[3].ponti=await getAllPonti();
    }
    var JSONanni=JSON.stringify(activeFilters[3].anni);
    var JSONponti=JSON.stringify(activeFilters[3].ponti);
    if(grafico3==1)
    {
        if(activeFilters[3].ditte.length==0)
        {
            activeFilters[3].ditte=await getAllDitte();
        }
        var JSONditte=JSON.stringify(activeFilters[3].ditte);
        var dataPoints=[];
        $.post("getGrafico3tipo1.php",
        {
            JSONanni,
            JSONditte,
            JSONponti,
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[3]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Media operatori per mese"
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
                    chartArray[3].render();
                }
            }
            else
                console.log(status);
        });
    }
    else
    {
        if(activeFilters[3].mesi.length==0)
        {
            activeFilters[3].mesi=getAllMesi();
        }
        var JSONmesi=JSON.stringify(activeFilters[3].mesi);
        var dataPoints=[];
        $.post("getGrafico3tipo2.php",
        {
            JSONanni,
            JSONmesi,
            JSONponti,
        },
        function(response, status)
        {
            if(status=="success")
            {
                removeCircleSpinner();
                if(response.indexOf("error")>-1 || response.indexOf("notice")>-1 || response.indexOf("warning")>-1)
                {
                    Swal.fire
                    ({
                        type: 'error',
                        title: 'Errore',
                        text: "Se il problema persiste contatta l' amministratore"
                    });
                    console.log(response);
                }
                else
                {
                    var res1=response.split("%");
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
                    if(container=="imageContainerHD")
                        var fontSize=30;
                    else
                        var fontSize=15;
                    chartArray[3]= new CanvasJS.Chart(container, 
                    {
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            fontSize: fontSize,
                            fontWeight:'normal',
                            color:'gray',
                            text: "Media operatori per ditta"
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
                    chartArray[3].render();
                }
            }
            else
                console.log(status);
        });
    }
    //chiudiPopupPonti();
}
function getTabella3()
{
    containers[3]="tabella";
    document.getElementById('chartContainer3').innerHTML="";
    newCircleSpinner("Caricamento in corso...");
    document.getElementById("buttonFilterRiepilogoPresenzeDitte3").style.display="none";
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            removeCircleSpinner();
            if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                window.alert("Errore. Impossibile visualizzare la tabella. Se il problema persiste contattare l' amministratore.");
            else
            {
                document.getElementById('chartContainer3').innerHTML=this.responseText;
                /*new SimpleBar(document.getElementById('chartContainer3'),{timeout: 10000000});
                document.getElementById('chartContainer3').style.height="280px";
                document.getElementById('chartContainer3').style.width="90%";*/
            }
        }
    };
    xmlhttp.open("POST", "getTabella3tipo1.php?", true);
    xmlhttp.send();
}
function getTabella4()
{
    newCircleSpinner("Caricamento in corso...");
    containers[5]="tabella";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            removeCircleSpinner();
            document.getElementById('chartContainer5').innerHTML=this.responseText;
        }
    };
    xmlhttp.open("POST", "getTabella4.php?", true);
    xmlhttp.send();
}
function getTabellaErrori()
{
    newCircleSpinner("Caricamento in corso...");
    containers[4]="tabella";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            removeCircleSpinner();
            document.getElementById('chartContainer4').innerHTML=this.responseText;
        }
    };
    xmlhttp.open("POST", "getTabellaErroriPresenzeDitte.php?", true);
    xmlhttp.send();
}
function openContextMenu(event,n)
{
    var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
    for (var i = 0; i < all.length; i++) 
    {
        all[i].style.display='none';
    }
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
function scaricaImmagine(n,row)
{
    var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
    for (var i = 0; i < all.length; i++) 
    {
        all[i].style.display='none';
    }
    if(!fullscreen)
        toggleFullscreen=true;
    else
        toggleFullscreen=false;
    newCircleSpinner("Caricamento in corso...")
    var rowButton=document.getElementById("rowButtonRiepilogoPresenzeDitte"+n);
    if(toggleFullscreen)
        toggleFullscreenChart(n,row,rowButton);
    //chartArray[n].axisX.labelAngle=45;
    //chartArray[n].options.axisX.labelFontSize=12;
    //chartArray[n].render();
    setTimeout(function()
    {
        html2canvas(document.getElementById("containerRiepilogoPresenzeDitteColumn"+n)).then(function(canvas) 
        {
            document.getElementById("imageContainer").appendChild(canvas);
            var img    = canvas.toDataURL("image/png");
            document.getElementById("imageContainer2").setAttribute('href', img);
            //getImageFromUrl(img, createPDF);
            document.getElementById("imageContainer2").click();
        });
    },1000);
    setTimeout(function()
    {
        if(toggleFullscreen)
            toggleFullscreenChart(n,row,rowButton);
        removeCircleSpinner();
    },2000);
}
/*var getImageFromUrl = function(url, callback) {
    var img = new Image();

    img.onError = function() {
        alert('Cannot load image: "'+url+'"');
    };
    img.onload = function() {
        callback(img);
    };
    img.src = url;
}


var createPDF = function(imgData) {
    var doc = new jsPDF();



    doc.addImage(imgData, 'JPEG', 10, 10, 50, 50, 'monkey');
    doc.addImage('monkey', 70, 10, 100, 120); // use the cached 'monkey' image, JPEG is optional regardless



    doc.output('datauri');
}*/
async function getFiltri(n)
{
    var filtriOuterContainer=document.createElement("div");
    filtriOuterContainer.setAttribute("class","multipleFilterFiltriOuterContainerRiepilogPresenzeDitte");
    switch(n)
    {
        case 0:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            filtriOuterContainer.appendChild(getFiltroMesi(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            break;
        case 1:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            filtriOuterContainer.appendChild(await getFiltroDitte(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            break;
        case 2:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            filtriOuterContainer.appendChild(await getFiltroDitte(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            filtriOuterContainer.appendChild(getFiltroMesi(n));
            break;
        case 3:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            if(grafico3==1)
                filtriOuterContainer.appendChild(await getFiltroDitte(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            if(grafico3==2)
                filtriOuterContainer.appendChild(getFiltroMesi(n));
            ;break;
        case 6:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            filtriOuterContainer.appendChild(await getFiltroDitte(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            break;
        case 7:
            filtriOuterContainer.appendChild(getFiltroAnni(n));
            filtriOuterContainer.appendChild(await getFiltroDitte(n));
            filtriOuterContainer.appendChild(await getFiltroPonti(n));
            break;
    }
    Swal.fire
    ({
        html:filtriOuterContainer.outerHTML,
        showCancelButton:true,
        width: 'auto',
        cancelButtonText: "Annulla",
        confirmButtonText : "Filtra"
    }).then((result) => 
    {
        if (result.value)
        {
            switch(n)
            {
                case 0:
                    getFilter("anni",n);
                    getFilter("mesi",n);
                    getFilter("ponti",n);
                    getDatas0(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte0").value);
                    break;
                case 1:
                    getFilter("anni",n);
                    getFilter("ditte",n);
                    getFilter("ponti",n);
                    getDatas1(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte1").value);
                    break;
                case 2:
                    getFilter("anni",n);
                    getFilter("ditte",n);
                    getFilter("ponti",n);
                    getFilter("mesi",n);
                    getDatas2(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte2").value);
                    break;
                case 3:
                    getFilter("anni",n);
                    getFilter("ditte",n);
                    getFilter("ponti",n);
                    getFilter("mesi",n);
                    getDatas3(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte3").value);
                    break;
                case 6:
                    getFilter("anni",n);
                    getFilter("ditte",n);
                    getFilter("mesi",n);
                    getDatas6(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte6").value);
                    break;
                case 7:
                    getFilter("anni",n);
                    getFilter("ditte",n);
                    getFilter("mesi",n);
                    getDatas7(document.getElementById("visualizzazioneOrigineDatiSelectRiepilogoPresenzeDitte7").value);
                    break;
            }
            if(fullscreen)
                getListActiveFilters(n);
        }
    })
}
function getFiltroAnni(n)
{
    //Anni----------------------------------------------------------------------------------------------------

    var anni=[2017,2018,2019,2020,2021,2022];
    var anniLabel=[2017,2018,2019,2020,2021,2022];
    
    if(activeFilters[n].anni.length==0)
    {
        var anniChecked=anni;
    }
    else
    {
        var anniChecked=activeFilters[n].anni;
    }

    var filtroMultiploOuterContainer=document.createElement("div");
    filtroMultiploOuterContainer.setAttribute("class","multipleFilterOuterContainerRiepilogPresenzeDitte");

    var filtroMultiploOuterContainerTitle=document.createElement("div");
    filtroMultiploOuterContainerTitle.setAttribute("class","multipleFilterOuterContainerTitleRiepilogPresenzeDitte");
    filtroMultiploOuterContainerTitle.innerHTML="Anno";

    filtroMultiploOuterContainer.appendChild(filtroMultiploOuterContainerTitle);

    var filtroMultiploContainer=document.createElement("div");
    filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
    var filtroMultiploLabel=document.createElement("label");
    filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
    filtroMultiploLabel.innerHTML="Tutti";
    var filtroMultiploInput=document.createElement("input");
    filtroMultiploInput.setAttribute("type","checkbox");
    if(anniChecked.length==anni.length)
        filtroMultiploInput.setAttribute("checked","checked");
    filtroMultiploInput.setAttribute("id","filter-value-anni-tutti");
    filtroMultiploInput.setAttribute("onchange","checkAllFilter(this.checked,'anni')");
    filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte");
    var filtroMultiploSpan=document.createElement("span");
    filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

    filtroMultiploLabel.appendChild(filtroMultiploInput);
    filtroMultiploLabel.appendChild(filtroMultiploSpan);

    filtroMultiploContainer.appendChild(filtroMultiploLabel);

    filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);

    for(var i=0;i<anni.length;i++)
    {
        var anno=anni[i];
        var annoLabel=anniLabel[i];
        var filtroMultiploContainer=document.createElement("div");
        filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
        var filtroMultiploLabel=document.createElement("label");
        filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
        filtroMultiploLabel.innerHTML=annoLabel;
        var filtroMultiploInput=document.createElement("input");
        filtroMultiploInput.setAttribute("type","checkbox");
        filtroMultiploInput.setAttribute("filter-value",anno);
        if(anniChecked.length==anni.length)
            filtroMultiploInput.setAttribute("checked","checked");
        else
        {
            
            if(anniChecked.includes(anno.toString()))
            {
                filtroMultiploInput.setAttribute("checked","checked");
            }
                
        }
        filtroMultiploInput.setAttribute("onchange","uncheckAllFilter(this.checked,'anni')");
        filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte filter-value-anni");
        var filtroMultiploSpan=document.createElement("span");
        filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

        filtroMultiploLabel.appendChild(filtroMultiploInput);
        filtroMultiploLabel.appendChild(filtroMultiploSpan);

        filtroMultiploContainer.appendChild(filtroMultiploLabel);

        filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);
    }

    return filtroMultiploOuterContainer;

    //------------------------------------------------------------------------------------------------
}
function getFiltroMesi(n)
{
    //Mesi----------------------------------------------------------------------------------------------------
    
    var mesi=[01,02,03,04,05,06,07,08,09,10,11,12];
    var mesiLabel=["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"];

    if(activeFilters[n].mesi.length==0)
    {
        var mesiChecked=mesi;
    }
    else
    {
        var mesiChecked=activeFilters[n].mesi;
    }

    var filtroMultiploOuterContainer=document.createElement("div");
    filtroMultiploOuterContainer.setAttribute("class","multipleFilterOuterContainerRiepilogPresenzeDitte");

    var filtroMultiploOuterContainerTitle=document.createElement("div");
    filtroMultiploOuterContainerTitle.setAttribute("class","multipleFilterOuterContainerTitleRiepilogPresenzeDitte");
    filtroMultiploOuterContainerTitle.innerHTML="Mese";

    filtroMultiploOuterContainer.appendChild(filtroMultiploOuterContainerTitle);

    var filtroMultiploContainer=document.createElement("div");
    filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
    var filtroMultiploLabel=document.createElement("label");
    filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
    filtroMultiploLabel.innerHTML="Tutti";
    var filtroMultiploInput=document.createElement("input");
    filtroMultiploInput.setAttribute("type","checkbox");
    filtroMultiploInput.setAttribute("id","filter-value-mesi-tutti");
    filtroMultiploInput.setAttribute("onchange","checkAllFilter(this.checked,'mesi')");
    if(mesiChecked.length==mesi.length)
        filtroMultiploInput.setAttribute("checked","checked");
    filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte");
    var filtroMultiploSpan=document.createElement("span");
    filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

    filtroMultiploLabel.appendChild(filtroMultiploInput);
    filtroMultiploLabel.appendChild(filtroMultiploSpan);

    filtroMultiploContainer.appendChild(filtroMultiploLabel);

    filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);

    for(var i=0;i<mesi.length;i++)
    {
        var mese=mesi[i];
        var meseLabel=mesiLabel[i];
        var filtroMultiploContainer=document.createElement("div");
        filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
        var filtroMultiploLabel=document.createElement("label");
        filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
        filtroMultiploLabel.innerHTML=meseLabel;
        var filtroMultiploInput=document.createElement("input");
        filtroMultiploInput.setAttribute("type","checkbox");
        filtroMultiploInput.setAttribute("filter-value",mese);
        if(mesiChecked.length==mesi.length)
            filtroMultiploInput.setAttribute("checked","checked");
        else
        {
            
            if(mesiChecked.includes(mese.toString()))
            {
                filtroMultiploInput.setAttribute("checked","checked");
            }
                
        }
        filtroMultiploInput.setAttribute("onchange","uncheckAllFilter(this.checked,'mesi')");
        filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte filter-value-mesi");
        var filtroMultiploSpan=document.createElement("span");
        filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

        filtroMultiploLabel.appendChild(filtroMultiploInput);
        filtroMultiploLabel.appendChild(filtroMultiploSpan);

        filtroMultiploContainer.appendChild(filtroMultiploLabel);

        filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);
    }

    return filtroMultiploOuterContainer;

    //------------------------------------------------------------------------------------------------
}
function getFiltroPonti(n)
{
    return new Promise(function (resolve, reject) 
    {
        $.get("getPontiRiepilogoPresenzeDitte.php",
        function(response, status)
        {
            if(status=="success")
            {
                var ponti=[];
                var pontiObj = JSON.parse(response);
                for (var key in pontiObj)
                {
                    ponti.push(pontiObj[key]);							
                }
                var pontiLabel=ponti;

                if(activeFilters[n].ponti.length==0)
                {
                    var pontiChecked=ponti;
                }
                else
                {
                    var pontiChecked=activeFilters[n].ponti;
                }
                
                //Ponti----------------------------------------------------------------------------------------------------
    
                var filtroMultiploOuterContainer=document.createElement("div");
                filtroMultiploOuterContainer.setAttribute("class","multipleFilterOuterContainerRiepilogPresenzeDitte");

                var filtroMultiploOuterContainerTitle=document.createElement("div");
                filtroMultiploOuterContainerTitle.setAttribute("class","multipleFilterOuterContainerTitleRiepilogPresenzeDitte");
                filtroMultiploOuterContainerTitle.innerHTML="Ponte";

                filtroMultiploOuterContainer.appendChild(filtroMultiploOuterContainerTitle);

                var filtroMultiploContainer=document.createElement("div");
                filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
                var filtroMultiploLabel=document.createElement("label");
                filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
                filtroMultiploLabel.innerHTML="Tutti";
                var filtroMultiploInput=document.createElement("input");
                filtroMultiploInput.setAttribute("type","checkbox");
                filtroMultiploInput.setAttribute("id","filter-value-ponti-tutti");
                filtroMultiploInput.setAttribute("onchange","checkAllFilter(this.checked,'ponti')");
                if(pontiChecked.length==ponti.length)
                    filtroMultiploInput.setAttribute("checked","checked");
                filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte");
                var filtroMultiploSpan=document.createElement("span");
                filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

                filtroMultiploLabel.appendChild(filtroMultiploInput);
                filtroMultiploLabel.appendChild(filtroMultiploSpan);

                filtroMultiploContainer.appendChild(filtroMultiploLabel);

                filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);

                for(var i=0;i<ponti.length;i++)
                {
                    var ponte=ponti[i];
                    var ponteLabel=pontiLabel[i];
                    var filtroMultiploContainer=document.createElement("div");
                    filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
                    var filtroMultiploLabel=document.createElement("label");
                    filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
                    filtroMultiploLabel.innerHTML=ponteLabel;
                    var filtroMultiploInput=document.createElement("input");
                    filtroMultiploInput.setAttribute("type","checkbox");
                    filtroMultiploInput.setAttribute("filter-value",ponte);
                    if(pontiChecked.length==ponti.length)
                        filtroMultiploInput.setAttribute("checked","checked");
                    else
                    {
                        
                        if(pontiChecked.includes(ponte.toString()))
                        {
                            filtroMultiploInput.setAttribute("checked","checked");
                        }
                            
                    }
                    filtroMultiploInput.setAttribute("onchange","uncheckAllFilter(this.checked,'ponti')");
                    filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte filter-value-ponti");
                    var filtroMultiploSpan=document.createElement("span");
                    filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

                    filtroMultiploLabel.appendChild(filtroMultiploInput);
                    filtroMultiploLabel.appendChild(filtroMultiploSpan);

                    filtroMultiploContainer.appendChild(filtroMultiploLabel);

                    filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);
                }

                resolve(filtroMultiploOuterContainer);
            }
            else
                reject({status});
        });
    });
}
function getFiltroDitte(n)
{
    return new Promise(function (resolve, reject) 
    {
        $.get("getDitteRiepilogoPresenzeDitte.php",
        function(response, status)
        {
            if(status=="success")
            {
                var ditte=[];
                var ditteObj = JSON.parse(response);
                for (var key in ditteObj)
                {
                    ditte.push(ditteObj[key]);							
                }
                var ditteLabel=ditte;

                if(activeFilters[n].ditte.length==0)
                {
                    var ditteChecked=ditte;
                }
                else
                {
                    var ditteChecked=activeFilters[n].ditte;
                }
                
                //ditte----------------------------------------------------------------------------------------------------
    
                var filtroMultiploOuterContainer=document.createElement("div");
                filtroMultiploOuterContainer.setAttribute("class","multipleFilterOuterContainerRiepilogPresenzeDitte");

                var filtroMultiploOuterContainerTitle=document.createElement("div");
                filtroMultiploOuterContainerTitle.setAttribute("class","multipleFilterOuterContainerTitleRiepilogPresenzeDitte");
                filtroMultiploOuterContainerTitle.innerHTML="Ditta";

                filtroMultiploOuterContainer.appendChild(filtroMultiploOuterContainerTitle);

                var filtroMultiploContainer=document.createElement("div");
                filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
                var filtroMultiploLabel=document.createElement("label");
                filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
                filtroMultiploLabel.innerHTML="Tutti";
                var filtroMultiploInput=document.createElement("input");
                filtroMultiploInput.setAttribute("type","checkbox");
                filtroMultiploInput.setAttribute("id","filter-value-ditte-tutti");
                filtroMultiploInput.setAttribute("onchange","checkAllFilter(this.checked,'ditte')");
                if(ditteChecked.length==ditte.length)
                    filtroMultiploInput.setAttribute("checked","checked");
                filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte");
                var filtroMultiploSpan=document.createElement("span");
                filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

                filtroMultiploLabel.appendChild(filtroMultiploInput);
                filtroMultiploLabel.appendChild(filtroMultiploSpan);

                filtroMultiploContainer.appendChild(filtroMultiploLabel);

                filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);

                for(var i=0;i<ditte.length;i++)
                {
                    var ditta=encodeURIComponent(ditte[i]);
                    var dittaLabel=ditteLabel[i];
                    var filtroMultiploContainer=document.createElement("div");
                    filtroMultiploContainer.setAttribute("class","multipleFilterContainerRiepilogPresenzeDitte");
                    var filtroMultiploLabel=document.createElement("label");
                    filtroMultiploLabel.setAttribute("class","multipleFilterLabelRiepilogPresenzeDitte");
                    filtroMultiploLabel.innerHTML=dittaLabel;
                    var filtroMultiploInput=document.createElement("input");
                    filtroMultiploInput.setAttribute("type","checkbox");
                    filtroMultiploInput.setAttribute("filter-value",ditta);
                    if(ditteChecked.length==ditte.length)
                        filtroMultiploInput.setAttribute("checked","checked");
                    else
                    {
                        
                        if(ditteChecked.includes(ditta.toString()))
                        {
                            filtroMultiploInput.setAttribute("checked","checked");
                        }
                            
                    }
                    filtroMultiploInput.setAttribute("onchange","uncheckAllFilter(this.checked,'ditte')");
                    filtroMultiploInput.setAttribute("class","multipleFilterInputRiepilogPresenzeDitte filter-value-ditte");
                    var filtroMultiploSpan=document.createElement("span");
                    filtroMultiploSpan.setAttribute("class","multipleFilterSpanRiepilogPresenzeDitte");

                    filtroMultiploLabel.appendChild(filtroMultiploInput);
                    filtroMultiploLabel.appendChild(filtroMultiploSpan);

                    filtroMultiploContainer.appendChild(filtroMultiploLabel);

                    filtroMultiploOuterContainer.appendChild(filtroMultiploContainer);
                }

                resolve(filtroMultiploOuterContainer);
            }
            else
                reject({status});
        });
    });
}
function getAllAnni()
{
    return [2017,2018,2019,2020,2021,2022];
}
function getAllMesi()
{
    return [01,02,03,04,05,06,07,08,09,10,11,12];
}
function getAllPonti()
{
    return new Promise(function (resolve, reject) 
    {
        $.get("getPontiRiepilogoPresenzeDitte.php",
        function(response, status)
        {
            if(status=="success")
            {
                var ponti=[];
                var pontiObj = JSON.parse(response);
                for (var key in pontiObj)
                {
                    ponti.push(pontiObj[key]);							
                }
                resolve(ponti);
            }
            else
                reject({status});
        });
    });
}
function getAllDitte()
{
    return new Promise(function (resolve, reject) 
    {
        $.get("getDitteRiepilogoPresenzeDitte.php",
        function(response, status)
        {
            if(status=="success")
            {
                var ditte=[];
                var ditteObj = JSON.parse(response);
                for (var key in ditteObj)
                {
                    ditte.push(encodeURIComponent(ditteObj[key]));							
                }
                resolve(ditte);
            }
            else
                reject({status});
        });
    });
}
function getFilter(filter,n)
{
    var arrayFilter=[];
    var all=document.getElementsByClassName("filter-value-"+filter);
    for (var i = 0; i < all.length; i++) 
    {
        if(all[i].checked)
        arrayFilter.push(all[i].getAttribute("filter-value"));
    }
    var uniqueFiltersValues = [];
    $.each(arrayFilter, function(i, el){
        if($.inArray(el, uniqueFiltersValues) === -1) uniqueFiltersValues.push(el);
    });
    //filtersValues[filter]=uniqueFiltersValues;
    activeFilters[n][filter]=uniqueFiltersValues;
}
function checkAllFilter(checkbox,filter)
{
    var all=document.getElementsByClassName("filter-value-"+filter);
    for (var i = 0; i < all.length; i++) 
    {
        all[i].checked=checkbox;
    }
}
function uncheckAllFilter(checkbox,filter)
{
    if(!checkbox)
        document.getElementById("filter-value-"+filter+"-tutti").checked=checkbox;
    else
    {
        var check=true;
        var all=document.getElementsByClassName("filter-value-"+filter);
        for (var i = 0; i < all.length; i++) 
        {
            if(!all[i].checked)
                check=false;
        }
        if(check)
            document.getElementById("filter-value-"+filter+"-tutti").checked=true;
    }
}
$("html").click(function(e) 
{
    //----------------------------------------------------------------------------------------------
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
function toggleFullscreenChart(n,row,rowButton)
{
    var all = document.getElementsByClassName("contextMenuRiepilogoPresenzeDitte");
    for (var i = 0; i < all.length; i++) 
    {
        all[i].style.display='none';
    }
    if(!fullscreen)
    {
        rowButton.childNodes[1].innerHTML="<i class='fad fa-compress'></i>";
        rowButton.childNodes[3].innerHTML="Riduci";

        for (var i = 0; i < containers.length; i++)
        {
            if(i!=n)
            {
                document.getElementById("containerRiepilogoPresenzeDitteColumn"+i).style.display="none";
            }
        }

        document.getElementById("contextMenuRiepilogoPresenzeDitte"+n).style.right="30";

        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.width="100%";
        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.height="100%";
        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.margin="0px";

        document.getElementById("chartContainer"+n).style.marginLeft="0px";
        document.getElementById("chartContainer"+n).style.marginRight="0px";
        document.getElementById("chartContainer"+n).style.width="99%";
        document.getElementById("chartContainer"+n).style.height="400px";

        //document.getElementById("containerRiepilogoPresenzeDitteRow"+row).style.height="600px";
        document.getElementById("containerRiepilogoPresenzeDitteRow"+row).style.width="100%";

        if(containers[n]=="grafico")
            getListActiveFilters(n);
    }
    else
    {
        rowButton.childNodes[1].innerHTML="<i class='fad fa-expand-wide'></i>";
        rowButton.childNodes[3].innerHTML="Estendi";
        
        if(containers[n]=="grafico")
            document.getElementById("valoriFiltriContainer"+n).remove();

        for (var i = 0; i < containers.length; i++)
        {
            if(i!=n)
            {
                document.getElementById("containerRiepilogoPresenzeDitteColumn"+i).style.display="";
            }
        }

        document.getElementById("contextMenuRiepilogoPresenzeDitte"+n).style.right="";

        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.width="";
        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.height="";
        document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).style.margin="";

        document.getElementById("chartContainer"+n).style.marginLeft="";
        document.getElementById("chartContainer"+n).style.marginRight="";
        document.getElementById("chartContainer"+n).style.width="";
        document.getElementById("chartContainer"+n).style.height="";

        //document.getElementById("containerRiepilogoPresenzeDitteRow"+row).style.height="";
        document.getElementById("containerRiepilogoPresenzeDitteRow"+row).style.width="";

        nGrafici.forEach(function(nGrafico)
        {
            chartArray[nGrafico].render();
        });

        /*for (var i = 0; i < nGrafici.length; i++)
        {
            chartArray[i].render();
        }*/

    }
    if(chartArray[n]!=null)
        chartArray[n].render();
    fullscreen=!fullscreen;
}
function getListActiveFilters(n)
{
    try
    {
        document.getElementById("valoriFiltriContainer"+n).remove();
    }
    catch (error){}
    
    var valoriFiltriContainer=document.createElement("div");
    valoriFiltriContainer.setAttribute("class","valoriFiltriContainer");
    valoriFiltriContainer.setAttribute("id","valoriFiltriContainer"+n);

    filters.forEach(function(filter)
    {
        if(activeFilters[n][filter].length>0)
        {
            var valoriFiltriListContainer=document.createElement("div");
            valoriFiltriListContainer.setAttribute("class","valoriFiltriListContainer");

            var valoriFiltriListTitle=document.createElement("div");
            valoriFiltriListTitle.setAttribute("class","valoriFiltriListTitle");
            valoriFiltriListTitle.innerHTML=filter;

            var valoriFiltriList=document.createElement("ul");
            valoriFiltriList.setAttribute("class","valoriFiltriList");

            for(var i=0;i<activeFilters[n][filter].length;i++)
            {
                var valoriFiltriListItem=document.createElement("li");
                valoriFiltriListItem.setAttribute("class","valoriFiltriListItem");

                var valoriFiltriListItemSpan=document.createElement("span");

                if(filter=="ditte")
                    valoriFiltriListItemSpan.innerHTML=decodeURIComponent(activeFilters[n][filter][i]);
                else
                    valoriFiltriListItemSpan.innerHTML=activeFilters[n][filter][i];

                valoriFiltriListItem.appendChild(valoriFiltriListItemSpan);

                valoriFiltriList.appendChild(valoriFiltriListItem);
            }

            valoriFiltriListContainer.appendChild(valoriFiltriListTitle);
            
            valoriFiltriListContainer.appendChild(valoriFiltriList);

            valoriFiltriContainer.appendChild(valoriFiltriListContainer);
        }
    });

    document.getElementById("containerRiepilogoPresenzeDitteColumn"+n).appendChild(valoriFiltriContainer);
}
