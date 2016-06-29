<script type="text/javascript">
	
	$(".eliminarRemision").on('click', eliminar);

	function eliminar()
	{
		if(confirm("Estas segur@ que deseas ELIMINAR esta remision??"))
		{
			return true;
		}else{
			return false;
		}
	}

	$(".reactivarRemision").on('click', reactivar);

	function reactivar()
	{
		if(confirm("Estas segur@ que deseas REACTIVAR esta remision??"))
		{
			return true;
		}else{
			return false;
		}
	}

</script>