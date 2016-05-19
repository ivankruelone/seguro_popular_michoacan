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
                                                <th class="center">Valuacion</th>
                                                <th class="center">Consulta</th>
                                                <th class="center">Editar</th>
                                                <th class="center">Asignar permisos</th>
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
                                                <td class="center"><?php echo $row->valuacion; ?></td>
                                                <td class="center"><?php echo $row->consulta; ?></td>
                                                <td class="center"><?php echo anchor('administracion/perfil_edita/'.$row->clvpuesto, 'Edita'); ?></td>
                                                <td class="center"><?php echo anchor('administracion/perfil_permisos/'.$row->clvpuesto, 'Permisos'); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            $i++;
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
