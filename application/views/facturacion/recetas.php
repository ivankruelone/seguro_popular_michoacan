                                    <table id="ventas-table" class="table table-bordered table-hover">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: right;"># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Piezas</th>
                                                <th>Importe</th>
                                                <th>IVA</th>
                                                <th>Servicio</th>
                                                <th>IVA Servicio</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $piezas = 0;
                                            $importe = 0;
                                            $iva = 0;
                                            $servicio = 0;
                                            $iva_servicio = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                            
                                            ?>
                                            <tr>
                                                <td style="text-align: right;"><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe + $row->iva + $row->servicio + $row->iva_servicio, 2); ?></td>
                                            </tr>

                                                
                                            <?php 
                                    
                                                $piezas = $piezas + $row->piezas;
                                                $importe = $importe + $row->importe;
                                                $iva = $iva + $row->iva;

                                                $servicio = $servicio + $row->servicio;
                                                $iva_servicio = $iva_servicio + $row->iva_servicio;

                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" style="text-align: right;">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio + $iva_servicio + $importe + $iva, 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
