							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('facturacion/facturar_submit', 'forma'); ?>
                                    
                                    <?php 
                                    
                                        echo MY_form_input('remision', 'remision', 'Remisión', 'number', 'Remisión', 1, true, $remision, true);
                                    
                                        echo MY_form_dropdown2('Cliente', 'rfc', $clientes, null, 12); 
                                    
                                        echo MY_form_dropdown2('Contrato', 'contratoID', array('0' => 'Elige una opcion'), null, 12); 

                                    ?>

                                    
                                    <?php echo form_hidden('remision', $remision); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
                            
                            <div class="row-fluid">
                            
                                <div class="span12" id="vistaPrevia"></div>
                            
                            </div>
