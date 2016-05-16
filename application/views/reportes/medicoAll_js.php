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