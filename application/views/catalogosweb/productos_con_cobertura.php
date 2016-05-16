							<div class="row-fluid">
                                <div class="span12">
                                                                        
                                    <table class="table table-condensed table-striped table-hover">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="width: 200px; ">Coberturas</th>
                                                <th>Antibiotico</th>
                                                <th>CAUSE</th>
                                                <th>Clasificacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            foreach($query->result() as $row){
                                                
                                                $semaforo = '<i class="icon-circle" style="color: '.$row->semaforoColor.'"> ' . $row->semaforoDescripcion . '</i>';

                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->id; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo CSVtoList($row->programa); ?></td>
                                                <td style="text-align: center;"><?php echo table_ok_cancel_element($row->antibiotico); ?></td>
                                                <td style="text-align: center;"><?php echo table_ok_cancel_element($row->cause); ?></td>
                                                <td style="text-align: center;"><?php echo $semaforo; ?></td>
                                            </tr>
                                            <?php 
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
