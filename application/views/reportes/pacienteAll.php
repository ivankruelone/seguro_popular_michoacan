							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('reportes/pacienteAll_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Suministro:', 'suministro', $suministro, null, 2); ?>

                                    <?php echo MY_form_input('expedienteAll', 'expedienteAll', 'Numero de expediente', 'text', 'Numero de expediente:', 12); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
