							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/0', 'Imprimir inventario de Medicamento', array('target' => '_blank')); ?></p>
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/1', 'Imprimir inventario de Material de Curacion', array('target' => '_blank')); ?></p>

                                    <p style="text-align: center;"><?php echo $this->pagination->create_links(); ?></p>

                                    <?php
                                        $data['query'] = $query;
                                        $this->load->view('inventario/inventario_template', $data);
                                    ?>                                    
								</div>	
                            </div>
