							<div class="row-fluid">
                                <div class="span12">

                                	<?php echo anchor('jurisdiccion/reporteExcel', '<i class="icon-save bigger-160"></i> Excel', array('class' => 'btn btn-app btn-yellow btn-mini'));?>

                                    <table id="ventas-table" class="table table-bordered table-hover">
                                    	<caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: right;">Movimiento</th>
                                                <th>Folio</th>
                                                <th>Colectivo</th>
                                                <th>Fecha</th>
                                                <th># Jur</th>
                                                <th>Jurisd.</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Programa</th>
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
                                            $subtotal = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                            
                                            ?>
                                            <tr>
                                                <td style="text-align: right;"><?php echo anchor('jurisdiccion/reporte_detalle/' . $row->movimientoID, $row->movimientoID); ?></td>
                                                <td><?php echo $row->referencia; ?></td>
                                                <td><?php echo $row->colectivo; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->numjurisd; ?></td>
                                                <td><?php echo $row->jurisdiccion; ?></td>
                                                <td><?php echo $row->clvsucursalReferencia; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->subtotal, 2); ?></td>
                                            </tr>

                                                
                                            <?php 
                                    
                                                $piezas = $piezas + $row->piezas;
                                                $importe = $importe + $row->importe;
                                                $iva_producto = $iva + $row->iva_producto;

                                                $servicio = $servicio + $row->servicio;
                                                $iva_servicio = $iva_servicio + $row->iva_servicio;

                                                $subtotal = $subtotal + $row->subtotal;
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" style="text-align: right;">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
