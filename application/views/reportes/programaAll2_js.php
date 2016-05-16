<script>

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




</script>   

 