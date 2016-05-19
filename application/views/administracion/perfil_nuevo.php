<div class="row-fluid">
    <div class="span12">
                                    
        <?php echo MY_form_open('administracion/perfil_nuevo_submit'); ?>
                                    
        <?php echo MY_form_input('puesto', 'puesto', 'Puesto', 'text', 'Puesto:', 3, true); ?>

        <?php echo MY_form_dropdown2('Nivel: ', 'nivelUsuarioID', $clvnivel, null, 6); ?>

        <?php echo MY_form_dropdown2('Ver valores: ', 'valuacion', $valuacion, null, 6); ?>
        
        <?php echo MY_form_dropdown2('Consulta: ', 'consulta', $valuacion, null, 6); ?>

        <?php echo MY_form_submit(); ?>
                                    
        <?php echo form_close(); ?>
    </div>	
</div>