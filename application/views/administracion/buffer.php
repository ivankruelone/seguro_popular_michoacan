							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="proveedores-table" class="table table-striped table-bordered table-hover">
                                    <caption>Número de Farmacias: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Domicilio</th>
                                                <th>Nivel de atención</th>
                                                <th>Jurisdicción</th>
                                                <th>Dia de pedido</th>
                                                <th colspan="2" style="text-align: center;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($query->result() as $row){?>
                                            <tr>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->calle . ', ' . $row->colonia . ', C. P. ' . $row->cp . ', ' . $row->municipio; ?></td>
                                                <td><?php echo $row->nivelatenciondescripcion; ?></td>
                                                <td><?php echo $row->jurisdiccion; ?></td>
                                                <td><?php echo $row->diaDescripcion; ?></td>
                                                <td><?php echo anchor('administracion/buffer_edita/0/'.$row->clvsucursal, 'Buffer Medicamento'); ?></td>
                                                <td><?php echo anchor('administracion/buffer_edita/1/'.$row->clvsucursal, 'Buffer Material de Curación'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
