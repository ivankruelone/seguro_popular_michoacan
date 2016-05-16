							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID Movimiento</th>
                                                <th>Referencia</th>
                                                <th>Fecha</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Observaciones</th>
                                                <th>Piezas</th>
                                                <th>Ver detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $piezas = 0;

                                            foreach($query as $row){

                                            ?>
                                            <tr>
                                                <td><?php echo $row->movimientoID; ?></td>
                                                <td><?php echo $row->referencia; ?></td>
                                                <td><?php echo $row->fechaCierre; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->observaciones; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->piezas, 0); ?></td>
                                                <td><?php echo anchor('almacen/transito_detalle/'.$row->referencia, 'Ver detalle'); ?></td>
                                            </tr>
                                            <?php 

                                            $piezas = $piezas + $row->piezas;

                                            } 


                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" style="text-align: right;">Total de piezas: </td>
                                                <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                            
                                </div>
                            </div>
