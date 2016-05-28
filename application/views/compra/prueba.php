							<div class="row-fluid">
                                <div class="span12">
                                
                                <?php
                                
                                $error = $this->session->flashdata('error');
                                if(strlen($error) >0){
                                
                                ?>
                                
                                <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">
											<i class="icon-remove"></i>
										</button>

										<strong>
											<i class="icon-remove"></i>
											Error !
										</strong>

										<?php echo $error; ?>
										<br />
									</div>
                                    
                                    <?php
                                    
                                    }
                                    
                                    ?>
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/0', 'Imprimir inventario de Medicamento', array('target' => '_blank')); ?></p>
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/1', 'Imprimir inventario de Material de Curacion', array('target' => '_blank')); ?></p>

                                    <p><?php echo anchor('inventario/reasignaUbicacion/', 'Reasigna ubicación'); ?></p>

                                    <?php
                                    	$data['query'] = $query; 
                                    	$this->load->view('inventario/inventario_template', $data);
                                    ?>

								</div>	
                            </div>
