							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('reportes/entradas_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Elige la fecha inicial', 'fecha1', 3); ?>

                                    <?php echo MY_form_datepicker('Elige la fecha final', 'fecha2', 3); ?>

                                    <?php echo MY_form_input('proveedorID', 'proveedorID', 'Proveedor', 'text', 'Proveedor', 6, FALSE); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
