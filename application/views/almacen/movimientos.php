							<div class="row-fluid">
                                <div class="span12">


                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>MovimientoID</th>
                                                <th>Referencia</th>
                                                <th>Tipo Movimiento</th>
                                                <th>Subtipo Movimiento</th>
                                                <th>Status</th>
                                                <th>Usuario</th>
                                                <th>Fecha</th>
                                                <th style="text-align: right;">Piezas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            $cantidad = 0;
                                            
                                            foreach($query->result() as $row){

                                                if($row->statusMovimiento == 0)
                                                {
                                                    $color = AMBAR_PASTEL;
                                                }elseif($row->statusMovimiento == 2){
                                                    $color = ROJO_PASTEL;
                                                }else
                                                {
                                                    $color = null;
                                                }
                                                
                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>">
                                                <td><?php echo $row->movimientoID; ?></td>
                                                <td><?php echo $row->referencia; ?></td>
                                                <td><?php echo $row->tipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->subtipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->statusMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->nombreusuario; ?></td>
                                                <td><?php echo $row->fechaCierre; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->piezas, 0); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>