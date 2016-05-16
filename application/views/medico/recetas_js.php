<script type="text/javascript">
	$(".cancela_receta").on('click', cancela);

	function cancela()
	{
	    if(confirm("Seguro que deseas eliminar esta receta, una vez cancelada no podras volver hacia atras."))
	    {
	        return true;
	    }else{
	        return false;
	    }
	}
</script>