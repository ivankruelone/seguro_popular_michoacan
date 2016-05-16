<script type="text/javascript">
<!--

$(document).on('ready', inicio);

function inicio()
{
    
    var $surtidas = parseFloat($('#sur').html().replace(/\,/gi, ''));
    var $requeridas = parseFloat($('#req').html().replace(/\,/gi, ''));

    $abastecimiento = (($surtidas / $requeridas) * 100);
    $abastecimiento = Math.round($abastecimiento * 100) / 100;
    $desabastecimiento = (100 - $abastecimiento);
    
    
    var chart = new CanvasJS.Chart("grafica",
    {
        animationEnabled: true, 
		animationDuration: 2000,
        toolTip: {
            enabled: true,
            shared: "true"
            },
      title:{
        text: "Grafica de Abasto"
      },
       data: [
      {
         type: "pie",
       showInLegend: true,
       toolTipContent: "{legendText}: <strong>{y}%</strong>",
       indexLabel: "{label} {y}%",
       dataPoints: [
       {  y: $abastecimiento, legendText:"Abasto", indexLabel: "Abasto {y}%" },
       {  y: $desabastecimiento, legendText:"Desabasto", indexLabel: "Desabasto {y}%" },
       ]
     }
     ]
   });

    chart.render();
    
}

	
-->
</script>