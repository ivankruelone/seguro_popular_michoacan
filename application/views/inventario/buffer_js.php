<script type="text/javascript">
	
    $( "input[name^='buffer_']" ).on('change', doBuffer);

    function doBuffer(event)
    {
        var $id = event.currentTarget.attributes.id.value;
        var $clvsucursal = event.currentTarget.attributes.clvsucursal.value;
        var $buffer = event.currentTarget.value;
        guardaBuffer($id, $clvsucursal, $buffer);
    }

    function guardaBuffer($id, $clvsucursal, $buffer)
    {
        var $url = '<?php echo site_url('administracion/saveBuffer'); ?>';;
        var $variables = { id : $id, clvsucursal : $clvsucursal, buffer: $buffer };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            
         });
    }

	
</script>