                            <div class="row-fluid">
                                <div class="span12">
                                                                        
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <?php if($valuacion == 1){ ?>
                                                <th style="text-align: right;">Importe</th>
                                                <th style="text-align: right;">IVA Producto</th>
                                                <th style="text-align: right;">Servicio</th>
                                                <th style="text-align: right;">IVA Servicio</th>
                                                <th style="text-align: right;">Subtotal</th>
                                                <?php } ?>
                                                <th style="text-align: center;">Ver inventario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $cantidad = 0;
                                            $n = 1;

                                            $importe = 0;
                                            $iva_producto = 0;
                                            $servicio = 0;
                                            $iva_servicio =0;
                                                
                                            foreach($query->result() as $row){

                                                $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $n; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <?php if($valuacion == 1){ ?>
                                                <td style="text-align: right;"><?php echo number_format($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                                <?php } ?>
                                                <td style="text-align: center;"><?php echo anchor('cliente/inventario_detalle/' . $row->clvsucursal, 'Ver detalle'); ?></td>
                                            </tr>
                                            <?php 

                                            $cantidad = $cantidad + $row->cantidad;
                                            $n++;

                                            $importe = $importe + $row->importe;
                                            $iva_producto = $iva_producto + $row->iva_producto;
                                            $servicio = $servicio + $row->servicio;
                                            $iva_servicio = $iva_servicio + $row->iva_servicio;
                                            
                                            }

                                            $subtotal_total = $importe + $iva_producto + $servicio + $iva_servicio;
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" style="text-align: right;">Totales: </td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <?php if($valuacion == 1){ ?>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal_total, 2); ?></td>
                                                <?php } ?>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <?php if($valuacion == 1){ ?>
                                                <th style="text-align: right;">Importe</th>
                                                <th style="text-align: right;">IVA Producto</th>
                                                <th style="text-align: right;">Servicio</th>
                                                <th style="text-align: right;">IVA Servicio</th>
                                                <th style="text-align: right;">Subtotal</th>
                                                <?php } ?>
                                                <th style="text-align: center;">Ver inventario</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    
                                </div>  
                            </div>
