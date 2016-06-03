<script type="text/javascript">
	
	$(".cancelar").on("click", cancelar);

	function cancelar(event)
	{
		if(confirm("Estas seguro que deseas CANCELAR este Movimiento ??"))
		{
			return true;
		}else{
			event.preventDefault();
			return false;
		}
	}


	$(".abrir").on("click", abrir);

	function abrir(event)
	{

		var $movimientoID = event.currentTarget.attributes.movimientoID.value;
		if(confirm("Estas seguro que deseas ABRIR este Movimiento: " + $movimientoID + " ??"))
		{
			return true;
		}else{
			event.preventDefault();
			return false;
		}
	}

</script>