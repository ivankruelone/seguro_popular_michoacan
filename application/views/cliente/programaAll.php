							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('cliente/programaAll_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Suministro:', 'suministro', $suministro, null, 2); ?>
                                    
                                    <?php echo MY_form_dropdown2('Jurisdiccion: ', 'juris', $juris, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Tipo de Sucursal: ', 'tipo_sucursal', $tipo_sucursal, null, 6); ?>
                                    
                                    <?php echo MY_form_dropdown2('Nivel de Atención: ', 'nivel_atencion', $nivel_atencion, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Sucursal: ', 'sucursal', $sucursal, null, 6); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
