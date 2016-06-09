<script type="text/javascript">
	
$('.eliminar').on('click', eliminar);

function eliminar(event)
{
	if(confirm("Seguro que deseas eliminar esta imagen ??"))
	{
		return true;
	}else{
		event.preventDefault();
		return false;
	}
}

</script>