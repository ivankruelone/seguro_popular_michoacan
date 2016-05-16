<script type="text/javascript">
	

	$(document).on('ready', inicio);
	$("#clvnivel").on('change', nivel);
	$("#numjurisd").on('change', juris);


	function inicio()
	{
		getJuris();
	}

	function nivel()
	{
		getJuris();
	}

	function juris()
	{
		getSucursales();
	}

	function getSucursales()
	{
		var $numjurisd = $("#numjurisd").val();
		var $nivelUsuario = $("#clvnivel").val();
        var $url = '<?php echo site_url('administracion/getSucursales'); ?>';
        var $variables = { nivelUsuario : $nivelUsuario, numjurisd: $numjurisd };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            $("#clvsucursal").html(data);

         });
	}

	function getPuesto()
	{
		var $nivelUsuario = $("#clvnivel").val();
        var $url = '<?php echo site_url('administracion/getPuesto'); ?>';
        var $variables = { nivelUsuario : $nivelUsuario };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            $("#clvpuesto").html(data);

         });
	}

	function getJuris()
	{
		var $nivelUsuario = $("#clvnivel").val();
        var $url = '<?php echo site_url('administracion/getjuris'); ?>';
        var $variables = { nivelUsuario : $nivelUsuario };

        var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            $("#numjurisd").html(data);
            getSucursales();
            getPuesto();

         });
	}
</script>