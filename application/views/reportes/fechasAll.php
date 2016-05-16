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

$("#juris").on('change', jurisCambio);
$(document).on('ready', inicio);

function inicio()
{
    jurisCambio();
}

function jurisCambio()
{
    $juris = $("#juris").val();
    
    var $url = '<?php echo site_url('reportes/getSucursalesByJuris'); ?>';
    var $variables = { juris: $juris };
    var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#sucursal').html(data);
                
             });
}

$( "#expediente" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_expediente'); ?>',
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
    
    
    $("#expediente").val(ui.item.cvepaciente);
    return false;
    
    }
});

$( "#expedienteJur" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_expedienteJur'); ?>',
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
    
    
    $("#expedienteJur").val(ui.item.cvepaciente);
    return false;
    
    }
});

$( "#expedienteAll" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_expedienteAll'); ?>',
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

$( "#cveMedico" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_cveMedico'); ?>',
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
    
    
    $("#cveMedico").val(ui.item.cvemedico);
    return false;
    
    }
});

$( "#cveMedicoJur" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('reportes/busca_cveMedicoJur'); ?>',
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
    
    
    $("#cveMedicoJur").val(ui.item.cvemedico);
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