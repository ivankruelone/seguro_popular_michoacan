							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="proveedores-table" class="table table-striped table-bordered table-hover">
                                        <caption>Pedidos: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Domicilio</th>
                                                <th>Tipo</th>
                                                <th>Nivel de atención</th>
                                                <th>Jurisdicción</th>
                                                <th>Dia de pedido</th>
                                                <th colspan="2">Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            foreach($query->result() as $row){

                                                if($row->movimientoID == 0)
                                                {

                                                    $l1 = anchor('almacen/genera_pedido/'.$row->clvsucursal, 'Genera pedido');

                                                }else
                                                {

                                                    $l1 = anchor('movimiento/guia/'.$row->movimientoID.'/2/13', 'Guia', array('target' => '_blank'));

                                                }

                                            ?>
                                            <tr>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->calle . ', ' . $row->colonia . ', C. P. ' . $row->cp . ', ' . $row->municipio; ?></td>
                                                <td><?php echo $row->tiposucursalDescripcion; ?></td>
                                                <td><?php echo $row->nivelatenciondescripcion; ?></td>
                                                <td><?php echo $row->jurisdiccion; ?></td>
                                                <td><?php echo $row->diaDescripcion; ?></td>
                                                <td><?php echo anchor('almacen/inventario_vs_buffer/'.$row->clvsucursal, 'Inventario vs Buffer'); ?></td>
                                                <td><?php echo $l1; ?></td>
                                            </tr>
                                            <?php 

                                            } 

                                            ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
