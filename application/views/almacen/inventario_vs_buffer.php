							<div class="row-fluid">
                                <div class="span12">
                                                                        
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="text-align: right;">Buffer</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <th style="text-align: right;">Factor Buffer</th>
                                                <th style="text-align: right;">Pedido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            $buffer = 0;
                                            $inventario = 0;
                                            $pedido = 0;
                                                
                                            foreach($query->result() as $row){

                                                if($row->factor < 70 && $row->buffer > 0)
                                                {
                                                    $color = VERDE;
                                                }elseif($row->factor > 150)
                                                {
                                                    $color = ROJO;
                                                }else
                                                {
                                                    $color = '';
                                                }
                                                

                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>">
                                                <td><?php echo $row->id; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->buffer, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->inventario, 0); ?></td>
                                                <td style="text-align: right;"><?php echo $row->factor; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->pedido, 0); ?></td>
                                            </tr>
                                            <?php 

                                            $buffer = $buffer + $row->buffer;
                                            $inventario = $inventario + $row->inventario;
                                            $pedido = $pedido + $row->pedido;
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" style="text-align: right;">Totales: </td>
                                                <td style="text-align: right;"><?php echo number_format($buffer, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($inventario, 0); ?></td>
                                                <td style="text-align: right;"></td>
                                                <td style="text-align: right;"><?php echo number_format($pedido, 0); ?></td>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="text-align: right;">Buffer</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <th style="text-align: right;">Factor Buffer</th>
                                                <th style="text-align: right;">Pedido</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    
								</div>	
                            </div>
