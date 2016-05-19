<?php $row = $query->row(); ?>
<div class="row-fluid">
    <div class="span12">
                                    
        <?php echo MY_form_open('administracion/perfil_edita_submit'); ?>
                                    
        <?php echo MY_form_input('puesto', 'puesto', 'Puesto', 'text', 'Puesto:', 3, true, $row->puesto); ?>

        <?php echo MY_form_dropdown2('Nivel: ', 'nivelUsuarioID', $clvnivel, $row->nivelUsuarioIDR, 6); ?>

        <?php echo MY_form_dropdown2('Ver valores: ', 'valuacion', $valuacion, $row->valuacion, 6); ?>

        <?php echo MY_form_dropdown2('Consulta: ', 'consulta', $valuacion, $row->consulta, 6); ?>

        <?php echo form_hidden('clvpuesto', $row->clvpuesto); ?>
        
        <?php echo MY_form_submit(); ?>
                                    
        <?php echo form_close(); ?>
    </div>	
</div>