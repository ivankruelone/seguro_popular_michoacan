<?php 
if($this->session->userdata('superuser'))
{
	$row = $query->row();


?>
                        	<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('movimiento/cambiar_submit'); ?>
                                    
                                    <?php echo MY_form_input('cvearticulo', 'cvearticulo', 'Clave de articulo', 'text', 'Clave de articulo', 3, true, $row->cvearticulo, true); ?>

                                    <?php echo MY_form_input('susa', 'susa', 'Susa', 'text', 'Susa', 12, true, $row->susa, true); ?>

                                    <?php echo MY_form_input('descripcion', 'descripcion', 'Descripcion', 'text', 'Descripcion', 12, true, $row->descripcion, true); ?>

                                    <?php echo MY_form_input('pres', 'pres', 'Presentacion', 'text', 'Presentacion', 12, true, $row->pres, true); ?>

                                    <?php echo MY_form_input('lote', 'lote', 'Lote', 'text', 'Lote', 12, true, $row->lote, true); ?>

                                    <?php echo MY_form_input('caducidad', 'caducidad', 'Caducidad', 'text', 'Caducidad', 12, true, $row->caducidad, true); ?>

                                    <?php echo MY_form_input('piezasNueva', 'piezasNueva', 'Piezas', 'number', 'Piezas', 3, true, $row->piezas); ?>

                                    <?php echo form_hidden('movimientoDetalle', $row->movimientoDetalle); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>
<?php } ?>