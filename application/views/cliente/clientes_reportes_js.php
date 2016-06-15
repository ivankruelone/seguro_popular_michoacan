<script type="text/javascript">
	
$('#fecha1').datepicker({
    language: "es",
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd"
    });

$('#fecha2').datepicker({
    language: "es",
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true,
    format: "yyyy-mm-dd"
    });

$("#juris").on("change", evalua);
$("#tipo_sucursal").on("change", evalua);
$("#nivel_atencion").on("change", evalua);

function evalua()
{
	var $juris = $("#juris").val();
	var $tipo_sucursal = $("#tipo_sucursal").val();
	var $nivel_atencion = $("#nivel_atencion").val();
	sucusales($juris, $tipo_sucursal, $nivel_atencion);
}

function sucusales($juris, $tipo_sucursal, $nivel_atencion)
{
    var $url = '<?php echo site_url('cliente/getSucursales'); ?>';
    var $variables = { juris: $juris, tipo_sucursal: $tipo_sucursal, nivel_atencion: $nivel_atencion };
    var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#sucursal').html(data);
                
             });
}

$( "#cveArticulo" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_cveArticulo'); ?>',
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function ( event, ui ) {
    
    
    $("#cveArticulo").val(ui.item.cveArticulo);
    return false;
    
    }
});

$( "#expedienteAll" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_expediente'); ?>',
                dataType: "json",
                data: {
                    term : request.term,
                    juris: $("#juris").val(),
                    sucursal: $("#sucursal").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function ( event, ui ) {
    
    
    $("#expedienteAll").val(ui.item.cvepaciente);
    return false;
    
    }
});

$( "#cveMedicoAll" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_cveMedicoAll'); ?>',
                dataType: "json",
                data: {
                    term : request.term,
                    sucursal: $("#sucursal").val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function ( event, ui ) {
    
    
    $("#cveMedicoAll").val(ui.item.cvemedico);
    return false;
    
    }
});

</script>