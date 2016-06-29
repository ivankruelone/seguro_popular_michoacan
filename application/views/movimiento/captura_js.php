<script type="text/javascript">
<!--
$(document).on('ready', inicio);

function inicio(){

    $("#cargando2").hide();
    
    detalle();

$( "#id-btn-dialog1" ).on('click', function(e) {
					e.preventDefault();
			
					var dialog = $( "#dialog-message" ).dialog({
						modal: true,
                        width: '80%',
						title: "",
						title_html: true,
						buttons: [ 
							{
								text: "OK",
								"class" : "btn btn-primary btn-mini",
								click: function() {
									$( this ).dialog( "close" ); 
								} 
							}
						]
					});
			
					/**
					dialog.data( "uiDialog" )._title = function(title) {
						title.html( this.options.title );
					};
					**/
				});
}
    $('#articulo').focus();
    
    $( "#articulo" ).autocomplete({
        
    source: '<?php echo site_url('movimiento/busca_articulo'); ?>',
    minLength: 2,
    select: function( event, ui ) {
        
        
        $("#articulo").val(ui.item.cvearticulo);
        $('#piezas').focus();
        return false;
        
        }
    });
    	
    $( "#articulo2" ).autocomplete({
        
    source: '<?php echo site_url('movimiento/busca_articulo_salida'); ?>' + '/' + $("#nivelatencionReferencia").html() + '/' + $("#cobertura").html(),
    minLength: 2,
    select: function( event, ui ) {
        
        
        $("#articulo2").val(ui.item.cvearticulo);
        $('#piezas').focus();
        return false;
        
        }
    });

    $("#articulo2").on('dblclick', rebusca);

    function rebusca()
    {
        articuloValida2();
    }

    $('#caducidad').datepicker({
        language: "es",
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true,
        format: "yyyy-mm-dd"
        });
    
    $('#captura_form').on('submit', envio);
    
    $('#captura_form2').on('submit', envio2);
    
    function envio(event)
    {
        event.preventDefault();
        
        $articulo = $('#articulo').val();
        $piezas = $('#piezas').val();
        $lote = $('#lote').val().toUpperCase();
        $caducidad = $('#caducidad').val();
        $ean = $('#ean').val();
        $marca = $('#marca').val().toUpperCase();
        $costo = $('#costo').val();
        $movimientoID = $('#movimientoID').html();
        $ubicacion = $('#ubicacion').val();
        $comercial = $('#comercial').val();
        
        var $url = '<?php echo site_url('movimiento/captura_submit'); ?>';
        var $variables = { articulo: $articulo, piezas: $piezas, lote: $lote, caducidad: $caducidad, ean: $ean, marca: $marca, costo: $costo, movimientoID: $movimientoID, ubicacion: $ubicacion, comercial: $comercial };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                detalle();
                
                
                $piezas = $('#piezas').val('');
                $lote = $('#lote').val('');
                $caducidad = $('#caducidad').val('');
                $ean = $('#ean').val('');
                $marca = $('#marca').val('');
                $costo = $('#costo').val('');
                $('#ubicacion').html('');
                $('#susa').html('');
                $('#comercial').val('');
                $('#descripcion').html('');
                $('#pres').html('');
                $('#cans').html('');
                $('#codigo').html('');
                $('#costoe').html('');
                $articulo = $('#articulo').val('').focus();
                
                
             });
        
    }

    function envio2(event)
    {
        event.preventDefault();
        
        $inventarioID = $('#lote2').val();
        $piezas = $('#piezas').val();
        $movimientoID = $('#movimientoID').html();
        
        var $url = '<?php echo site_url('movimiento/captura_submit2'); ?>';
        var $variables = { inventarioID: $inventarioID, piezas: $piezas, movimientoID: $movimientoID };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                detalle();
                
                
                $('#piezas').val('');
                $('#lote2').html('');
                $('#articulo2').val('').focus();
                
             });
        
    }

    function detalle()
    {
        
        $movimientoID = $('#movimientoID').html();
        
        var $url = '<?php echo site_url('movimiento/detalle'); ?>';
        var $variables = { movimientoID: $movimientoID };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#detalle').html(data);
                
             });
        
    }
    
    
    <?php
	if(PATENTE == 1)
    {
        echo "$('#articulo').on('change', articuloValida3);";
    ?>
    
    
    <?php
        
    }else{
        
        echo "$('#articulo').on('change', articuloValida);";
        
    ?>
    
    <?php
    }
?>
    
    $('#articulo2').on('change', articuloValida2);

    //$('#lote2').on('focus', cargaLote);
    
    function cargaLote()
    {
        var $articulo = $('#articulo2').val();
        var $subtipoMovimiento = $( "input[name='subtipoMovimiento']" ).val();
        var $url = '<?php echo site_url('movimiento/cargaLotes'); ?>';
        var $variables = { articulo: $articulo, subtipoMovimiento: $subtipoMovimiento };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#lote2').html(data);
                $("#cargando2").hide();
                
             });

    }
    
    function articuloValida()
    {
        
        $articulo = $('#articulo').val();
        $ean = $('#articulo').val();
        
        if($articulo.length > 0)
        {
            $orden = parseInt($('#orden').html());
            
            var $url = '<?php echo site_url('movimiento/articuloValida'); ?>';
            var $variables = { articulo: $articulo, orden: $orden };
            var posting = $.post( $url, $variables );
                
                 posting.done(function( data ) {
                    
                    $var1 = data.split('|');
                    
                    
                    if($orden > 0)
                    {
                        if(parseInt($var1[5]) > 0)
                        {
                            $('#clave').html($var1[1]);
                            $('#susa').html($var1[2]);
                            $('#descripcion').html($var1[3]);
                            $('#pres').html($var1[4]);
                            
                            $('#cans').html($var1[5]);
                            $('#codigo').html($var1[6]);
                            $('#costoe').html($var1[7]);
                            
                            getUbicaciones($var1[1]);
                            getMarca($ean);
                            
                            return true;
                        }else{
                            
                            $('#descripcion').html('');
                            $('#pres').html('');
                            
                            $('#cans').html('');
                            $('#codigo').html('');
                            $('#costoe').html('');
                            $("#ubicacion").html('');
                            
                            if(parseInt($var1[5]) == -1)
                            {
                                alert('Esta clave no esta asignada en esta orden de compra.');
                            }else
                            {
                                alert('Ya se recibio la cantidad estipulada');
                            }
                            
                            $('#articulo').val('').focus();
    
                            return false;
                        }
                    }else{
                            $('#articulo').val($var1[1]);
                            $('#susa').html($var1[2]);
                            $('#descripcion').html($var1[3]);
                            $('#pres').html($var1[4]);
                            
                            $('#cans').html($var1[5]);
                            $('#codigo').html($var1[6]);
                            $('#costoe').html($var1[7]);
                            
            
                            getUbicaciones($var1[1]);
                            getMarca($ean);
                            
                            return true;
                    }
                    
    
                    
                 });
        }
        
    }
    
    function articuloValida3()
    {
        
        $articulo = $('#articulo').val();
        $ean = $('#articulo').val();
        
        if($articulo.length > 0)
        {
            $orden = parseInt($('#orden').html());
            
            var $url = '<?php echo site_url('movimiento/articuloValida'); ?>';
            var $variables = { articulo: $articulo, orden: $orden };
            var posting = $.post( $url, $variables );
                
                 posting.done(function( data ) {
                    
                    $var1 = data.split('|');
                    
                    
 
                            $('#articulo').val($var1[1]);
                            $('#susa').html($var1[2]);
                            $('#descripcion').html($var1[3]);
                            $('#pres').html($var1[4]);
                            
                            $('#cans').html($var1[5]);
                            $('#codigo').html($var1[6]);
                            $('#costoe').html($var1[7]);
                            
            
                            getUbicaciones($ean);
                            getMarca($ean);
                            
                            return true;
                    
                    
    
                    
                 });
        }
        
    }

    function getUbicaciones($articulo)
    {
        var $url = '<?php echo site_url('movimiento/getUbicaciones'); ?>';
        var $variables = { cvearticulo: $articulo };
        var posting = $.post( $url, $variables );
        
             posting.done(function( data ) {
                
                $("#ubicacion").html(data);
                
             });
    }
    
    function getMarca($ean)
    {
        var $url = '<?php echo site_url('movimiento/getEANMarca'); ?>';
        var $variables = { ean: $ean };
        var posting = $.post( $url, $variables );
        
             posting.done(function( data ) {
                
                $var1  = data.split('|');
                
                
                $("#ean").val($var1[0]);
                $("#marca").val($var1[1]);
                
             });
    }

    function articuloValida2()
    {
        
        var $articulo = $('#articulo2').val();
        var $nivelatencionReferencia = $("#nivelatencionReferencia").html();
        var $cobertura = $("#cobertura").html();

        $("#cargando2").show();
        
        var $url = '<?php echo site_url('movimiento/articuloValidaSalida'); ?>';
        var $variables = { articulo: $articulo, nivelatencionReferencia: $nivelatencionReferencia, cobertura: $cobertura };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $var1 = data.split('|');
                
                $('#articulo2').val($var1[1]);
                $('#susa').html($var1[2]);
                $('#descripcion').html($var1[3]);
                $('#pres').html($var1[4]);
                cargaLote();
                
             });
        
    }

    $('#cierre').on('click', cierre);
    
    function cierre(event){
        if(confirm("Seguro que deseas cerrar este Movimiento ??")){
            
            return true;
            
        }else{
            event.preventDefault();
            return false;
        }
    }
    
    var availableTags = '<?php echo $json; ?>';
    var arr = $.map(JSON.parse(availableTags), function(el) { return el; });

    $( "#marca" ).autocomplete({
    
source: arr,
minLength: 2,
select: function( event, ui ) {
    
    $("#marca").val(ui.item.value);

    return false;
    
    }
});


$("#llenadoAutomatico").on('click', llena);

function llena()
{
    var $referencia = $("#referencia").html();
    var $movimientoID = $("#movimientoID").html();
    var $url = '<?php echo site_url('movimiento/getSalidaRemota'); ?>';
    var $variables = { referencia: $referencia, movimientoID: $movimientoID };
    var posting = $.post( $url, $variables );

    posting.done(function( data ){

        detalle();
                
    });

}




-->
</script>