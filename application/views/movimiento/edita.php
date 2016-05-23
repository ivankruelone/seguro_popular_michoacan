<?php
	$row = $query->row();
?>

							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('movimiento/edita_submit'); ?>
                                    
                                    <?php echo MY_form_datepicker('Fecha del documento', 'fecha', 3, true, $row->fecha); ?>
                                    
                                    <?php 
                                    
                                    if($row->subtipoMovimiento == 1 || $row->subtipoMovimiento == 3)
                                    {
                                        echo MY_form_input('orden', 'orden', 'Orden de compra', 'text', 'Orden de compra', 3, false, $row->orden); 
                                    }else{
                                        echo form_hidden('orden', 0);
                                    }

                                    ?>

                                    <?php echo MY_form_input('referencia', 'referencia', 'Referencia de documento', 'text', 'Referencia de documento', 3, true, $row->referencia); ?>

                                    <?php 
                                    
                                    if($row->subtipoMovimiento == 1 || $row->subtipoMovimiento == 3 || $row->subtipoMovimiento == 12 || $row->subtipoMovimiento == 15)
                                    {
                                        echo form_hidden('sucursal_referencia', 0);
                                    }else{

                                        if($row->tipoMovimiento == 1)
                                        {
                                            echo MY_form_dropdown2('Sucursal Origen', 'sucursal_referencia', $sucursales, $row->clvsucursalReferencia, 6); 
                                        }elseif ($row->tipoMovimiento == 2){
                                            echo MY_form_dropdown2('Sucursal Destino', 'sucursal_referencia', $sucursales, $row->clvsucursalReferencia, 6); 
                                        }elseif ($row->tipoMovimiento == 3) {
                                            echo MY_form_dropdown2('Sucursal', 'sucursal_referencia', $sucursales, $row->clvsucursalReferencia, 6); 
                                        }
                                        
                                    }
                                    
                                    ?>


                                    <?php 
                                    
                                    if($row->subtipoMovimiento == 1 || $row->subtipoMovimiento == 3)
                                    {
                                        echo MY_form_dropdown2('Proveedor', 'proveedor', $proveedores, $row->proveedorID, 6);
                                    }else{
                                        echo form_hidden('proveedor', 0);
                                    }
                                    
                                     
                                    
                                    ?>


                                    <?php 
                                    
                                    if($row->subtipoMovimiento == 22)
                                    {
                                        echo MY_form_dropdown2('Cobertura', 'idprograma', $programa, $row->cobertura, 6);
                                    }else{
                                        echo form_hidden('idprograma', 100);
                                    }
                                    
                                     
                                    
                                    ?>

                                    <?php echo MY_form_input('observaciones', 'observaciones', 'Observaciones', 'text', 'Observaciones', 12, false, $row->observaciones); ?>
                                    
                                    <?php echo form_hidden('tipoMovimiento', $row->tipoMovimiento); ?>

                                    <?php echo form_hidden('subtipoMovimiento', $row->subtipoMovimiento); ?>

                                    <?php echo form_hidden('movimientoID', $row->movimientoID); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
