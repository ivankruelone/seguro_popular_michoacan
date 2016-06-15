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

	$(".cerrar_sin_afectar").on("click", cerrar_sin_afectar);

	function cerrar_sin_afectar(event)
	{

		var $movimientoID = event.currentTarget.attributes.movimientoID.value;
		if(confirm("Estas seguro que deseas CERRAR SIN AFECTAR este Movimiento: " + $movimientoID + " ??"))
		{
			return true;
		}else{
			event.preventDefault();
			return false;
		}
	}

	$(".aprobar_pedido").on("click", aprobar_pedido);

	function aprobar_pedido(event)
	{

		var $movimientoID = event.currentTarget.attributes.movimientoID.value;
		if(confirm("Estas seguro que deseas APROBAR este MOVIMIENTO(PEDIDO DE SUCURSAL): " + $movimientoID + " ??"))
		{
			return true;
		}else{
			event.preventDefault();
			return false;
		}
	}

</script>