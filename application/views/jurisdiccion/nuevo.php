							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('jurisdiccion/nuevo_submit'); ?>
                                    
                                    <?php echo MY_form_input('folio', 'folio', 'Folio', 'text', 'Folio', 3); ?>

                                    <?php echo MY_form_datepicker('Fecha del documento', 'fecha', 3, true, date('Y-m-d')); ?>

                                    <?php echo MY_form_dropdown2('Sucursal', 'clvsucursal', $sucursales, null, 6); ?>

                                    <?php echo MY_form_dropdown2('Cobertura', 'idprograma', $programa, null, 6); ?>

                                    <?php echo MY_form_input('observaciones', 'observaciones', 'Observaciones', 'text', 'Observaciones', 12, false); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>