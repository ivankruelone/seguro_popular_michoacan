<script type="text/javascript">
	

	$(".eliminar").on('click', eliminar);

	function eliminar(event)
	{
		if(confirm("Seguro que deseas eliminar este pasillo y todo su contenido??"))
		{

		}else{
			event.preventDefault();
		}
	}
</script>