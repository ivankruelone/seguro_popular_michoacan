							<div class="row-fluid">
                                <div class="span12">

                                    <table cellpadding="2" cellspacing="2">
                                        <tr>
                                            <td style="background-color: <?php echo VERDE_PASTEL; ?>; width: 50px;"></td>
                                            <td style="font-weight: bold;">ENTRADA</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: <?php echo ROJO_PASTEL; ?>; width: 50px;"></td>
                                            <td style="font-weight: bold;">SALIDA</td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: <?php echo AMBAR_PASTEL; ?>; width: 50px;"></td>
                                            <td style="font-weight: bold;">NEUTRO</td>
                                        </tr>
                                    </table>                                    
                                    
                                    <table class="table table-condensed table-hover">
                                        <caption>Registros encontrados: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Lote</th>
                                                <th>Caducidad</th>
                                                <th>Inv. Ant.</th>
                                                <th>Inv. Act.</th>
                                                <th style="text-align: right;">Cantidad</th>
                                                <th>Tipo</th>
                                                <th>Subtipo</th>
                                                <th>Receta</th>
                                                <th>Movimiento</th>
                                                <th>Fecha</th>
                                                <th>Ubicacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            $cantidad = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                                if($row->receta > 0)
                                                {
                                                    $receta = anchor('captura/ver/'.$row->receta, $row->receta);
                                                }else{
                                                    $receta = $row->receta;
                                                }
                                            
                                                if($row->movimientoID > 0)
                                                {
                                                    $movimiento = anchor('movimiento/captura/'.$row->movimientoID, $row->movimientoID);
                                                }else{
                                                    $movimiento = $row->movimientoID;
                                                }

                                                if($row->tipoMovimiento == 1)
                                                {
                                                    $color = VERDE_PASTEL;
                                                }elseif($row->tipoMovimiento == 2)
                                                {
                                                    $color = ROJO_PASTEL;
                                                }elseif($row->tipoMovimiento == 3)
                                                {
                                                    $color = AMBAR_PASTEL;
                                                }else
                                                {
                                                    $color = '';
                                                }

                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>;">
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo $row->lote; ?></td>
                                                <td><?php echo $row->caducidad; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidadOld, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidadNew, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <td><?php echo $row->tipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->subtipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $receta; ?></td>
                                                <td><?php echo $movimiento; ?></td>
                                                <td><?php echo $row->fechaKardex; ?></td>
                                                <td><?php echo $row->ubicacion_anterior . ' - ' . $row->ubicacion_actual; ?></td>
                                            </tr>
                                            <?php 
                                            
                                                $cantidad = $cantidad + $row->cantidad;
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8">Total</td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
								</div>	
                            </div>
