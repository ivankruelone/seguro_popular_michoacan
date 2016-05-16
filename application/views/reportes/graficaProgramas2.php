<script type="text/javascript">
<!--

$(document).on('ready', inicio);

function inicio()
{
   
   var $to = parseFloat($('#to').html().replace(/\,/gi, ''));
   
    var $pa = Math.round(parseFloat($('#pa').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $sp = Math.round(parseFloat($('#sp').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $op = Math.round(parseFloat($('#op').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $pp = Math.round(parseFloat($('#pp').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $bp = Math.round(parseFloat($('#bp').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $am = Math.round(parseFloat($('#am').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $pq = Math.round(parseFloat($('#pq').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $sm = Math.round(parseFloat($('#sm').html().replace(/\,/gi, ''))/$to*100*100)/100;
    var $ch = Math.round(parseFloat($('#ch').html().replace(/\,/gi, ''))/$to*100*100)/100;
    
    var chart = new CanvasJS.Chart("grafica",
    {
        animationEnabled: true, 
		animationDuration: 2000,
        toolTip: {
            enabled: true,
            shared: "true"
            },
      title:{
        text: "Consumos por programa"
      },
       data: [
      {
         type: "pie",
       showInLegend: true,
       toolTipContent: "{legendText}: <strong>{y}%</strong>",
       indexLabel: "{label} {y}%",
       dataPoints: [
       {  y: $pa, legendText:"PA", indexLabel: "POBLACION ABIERTA {y}%" },
       {  y: $sp, legendText:"SP", indexLabel: "SEGURO POPULAR {y}%" },
       {  y: $op, legendText:"OP", indexLabel: "OPORTUNIDADES {y}%" },
       {  y: $pp, legendText:"PP" , indexLabel: "PROGRAMA PRIORITARIOS {y}%"},
       {  y: $bp, legendText:"BP", indexLabel: "BENEFICENCIA PUBLICA {y}%" },
       {  y: $am, legendText:"AM" , indexLabel: "ADULTO MAYO {y}%"},
       {  y: $pq, legendText:"PQ" , indexLabel: "PAQUETES {y}%"},
       {  y: $sm, legendText:"SM" , indexLabel: "SEGURO MEDICO SIGLO XXI {y}%"},
       {  y: $ch, legendText:"CH" , indexLabel: "CLINICA DE HERIDAS {y}%"},
       ]
     }
     ]
   });

    chart.render();
}


-->
</script>