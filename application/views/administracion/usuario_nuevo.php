<div class="row-fluid">
    <div class="span12">
                                    
        <?php echo MY_form_open('administracion/usuario_nuevo_submit'); ?>
                                    
        <?php echo MY_form_input('clvusuario', 'clvusuario', 'Usuario', 'text', 'Usuario:', 3, true); ?>

        <?php echo MY_form_input('password', 'password', 'Password', 'password', 'Password', 3); ?>

        <?php echo MY_form_input('nombreusuario', 'nombreusuario', 'Nombre Usuario', 'text', 'Nombre:', 6, true); ?>

        <?php echo MY_form_dropdown2('Nivel: ', 'clvnivel', $clvnivel, null, 6); ?>

        <?php echo MY_form_dropdown2('Jurisdicción: ', 'numjurisd', $jurisd, null, 6); ?>

        <?php echo MY_form_dropdown2('Sucursal: ', 'clvsucursal', $clvsucursal, null, 6); ?>

        <?php echo MY_form_dropdown2('Puesto: ', 'clvpuesto', $clvpuesto, null, 6); ?>
        
        <?php echo MY_form_submit(); ?>
                                    
        <?php echo form_close(); ?>
    </div>	
</div>