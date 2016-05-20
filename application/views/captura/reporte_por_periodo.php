							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('captura/recetas_periodo_detalle'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>
                                    
                                    <?php echo MY_form_dropdown2('Programa: ', 'idprograma', $programa, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Requerimiento: ', 'tiporequerimiento', $requerimiento, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Suministro: ', 'cvesuministro', $suministro, null, 6); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
