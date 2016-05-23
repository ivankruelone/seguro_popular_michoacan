							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('cliente/recetas_periodo_detalleAll'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Programa: ', 'idprograma', $programa, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Requerimiento: ', 'tiporequerimiento', $requerimiento, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Suministro: ', 'cvesuministro', $suministro, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Jurisdiccion: ', 'juris', $juris, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Sucursal: ', 'sucursal', $sucursal, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Nivel de atenci&oacute;n: ', 'nivel', $nivelDeAtencion, null, 6); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
