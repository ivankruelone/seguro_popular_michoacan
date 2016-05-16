							<div class="row-fluid">
                                <div class="span12">

                                <?php

                                    foreach($tipos->result() as $tipo)
                                    {

                                        $query = $this->util->getSucursalByTipoSucursal($tipo->tiposucursal);



                                ?>

                                    <h2 style="color: red; "><?php echo $tipo->tiposucursalDescripcion; ?></h2>
                                    
                                    <table id="proveedores-table" class="table table-striped table-bordered table-hover">
                                        <caption>Número de sucursales: <?php echo $query->num_rows(); ?></caption>
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
                                                <td><?php echo anchor('administracion/sucursal_edita/'.$row->clvsucursal, 'Edita'); ?></td>
                                                <td><?php echo anchor('administracion/sucursal_servicios/'.$row->clvsucursal, 'Servicios'); ?></td>
                                            </tr>
                                            <tr>
                                                <td>ALT.</td>
                                                <td><?php echo $row->nombreSucursalPersonalizado; ?></td>
                                                <td><?php echo $row->domicilioSucursalPersonalizado; ?></td>
                                                <td colspan="2">DIR: <?php echo $row->director; ?></td>
                                                <td colspan="3">ADMON: <?php echo $row->administrador; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <?php

                                    }

                                    ?>
                            
                                </div>
                            </div>
