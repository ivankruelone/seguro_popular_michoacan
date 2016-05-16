							<div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Remision</th>
                                                <th>Fecha inicial</th>
                                                <th>Fecha final</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Suministro</th>
                                                <th>Requerimiento</th>
                                                <th>Programa</th>
                                                <th>Cantidad solicitada</th>
                                                <th>Cantidad surtida</th>
                                                <th>Importe</th>
                                                <th>IVA</th>
                                                <th>Subtotal</th>
                                                <th>Hacer remision</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $num = 0;
                                            $sur = 0;
                                            $req = 0;
                                            $total = 0;
                                            $iva_producto = 0;
                                            $importe = 0;

                                            $servicio = 0;
                                            $iva_servicio = 0;
                                            
                                            foreach($query->result() as $row){
                                            $num++;    
                                                
                                            
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $fecha1; ?></td>
                                                <td><?php echo $fecha2; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo ($row->descsucursal); ?></td>
                                                <td><?php echo $row->suministro; ?></td>
                                                <td><?php echo ($row->requerimiento); ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->canreq, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->cansur, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe + $row->iva_producto, 2); ?></td>
                                                <td><?php echo anchor('facturacion/remisionar/'.$fecha1.'/'.$fecha2.'/'.$row->clvsucursal.'/'.$row->iva.'/'.$row->tiporequerimiento.'/'.$row->idprograma, 'Generar remisión', array('class' => 'generaRemision')); ?></td>
                                                </tr>
                                                
                                                
                                            <?php 
                                    
                                                $sur = $sur + $row->cansur;
                                                $req = $req + $row->canreq;
                                                $importe = $importe + $row->importe;
                                                $iva_producto = $iva_producto + $row->iva_producto;
                                                $total = $total + $row->importe + $row->iva_producto;

                                                $servicio = $servicio + $row->servicio;
                                                $iva_servicio = $iva_servicio + $row->iva_servicio;
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" style="text-align: right;">Productos</td>
                                                <td style="text-align: right;"><?php echo number_format($req, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($sur, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;" style="font-size: large;"><?php echo number_format($total, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9" style="text-align: right;">Servicio</td>
                                                <td style="text-align: right;"><?php echo number_format($sur, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio + $iva_servicio, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="12" style="text-align: right;">Total</td>
                                                <td style="text-align: right;"><?php echo number_format($total + $servicio + $iva_servicio, 2); ?></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                            
                                </div>
                            </div>
