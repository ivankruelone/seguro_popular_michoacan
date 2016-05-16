<script type="text/javascript">
<!--
$('#orden').focus();
	
$('#fecha').datepicker({
    language: "es",
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd"
    });

$('#orden').change(function(){
    $('#orden').val($('#orden').val().toUpperCase());
    orden();
});

$('#remision').change(function(){
    $('#remision').val($('#remision').val().toUpperCase());
});

$('#folioreceta').change(function(){
    $('#folioreceta').val($('#folioreceta').val().toUpperCase());
});

$('#referencia').change(function(){
    $('#referencia').val($('#referencia').val().toUpperCase());
});

$('#observaciones').change(function(){
    $('#observaciones').val($('#observaciones').val().toUpperCase());
});

function orden()
{
    var $orden = $('#orden').val();
    
    var $url = '<?php echo site_url('movimiento/validaOrden'); ?>';
    var $variables = { orden : $orden };
    var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            var $json = JSON.parse(data);
            
            if($json.error)
            {
                alert('No existe ese numero de ORDEN');
                $('#orden').next().removeClass('icon-ok-sign').addClass('icon-remove-sign');
                $('#orden').val('').focus();
                return false;
            }
            
            var CurrentDate = new Date();
            var LimitDate = new Date($json[0].fecha_limite);
            
            if(LimitDate <= CurrentDate)
            {
                alert('La ORDEN expiro.');
                $('#orden').next().removeClass('icon-ok-sign').addClass('icon-remove-sign');
                $('#orden').val('').focus();
                return false;
            }
            
            $('#orden').next().removeClass('icon-remove-sign').addClass('icon-ok-sign');
            
         });
}

$("#referencia").on('change', validaReferencia);

function validaReferencia(event)
{
    var $referencia = event.currentTarget.value;

    var $url = '<?php echo site_url('movimiento/validaReferencia'); ?>';
    var $variables = { referencia : $referencia };
    var posting = $.post( $url, $variables );

    posting.done(function( data ) {
            
        var $json = JSON.parse(data);

        if (typeof $json.length === "undefined") {
            $("#sucursal_referencia").val('<?php echo ALMACEN; ?>');
            
        }else{
            $("#sucursal_referencia").val($json[0].clvsucursal);
        }


    });

}

-->
</script>