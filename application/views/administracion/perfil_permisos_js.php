<script type="text/javascript">
<!--
	$( "input[name^='opcion_']" ).on('click', doClick);
    
    function doClick(event)
    {
        console.log(event);
        var $clvpuesto = event.currentTarget.value;
        var $submenu = event.currentTarget.attributes.id.value;
        guarda($clvpuesto, $submenu);
    }


    function guarda($clvpuesto, $submenu)
    {
        var $url = '<?php echo site_url('administracion/savePermisoPerfil'); ?>';;
        var $variables = { clvpuesto : $clvpuesto, submenu : $submenu };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            
         });
    }
-->
</script>