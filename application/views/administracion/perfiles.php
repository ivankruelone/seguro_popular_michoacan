							<div class="row-fluid">
                                <div class="span12">
                            
                                    <p><?php echo anchor('administracion/perfil_nuevo', 'Agrega un perfil nuevo'); ?></p>

                                    <table id="proveedores-table" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave puesto</th>
                                                <th>Puesto</th>
                                                <th>Departamento</th>
                                                <th>Valuacion</th>
                                                <th>Editar</th>
                                                <th>Asignar permisos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $i = 1;
                                            foreach($query->result() as $row){
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row->clvpuesto; ?></td>
                                                <td><?php echo $row->puesto; ?></td>
                                                <td><?php echo $row->nivelUsuario; ?></td>
                                                <td><?php echo $row->valuacion; ?></td>
                                                <td><?php echo anchor('administracion/perfil_edita/'.$row->clvpuesto, 'Edita'); ?></td>
                                                <td><?php echo anchor('administracion/perfil_permisos/'.$row->clvpuesto, 'Permisos'); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            $i++;
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
