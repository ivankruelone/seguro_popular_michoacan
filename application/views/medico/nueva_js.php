<script>
$('#expediente').on('change', checkExpediente);

function checkExpediente(data){
    
    var $url = '<?php echo site_url('captura/verifica_expediente'); ?>';
    var $variables = { expediente : data.currentTarget.value};
    var posting = $.post( $url, $variables );
        
         posting.done(function( data ) {
            
            if(data.length == 0)
            {
                $("#nombre").val('');
                $("#pat").val('');
                $("#mat").val('');
                $("#sexo").val('');
                $("#edad").val('0');
                $("#idprograma").val('0');
            }else{
                var $res = data.split('|');
                $("#nombre").val($res[0]);
                $("#pat").val($res[1]);
                $("#mat").val($res[2]);
                $("#sexo").val($res[3]);
                $("#edad").val($res[4]);
                $("#idprograma").val($res[5]);
            }
            
         });

}


$( "#cveArticulo1" ).autocomplete({
    
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('captura/busca_cveArticulo'); ?>',
                dataType: "json",
                data: {
                    term : request.term,
                    idprograma : $("#idprograma").val().trim()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function( event, ui ) {
    
    $("#cveArticulo1").val(ui.item.cveArticulo);
    $('#descripcion1').html(ui.item.descripcion);
    $('#susa1').html(ui.item.susa);
    $('#pres1').html(ui.item.pres);
    $('#ampuleo1').html(ui.item.ampuleo);
    //$('#nombre').val(ui.item.nombre);
    //$('#expediente').val(ui.item.paciente);
    
    
    $('#req1').focus();
    return false;
    
    }
});

$("#cveArticulo1").on("change", checkArt);
$("#cveArticulo2").on("change", checkArt);
$("#cveArticulo3").on("change", checkArt);

function checkArt(event)
{
    var $cveArticulo = event.currentTarget.value;
    var $id = event.currentTarget.attributes.id.value;
    var $n = $id.replace('cveArticulo', '');
    var $url = '<?php echo site_url('medico/verificaCveArticulo'); ?>';
    var $idprograma = $("#idprograma").val().trim();
    var $variables = { cveArticulo: $cveArticulo, idprograma: $idprograma };
    var posting = $.post( $url, $variables );

    posting.done(function( data ) {
                
        if( data == '0' )
        {
            alert("La clave tecleada: " + $cveArticulo + " no existe o esta fuera de cobertura, verifica por favor.");
            $('#descripcion' + $n).html('');
            $('#susa' + $n).html('');
            $('#pres' + $n).html('');
            $('#ampuleo' + $n).html('');
            $('#req' + $n).val('');
            $('#dosis' + $n).val('');
            $('#cveArticulo' + $n).val('').focus();

        }else
        {
            //Bien
        }
                
    });

}

$( "#cveArticulo2" ).autocomplete({
    
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('captura/busca_cveArticulo'); ?>',
                dataType: "json",
                data: {
                    term : request.term,
                    idprograma : $("#idprograma").val().trim()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function( event, ui ) {
    
    $("#cveArticulo2").val(ui.item.cveArticulo);
    $('#descripcion2').html(ui.item.descripcion);
    $('#susa2').html(ui.item.susa);
    $('#pres2').html(ui.item.pres);
    $('#ampuleo2').html(ui.item.ampuleo);
    //$('#nombre').val(ui.item.nombre);
    //$('#expediente').val(ui.item.paciente);
    
    
    $('#req2').focus();
    return false;
    
    }
});

$( "#cveArticulo3" ).autocomplete({
    
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('captura/busca_cveArticulo'); ?>',
                dataType: "json",
                data: {
                    term : request.term,
                    idprograma : $("#idprograma").val().trim()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
minLength: 2,
select: function( event, ui ) {
    
    $("#cveArticulo3").val(ui.item.cveArticulo);
    $('#descripcion3').html(ui.item.descripcion);
    $('#susa3').html(ui.item.susa);
    $('#pres3').html(ui.item.pres);
    $('#ampuleo3').html(ui.item.ampuleo);
    //$('#nombre').val(ui.item.nombre);
    //$('#expediente').val(ui.item.paciente);
    
    
    $('#req3').focus();
    return false;
    
    }
});

$( "#cie103" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('captura/busca_cie103'); ?>',
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
    
        $("#cie103").val(ui.item.cie);
        $('#cveArticulo').focus();
     
        return false;
    
    }
});

$( "#cie104" ).autocomplete({
source: function (request, response) {
            $.ajax({
                url: '<?php echo site_url('captura/busca_cie104'); ?>',
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
    
        $("#cie104").val(ui.item.cie);
        $('#cveArticulo').focus();
     
        return false;
    
    }
});

$("#default_form").on('submit', envio);

function envio()
{
    if(confirm("Seguro que deseas guardar, una vez guardada la receta no podra modificarse, solo cancelarse"))
    {
        return true;
    }else{
        return false;
    }
}
</script>