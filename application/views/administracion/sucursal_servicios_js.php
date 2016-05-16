<script type="text/javascript">

	$( "input[name^='activo_']" ).on('click', doClick);
    
    function doClick(event)
    {
        console.log(event);
        var $clvsucursal = event.currentTarget.attributes.clvsucursal.value;
        var $cveservicios = event.currentTarget.attributes.cveservicios.value;
        guarda($clvsucursal, $cveservicios);
    }


    function guarda($clvsucursal, $cveservicios)
    {
        var $url = '<?php echo site_url('administracion/saveSucursalServicio'); ?>';;
        var $variables = { clvsucursal : $clvsucursal, cveservicios : $cveservicios };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            
         });
    }

</script>