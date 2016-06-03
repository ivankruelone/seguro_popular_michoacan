<script type="text/javascript">
	
	$(document).on('ready', inicio);

	function inicio(){
    
    	detalle();
		$('#articulo2').focus();

	}
    
    function detalle()
    {
        
        $colectivoID = $('#colectivoID').html();
        
        var $url = '<?php echo site_url('jurisdiccion/detalle'); ?>';
        var $variables = { colectivoID: $colectivoID };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#detalle').html(data);
                
             });
        
    }

    $( "#articulo2" ).autocomplete({
        
    source: '<?php echo site_url('movimiento/busca_articulo_salida'); ?>' + '/' + $("#nivelatencionReferencia").html() + '/' + $("#cobertura").html(),
    minLength: 2,
    select: function( event, ui ) {
        
        
        $("#articulo2").val(ui.item.cvearticulo);
        $('#piezas').focus();
        return false;
        
        }
    });

    $('#articulo2').on('change', articuloValida2);

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

                if(parseInt($var1[0]) == 0)
                {
                    $('#articulo2').val('').focus();
                }
                
             });
        
    }

    $('#captura_form2').on('submit', envio2);

    function envio2(event)
    {
        event.preventDefault();
        
        $cvearticulo = $('#articulo2').val();
        $piezas = $('#piezas').val();
        $colectivoID = $('#colectivoID').html();

        if($cvearticulo.length >= 1 && parseInt($piezas))
        {


        var $url = '<?php echo site_url('jurisdiccion/captura_submit'); ?>';
        var $variables = { cvearticulo: $cvearticulo, piezas: $piezas, colectivoID: $colectivoID };
        var posting = $.post( $url, $variables );
            
        	posting.done(function( data ) {

	            if(parseInt(data) > 0)
	            {
		            detalle();
		                
		                
		            $('#susa').html('');
		            $('#descripcion').html('');
		            $('#pres').html('');

		            $('#piezas').val('');
		            $('#lote2').html('');
		            $('#articulo2').val('').focus();
	            }else{
	            	alert("Ya capturaste esa clave en este pedido, imposible duplicarla.");
	            }
                
                
            });
        }else{
        	alert("Revisa los datos.");
		    $('#susa').html('');
		    $('#descripcion').html('');
		    $('#pres').html('');
        	$('#articulo2').val('').focus();
        }

        
    }

    $('#cierre').on('click', cierre);
    
    function cierre(event){
        if(confirm("Seguro que deseas cerrar este Paquete ??")){
            
            return true;
            
        }else{
            event.preventDefault();
            return false;
        }
    }

</script>