							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('reportes/programaAll2_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Suministro:', 'suministro', $suministro, null, 2); ?>

                                    <?php echo MY_form_dropdown2('Programa: ', 'idprograma', $programas, null, 6); ?>
                                    
                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
