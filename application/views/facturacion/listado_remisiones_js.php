<script type="text/javascript">
	
	$(".eliminarRemision").on('click', eliminar);

	function eliminar()
	{
		if(confirm("Estas segur@ que deseas eliminar esta remision??"))
		{
			return true;
		}else{
			return false;
		}
	}

</script>