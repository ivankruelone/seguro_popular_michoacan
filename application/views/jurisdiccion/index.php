							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <p><?php echo anchor('jurisdiccion/nuevo', 'Nuevo Colectivo'); ?></p>
                                    
                                    <?php 

                                    $data['query'] = $query;
                                    $this->load->view('jurisdiccion/paquetes', $data); 

                                    ?>
                                    
								</div>	
                            </div>
