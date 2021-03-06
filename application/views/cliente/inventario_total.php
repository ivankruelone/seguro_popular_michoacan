                            <div class="row-fluid">
                                <div class="span12">
                                                                        
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <?php echo anchor('reportes/invtotal_excel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <?php if($valuacion == 1){ ?>
                                                <th style="text-align: right;">Importe</th>
                                                <th style="text-align: right;">IVA Producto</th>
                                                <th style="text-align: right;">Servicio</th>
                                                <th style="text-align: right;">IVA Servicio</th>
                                                <th style="text-align: right;">Subtotal</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $cantidad = 0;
                                                
                                            $importe = 0;
                                            $iva_producto = 0;
                                            $servicio = 0;
                                            $iva_servicio =0;
                                                
                                            foreach($query->result() as $row){

                                                $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;

                                            ?>
                                            <tr>
                                                <td><?php echo $row->id; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <?php if($valuacion == 1){ ?>
                                                <td style="text-align: right;"><?php echo number_format($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php 

                                            $cantidad = $cantidad + $row->cantidad;
                                            
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
                                                <td colspan="5" style="text-align: right;">Totales: </td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <?php if($valuacion == 1){ ?>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal_total, 2); ?></td>
                                                <?php } ?>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <?php if($valuacion == 1){ ?>
                                                <th style="text-align: right;">Importe</th>
                                                <th style="text-align: right;">IVA Producto</th>
                                                <th style="text-align: right;">Servicio</th>
                                                <th style="text-align: right;">IVA Servicio</th>
                                                <th style="text-align: right;">Subtotal</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                    </table>
                                    
                                </div>  
                            </div>
