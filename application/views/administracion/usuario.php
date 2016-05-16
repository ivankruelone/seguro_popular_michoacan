							<div class="row-fluid">
                                <div class="span12">
                            
                                    <p><?php echo anchor('administracion/usuario_nuevo', 'Agrega un usuario'); ?></p>

                                    <table id="proveedores-table" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Puesto</th>
                                                <th>Departamento</th>
                                                <th>Usuario</th>
                                                <th>Password</th>
                                                <th>Valuación</th>
                                                <th>Status</th>
                                                <th>Sucursal</th>
                                                <th colspan="2">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $i = 1;
                                            foreach($query->result() as $row){

                                                if($row->activo == 0)
                                                {
                                                    $color = ROJO;
                                                }else
                                                {
                                                    $color = '';
                                                }
                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>; ">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row->nombreusuario; ?></td>
                                                <td><?php echo $row->puesto; ?></td>
                                                <td><?php echo $row->nivelUsuario; ?></td>
                                                <td><?php echo $row->clvusuario; ?></td>
                                                <td><?php echo '*********'; ?></td>
                                                <td><?php echo $row->valuacionDescripcion; ?></td>
                                                <td class="center"><?php echo $row->estaactivo ?></td>
                                                <td><?php echo $row->clvsucursal .' - ' . $row->descsucursal; ?></td>
                                                <td><?php echo anchor('administracion/usuario_edita/'.$row->usuario, 'Edita'); ?></td>
                                                <td><?php echo anchor('administracion/usuario_permisos/'.$row->usuario, 'Permisos'); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            $i++;
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
