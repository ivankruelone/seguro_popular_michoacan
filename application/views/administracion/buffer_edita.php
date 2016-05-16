							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo $this->pagination->create_links(); ?>
                                    
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Buffer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            foreach($query->result() as $row){
                                                

                                                $data = array(
                                                  'name'        => 'buffer_'.$clvsucursal.'_'.$row->id,
                                                  'id'          => 'buffer_'.$clvsucursal.'_'.$row->id,
                                                  'value'       => $row->buffer,
                                                  'min'         => 0,
                                                  'max'         => 99999,
                                                  'type'        => 'number',
                                                  'clvsucursal' => $clvsucursal,
                                                  'id'          => $row->id,
                                                );

                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo form_input($data); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
