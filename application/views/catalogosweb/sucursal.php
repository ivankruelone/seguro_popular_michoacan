							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="proveedores-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Domicilio</th>
                                                <th>Nivel de atención</th>
                                                <th>Jurisdicción</th>
                                                <th>Dia de pedido</th>
                                                <th>Tipo</th>
                                                <th>Accion</th>
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
                                                <td><?php echo $row->tiposucursalDescripcion; ?></td>
                                               <td><?php echo anchor('catalogosweb/sucursal_servicios/'.$row->clvsucursal, 'Ver Servicios'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
