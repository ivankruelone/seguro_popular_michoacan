                            <div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="table" class="table table-bordered table-hover">
                                        <caption>Registros: <?php echo count($query); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>ID Movimiento</th>
                                                <th>Referencia</th>
                                                <th>Fecha</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Observaciones</th>
                                                <th>Enviadas</th>
                                                <th>Recibidas</th>
                                                <th>Por recibir</th>
                                                <th>Ver detalle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $piezas = 0;
                                            $enviadas = 0;
                                            $recibidas = 0;

                                            foreach($query as $row){

                                                if($row->enviadas <> $row->recibidas)
                                                {
                                                    $color = ROJO_PASTEL;
                                                }else
                                                {
                                                    $color = null;
                                                }

                                            ?>
                                            <tr style="background-color: <?php echo $color;?>;">
                                                <td><?php echo $row->movimientoID; ?></td>
                                                <td><?php echo $row->referencia; ?></td>
                                                <td><?php echo $row->fechaCierre; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->observaciones; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->enviadas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->recibidas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->piezas, 0); ?></td>
                                                <td><?php echo anchor('almacen/transito_detalle/'.$row->referencia, 'Ver detalle'); ?></td>
                                            </tr>
                                            <?php 

                                            $piezas = $piezas + $row->piezas;
                                            $enviadas = $enviadas + $row->enviadas;
                                            $recibidas = $recibidas + $row->recibidas;

                                            } 


                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" style="text-align: right;">Total de piezas: </td>
                                                <td style="text-align: right;"><?php echo number_format($enviadas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($recibidas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                            
                                </div>
                            </div>
