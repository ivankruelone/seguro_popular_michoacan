							<div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Folio Receta</th>
                                                <th>Clave Paciente</th>
                                                <th>Nombre</th>
                                                <th>Paterno</th>
                                                <th>Materno</th>
                                                <th>Edad</th> 
                                                <th>Fecha Consulta</th>
                                                <th>Cobertura</th>
                                                <th>Imprimir</th>
                                                <th>Cancelar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $num=0;
                                            
                                            foreach($query->result() as $row){
                                            $num++;    
                                            
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->recetaID; ?></td>
                                                <td><?php echo $row->cvepaciente; ?></td>
                                                <td><?php echo $row->nombre; ?></td>
                                                <td><?php echo $row->apaterno; ?></td>
                                                <td><?php echo $row->amaterno; ?></td>
                                                <td><?php echo $row->edad; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td><?php echo anchor('medico/imprimeReceta/'.$row->recetaID, 'Imprimir', array('target' => '_blank')); ?></td>
                                                <td><?php echo anchor('medico/cancelaReceta/'.$row->recetaID, 'Cancelar', array('class' => 'cancela_receta')); ?></td>

                                            </tr>
                                                
                                                
                                            <?php 
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
                            
                                </div>
                            </div>
