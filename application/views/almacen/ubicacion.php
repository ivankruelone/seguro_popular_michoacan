							<div class="row-fluid">
                                <div class="span12">
                                                                        
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Ubicación</th>
                                                <th>Coordenadas</th>
                                                <th style="width: 200px; ">Area</th>
                                                <th style="width: 200px; ">Pasillo</th>
                                                <th>Asignada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            foreach($query->result() as $row){
                                                
                                                if($row->id > 0)
                                                {
                                                    $asignada = '<span style="color: red;">' . $row->cvearticulo . '</span> - ' . $row->susa . ' ' . $row->descripcion . ' ' . $row->pres;
                                                }else
                                                {
                                                    $asignada = 'LIBRE';
                                                }

                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->ubicacion; ?></td>
                                                <td><?php echo $row->areaID . '-' . $row->pasilloID . '-' . $row->moduloID . '-' . $row->nivelID . '-' . $row->posicionID; ?></td>
                                                <td><?php echo $row->area; ?></td>
                                                <td><?php echo $row->pasillo; ?></td>
                                                <td><?php echo $asignada; ?></td>
                                            </tr>
                                            <?php 
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
