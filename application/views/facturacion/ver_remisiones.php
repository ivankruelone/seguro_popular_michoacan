							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('facturacion/listado_remisiones'); ?>
                                                                        
                                    <?php echo MY_form_dropdown2('Sucursal: ', 'clvsucursal', $sucursal, null, 6); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
