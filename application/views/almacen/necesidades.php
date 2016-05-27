                            <div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo anchor('reportes/necesidades_excel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>   
                                                                        
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
                                                <th style="text-align: right;">Excedentes</th>
                                                <th style="text-align: right;">Sobrantes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $bufferFarmacias = 0;
                                            $inventario = 0;
                                            $pedido = 0;
                                            $excedente = 0;
                                            $sobrante = 0;
                                                
                                            foreach($query->result() as $row){

                                                if($row->factor < 70 && $row->bufferFarmacias > 0)
                                                {
                                                    $color = VERDE;
                                                }elseif($row->factor > 150)
                                                {
                                                    $color = ROJO;
                                                }elseif($row->sobrante > 0)
                                                {
                                                    $color = AMBAR;
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
                                                <td style="text-align: right;"><?php echo number_format($row->bufferFarmacias, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->inventario, 0); ?></td>
                                                <td style="text-align: right;"><?php echo $row->factor; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->pedido, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->excedente, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->sobrante, 0); ?></td>
                                            </tr>
                                            <?php 

                                            $bufferFarmacias = $bufferFarmacias + $row->bufferFarmacias;
                                            $inventario = $inventario + $row->inventario;
                                            $pedido = $pedido + $row->pedido;
                                            $excedente = $excedente + $row->excedente;
                                            $sobrante = $sobrante + $row->sobrante;
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" style="text-align: right;">Totales: </td>
                                                <td style="text-align: right;"><?php echo number_format($bufferFarmacias, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($inventario, 0); ?></td>
                                                <td style="text-align: right;"></td>
                                                <td style="text-align: right;"><?php echo number_format($pedido, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($excedente, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($sobrante, 0); ?></td>
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
                                                <th style="text-align: right;">Excedentes</th>
                                                <th style="text-align: right;">Sobrantes</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    
                                </div>  
                            </div>
