<?php if($validaUbicacion === TRUE){ ?>
							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('almacen/nuevo_pedido_submit'); ?>
                                    
                                    <?php 
                                    
                                    if($subtipoMovimiento == 1 || $subtipoMovimiento == 3)
                                    {
                                        echo MY_form_input('orden', 'orden', 'Orden de compra', 'text', 'Orden de compra', 3); 
                                    }else{
                                        echo form_hidden('orden', 0);
                                    }
                                    
                                    
                                    if($subtipoMovimiento == 3)
                                    {
                                        echo MY_form_input('remision', 'remision', 'Remision', 'text', 'Remision', 3); 
                                    }else{
                                        echo form_hidden('remision', 0);
                                    }                                    
                                    
                                    
                                    ?>

                                    <?php 
                                    
                                    if($subtipoMovimiento == 4 || $subtipoMovimiento == 5 || $subtipoMovimiento == 6 || $subtipoMovimiento == 7 || $subtipoMovimiento == 8 || $subtipoMovimiento == 9 || $subtipoMovimiento == 12 || $subtipoMovimiento == 13 || $subtipoMovimiento == 15 || $subtipoMovimiento == 20 || $subtipoMovimiento == 21 || $subtipoMovimiento == 22 || $subtipoMovimiento == 23)
                                    {

                                        if($subtipoMovimiento == 22)
                                        {
                                            $fol = 'AUTO';
                                        }else
                                        {
                                            $fol = $tipoMovimiento.STR_PAD($this->session->userdata('clvsucursal'), 5, '0', STR_PAD_LEFT).STR_PAD($subtipoMovimiento, 2, '0', STR_PAD_LEFT).date('ymdHi');
                                        }
                                        echo MY_form_input('referencia', 'referencia', 'Folio', 'text', 'Folio', 3, true, $fol); 
                                    }elseif($subtipoMovimiento == 3)
                                    {
                                        
                                        echo form_hidden('referencia', 0);
                                    }elseif($subtipoMovimiento == 2)
                                    {
                                    	echo MY_form_input('referencia', 'referencia', 'Referencia', 'text', 'Referencia', 3); 
                                    }else{
                                        echo MY_form_input('referencia', 'referencia', 'Folio de Factura', 'text', 'Folio de Factura', 3); 
                                    }
                                    
                                    
                                    ?>

                                    <?php 
                                    
                                    if($subtipoMovimiento == 4 || $subtipoMovimiento == 5 || $subtipoMovimiento == 6 || $subtipoMovimiento == 7 || $subtipoMovimiento == 8 || $subtipoMovimiento == 9 || $subtipoMovimiento == 12 || $subtipoMovimiento == 13 || $subtipoMovimiento == 15 || $subtipoMovimiento == 20)
                                    {
                                        echo MY_form_datepicker('Fecha del documento', 'fecha', 3, true, date('Y-m-d')); 
                                    }else{
                                        echo MY_form_datepicker('Fecha del documento', 'fecha', 3, true); 
                                    }
                                    
                                    ?>

                                    <?php echo form_hidden('sucursal_referencia', $this->session->userdata('clvsucursal')); ?>

                                    <?php 
                                    
                                    if($subtipoMovimiento == 1 || $subtipoMovimiento == 3)
                                    {
                                        echo MY_form_dropdown2('Proveedor', 'proveedor', $proveedores, null, 6);
                                    }else{
                                        echo form_hidden('proveedor', 0);
                                    }
                                    
                                     
                                    
                                    ?>

                                    <?php 
                                    
                                    if($subtipoMovimiento == 22)
                                    {
                                        echo MY_form_dropdown2('Cobertura', 'idprograma', $programa, null, 6);
                                        echo MY_form_input('colectivo', 'colectivo', 'Folio de Colectivo', 'text', 'Colectivo<br />(Uno por paquete)', 12);
                                    }else{
                                        echo form_hidden('idprograma', 100);
                                        echo form_hidden('colectivo', null);
                                    }
                                    
                                     
                                    
                                    ?>

                                    <?php echo MY_form_input('observaciones', 'observaciones', 'Observaciones', 'text', 'Observaciones', 12, false); ?>
                                    
                                    <?php echo form_hidden('tipoMovimiento', $tipoMovimiento); ?>

                                    <?php echo form_hidden('subtipoMovimiento', $subtipoMovimiento); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
<?php }else{ ?>
                            <div class="row-fluid">
                                <div class="span12">

                                <?php echo anchor('almacen/area', 'Para poder agregar una entrada debes crear una Ubicacion antes, da click aqui para crearla'); ?>
                                
                                </div>
                            </div>

<?php } ?>