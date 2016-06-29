<script type="text/javascript">
<!--

$(document).on('ready', inicio);

function inicio()
{
  requerimiento();
  desplazamiento();
  total();
  abasto();
}

function requerimiento()
{
    
    var $recetas = parseFloat($('#recetas').html().replace(/\,/gi, ''));
    var $colectivos = parseFloat($('#colectivos').html().replace(/\,/gi, ''));
    var $paquetes = parseFloat($('#paquetes').html().replace(/\,/gi, ''));    
    var $total = $recetas + $colectivos + $paquetes;

    $recetasp = ($recetas / $total) * 100;
    $colectivosp = ($colectivos / $total) * 100;
    $paquetesp = ($paquetes / $total) * 100;

    $recetasp = Math.round($recetasp * 100) / 100;
    $colectivosp = Math.round($colectivosp * 100) / 100;
    $paquetesp = Math.round($paquetesp * 100) / 100;

    var chart = new CanvasJS.Chart("graficaRecetas",
    {
      animationEnabled: true, 
      animationDuration: 2000,
      toolTip: {
        enabled: true,
        shared: "true"
      },
      title:{
        text: "Folios surtidos por Requerimiento"
      },
      theme: "theme2",
      data: [
      {
        type: "pie",
        showInLegend: true,
        toolTipContent: "{legendText}: <strong>{y}%</strong>",
        indexLabel: "{label} {y}%",
        dataPoints: [
        {  y: $recetasp, legendText:"Recetas", indexLabel: "Recetas {y}%" },
        {  y: $colectivosp, legendText:"Colectivos", indexLabel: "Colectivos {y}%" },
        {  y: $paquetesp, legendText:"Paquetes", indexLabel: "Paquetes {y}%" },
      ]
     }
     ]
   });

    chart.render();
    

}

function desplazamiento()
{
    
    var $recetas = parseFloat($('#recetasPiezas').html().replace(/\,/gi, ''));
    var $colectivos = parseFloat($('#colectivosPiezas').html().replace(/\,/gi, ''));
    var $paquetes = parseFloat($('#paquetesPiezas').html().replace(/\,/gi, ''));    
    var $total = $recetas + $colectivos + $paquetes;

    $recetasp = ($recetas / $total) * 100;
    $colectivosp = ($colectivos / $total) * 100;
    $paquetesp = ($paquetes / $total) * 100;

    $recetasp = Math.round($recetasp * 100) / 100;
    $colectivosp = Math.round($colectivosp * 100) / 100;
    $paquetesp = Math.round($paquetesp * 100) / 100;

    var chart = new CanvasJS.Chart("graficaDesplazamiento",
    {
      animationEnabled: true, 
      animationDuration: 2000,
      toolTip: {
        enabled: true,
        shared: "true"
      },
      title:{
        text: "Piezas desplazadas por Requerimiento"
      },
      theme: "theme2",
      data: [
      {
        type: "pie",
        showInLegend: true,
        toolTipContent: "{legendText}: <strong>{y}%</strong>",
        indexLabel: "{label} {y}%",
        dataPoints: [
        {  y: $recetasp, legendText:"Recetas", indexLabel: "Recetas {y}%" },
        {  y: $colectivosp, legendText:"Colectivos", indexLabel: "Colectivos {y}%" },
        {  y: $paquetesp, legendText:"Paquetes", indexLabel: "Paquetes {y}%" },
      ]
     }
     ]
   });

    chart.render();
    

}
	
function total()
{
    
    var $recetas = parseFloat($('#recetasTotal').html().replace(/\,/gi, ''));
    var $colectivos = parseFloat($('#colectivosTotal').html().replace(/\,/gi, ''));
    var $paquetes = parseFloat($('#paquetesTotal').html().replace(/\,/gi, ''));    
    var $total = $recetas + $colectivos + $paquetes;

    $recetasp = ($recetas / $total) * 100;
    $colectivosp = ($colectivos / $total) * 100;
    $paquetesp = ($paquetes / $total) * 100;

    $recetasp = Math.round($recetasp * 100) / 100;
    $colectivosp = Math.round($colectivosp * 100) / 100;
    $paquetesp = Math.round($paquetesp * 100) / 100;

    var chart = new CanvasJS.Chart("graficaTotal",
    {
      animationEnabled: true, 
      animationDuration: 2000,
      toolTip: {
        enabled: true,
        shared: "true"
      },
      title:{
        text: "Monto por Requerimiento"
      },
      theme: "theme2",
      data: [
      {
        type: "pie",
        showInLegend: true,
        toolTipContent: "{legendText}: <strong>{y}%</strong>",
        indexLabel: "{label} {y}%",
        dataPoints: [
        {  y: $recetasp, legendText:"Recetas", indexLabel: "Recetas {y}%" },
        {  y: $colectivosp, legendText:"Colectivos", indexLabel: "Colectivos {y}%" },
        {  y: $paquetesp, legendText:"Paquetes", indexLabel: "Paquetes {y}%" },
      ]
     }
     ]
   });

    chart.render();
    

}

function abasto()
{
    var $recetas = parseFloat($('#recetasAbasto').html().replace(/\,/gi, ''));
    var $colectivos = parseFloat($('#colectivosAbasto').html().replace(/\,/gi, ''));
    var $paquetes = parseFloat($('#paquetesAbasto').html().replace(/\,/gi, ''));    

    $recetas = Math.round($recetas * 100) / 100;
    $colectivos = Math.round($colectivos * 100) / 100;
    $paquetes = Math.round($paquetes * 100) / 100;

        var chart = new CanvasJS.Chart("graficaAbasto",
        {
          title:{
            text: "Abasto por Requerimiento"    
          },
          animationEnabled: true,
          axisY: {
            title: "Abasto(%)"
          },
          legend: {
            verticalAlign: "bottom",
            horizontalAlign: "center"
          },
          theme: "theme2",
          data: [

          {        
            type: "column",  
            showInLegend: true, 
            legendMarkerColor: "grey",
            legendText: "Abasto",
            dataPoints: [      
            {y: $recetas, label: "Recetas"},
            {y: $colectivos,  label: "Colectivos" },
            {y: $paquetes,  label: "Paquetes"},
            ]
          }   
          ]
        });

        chart.render();
}

-->
</script>