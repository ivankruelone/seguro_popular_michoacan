<?php
	$row = $query->row();
    
    if($query2->num_rows() > 0)
    {
        $row2 = $query2->row();
        $nombre = $row2->nombreSucursalPersonalizado;
        $domicilio = $row2->domicilioSucursalPersonalizado;
        $director = $row2->director;
        $administrador = $row2->administrador;
    }else{
        $nombre = null;
        $domicilio = null;
        $director = null;
        $administrador = null;
    }
    
    
?>							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('administracion/sucursal_edita_submit'); ?>

                                    <?php echo MY_form_input('descsucursal', 'descsucursal', 'Descripcion sucursal', 'text', 'Descripcion Sucursal', 6, true, $row->descsucursal, true); ?>

                                    <?php echo MY_form_dropdown2('Jurisdiccion', 'numjurisd', $juris, $row->numjurisd, 12); ?>
                                    
                                    <?php echo MY_form_dropdown2('Nivel de atención', 'nivelAtencion', $nivelAtencion, $row->nivelAtencion, 12); ?>

                                    <?php echo MY_form_dropdown2('Dia de pedido', 'diaped', $dia, $row->diaped, 12); ?>

                                    <?php echo MY_form_input('nombreSucursalPersonalizado', 'nombreSucursalPersonalizado', 'Nombre sucursal personalizado', 'text', 'Nombre Sucursal Personalizado', 12, false, $nombre); ?>

                                    <?php echo MY_form_input('domicilioSucursalPersonalizado', 'domicilioSucursalPersonalizado', 'Domicilio sucursal personalizado', 'text', 'Domicilio Sucursal Personalizado', 12, false, $domicilio); ?>

                                    <?php echo MY_form_input('director', 'director', 'Director', 'text', 'Director', 12, false, $director); ?>

                                    <?php echo MY_form_input('administrador', 'administrador', 'Administrador', 'text', 'Administrador', 12, false, $administrador); ?>

                                    <?php echo form_hidden('clvsucursal', $row->clvsucursal); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); 
                                    
                                    //clvsucursal, descsucursal, tiposucursal, numjurisd, diaped, calle, noexterior, nointerior, colonia, municipio, estado, pais, cp, cia, 
                                    //nombreSucursalPersonalizado, domicilioSucursalPersonalizado
                                    ?>
                                    
								</div>	
                            </div>
