<script type="text/javascript">
<!--

$(document).on('ready', inicio);

function inicio()
{
    
    var $pa = parseFloat($('#pa').html().replace(/\,/gi, ''));
    var $sp = parseFloat($('#sp').html().replace(/\,/gi, ''));
    var $pr = parseFloat($('#pr').html().replace(/\,/gi, ''));
    var $sm = parseFloat($('#sm').html().replace(/\,/gi, ''));

    var chart = new CanvasJS.Chart("grafica",
    {
        animationEnabled: true, 
        animationDuration: 2000,
        toolTip: {
            enabled: true,
            shared: "true"
            },
      title:{
        text: "Grafica de Abasto por cobertura concentrado"
      },
       data: [
      {
         type: "pie",
       showInLegend: true,
       toolTipContent: "{legendText}: <strong>{y}%</strong>",
       indexLabel: "{label} {y}%",
       dataPoints: [
       {  y: $pa, legendText:"POBLACION ABIERTA", indexLabel: "POBLACION ABIERTA {y}%" },
       {  y: $sp, legendText:"SEGURO POPULAR", indexLabel: "SEGURO POPULAR {y}%" },
       {  y: $pr, legendText:"PROSPERA", indexLabel: "PROSPERA {y}%" },
       {  y: $sm, legendText:"SEGURO MEDICO SIGLO XXI", indexLabel: "SEGURO MEDICO SIGLO XXI {y}%" },
       ]
     }
     ]
   });

    chart.render();
    
}

    
-->
</script>