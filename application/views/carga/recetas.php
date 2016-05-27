							<div class="row-fluid">
                                <div class="span12">
                                
                                <?php
                                
                                echo form_open('carga/recetas_submit', array('enctype' => 'multipart/form-data'));
                                
                                ?>
                                
                                Please choose a file: <input type="file" name="uploadFile" /><br />
                                <input type="submit" value="Subir archivo" />
                                
                                <?php
                                
                                echo form_close();
                                
                                ?>
                                
                                
                                </div>
                            </div>
                            
                            <div class="row-fluid">
                                <div class="span12">
                                
                                <table class="table">
                                    <thead>
                                        <th>Subida</th>
                                        <th># Suc.</th>
                                        <th>Sucursal</th>
                                        <th>Fecha inicial</th>
                                        <th>Fecha final</th>
                                        <th>Requeridas</th>
                                        <th>$surtidas</th>
                                        <th>Detalle</th>
                                        <th>Guardar en principal</th>
                                        <th>Borrar</th>
                                    </thead>
                                    
                                    <tbody>
                                        <?php foreach($query->result() as $row){?>
                                        <tr>
                                            <td><?php echo $row->subida; ?></td>
                                            <td><?php echo $row->suc; ?></td>
                                            <td><?php echo $row->descsucursal; ?></td>
                                            <td><?php echo $row->minimo; ?></td>
                                            <td><?php echo $row->maximo; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($row->sumreq, 0); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($row->sumsur, 0); ?></td>
                                            <td><?php echo anchor('carga/subida_detalle/' . $row->subida, 'Detalle'); ?></td>
                                            <td><?php echo anchor('carga/subida_cargar/' . $row->subida, 'Carga a principal', array('class' => 'cargar')); ?></td>
                                            <td><?php echo anchor('subida_cargar/subida_eliminar/' . $row->subida, 'Eliminar', array('class' => 'eliminar')); ?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                
                                </div>
                            </div>