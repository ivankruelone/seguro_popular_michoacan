							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('reportes/medicoAll_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Suministro:', 'suministro', $suministro, null, 2); ?>
                                    
                                    <?php echo MY_form_dropdown2('Jurisdiccion: ', 'juris', $juris, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Sucursal: ', 'sucursal', $sucursal, null, 6); ?>

                                    <?php echo MY_form_input('cveMedicoAll', 'cveMedicoAll', 'Clave de medico', 'text', 'Clave de Medico:', 12); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
