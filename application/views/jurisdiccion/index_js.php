<script type="text/javascript">
	
$(".aprobar").on("click", aprobar);

function aprobar(event)
{
	if(confirm("Seguro que deseas aprobar este paquete, una vez aprobado pasara a linea de surtido en el almacen con un folio de paquete ??"))
	{
		return true;
	}else{
		event.preventDefault();
		return false;
	}
}

$(".rechazar").on("click", rechazar);

function rechazar(event)
{
	if(confirm("Seguro que deseas RECHAZAR este paquete, con esto podras mandarlo a correcciones ??"))
	{
		return true;
	}else{
		event.preventDefault();
		return false;
	}
}

</script>