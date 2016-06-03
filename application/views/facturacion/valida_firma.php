<?php $row = $query->row(); ?>
							<div class="row-fluid">
                                <div class="span12">
                                    <table id="ventas-table" class="table table-bordered table-hover">
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
                                            
                                            $num++;

                                            if($row->remisionStatus == 1)
                                            {
                                                $imprime = anchor('facturacion/imprimirRemision/'.$row->remision.'/'.$row->clvsucursal, 'Imprimir <i class="icon-print"></i>', array('target' => '_blank'));
                                                $cancela = anchor('facturacion/eliminar_remision/'.$row->remision.'/'.$row->clvsucursal, 'Cancelar <i class="icon-minus"></i>', array('class' => 'eliminarRemision'));
                                                $color = null;
                                            }else
                                            {
                                                $imprime = 'CANCELADA';
                                                $cancela = 'CANCELADA';
                                                $color = ROJO_PASTEL;
                                            }

                                            if($row->firmada == 0)
                                            {
                                                $firmada = anchor('facturacion/valida_firma/' . $row->remision, 'Validar Firma');
                                            }else
                                            {
                                                $firmada = null;
                                            }
                                                
                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>;">
                                                <td><?php echo $row->remision; ?></td>
                                                <td><?php echo $row->perini; ?></td>
                                                <td><?php echo $row->perfin; ?></td>
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
                                                </tr>
                                                
                                                
                                            <?php 
                                    
                                                $sur = $sur + $row->cansur;
                                                $req = $req + $row->canreq;
                                                $importe = $importe + $row->importe;
                                                $iva_producto = $iva_producto + $row->iva_producto;
                                                $total = $total + $row->importe + $row->iva_producto;

                                                $servicio = $servicio + $row->servicio;
                                                $iva_servicio = $iva_servicio + $row->iva_servicio;
                                            
                                            
                                            
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
                                            </tr>
                                            <tr>
                                                <td colspan="9" style="text-align: right;">Servicio</td>
                                                <td style="text-align: right;"><?php echo number_format($sur, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio + $iva_servicio, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="12" style="text-align: right;">Total</td>
                                                <td style="text-align: right;"><?php echo number_format($total + $servicio + $iva_servicio, 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
								</div>
							</div>


							<div class="row-fluid">
                                <div class="span12">

                                    <?php echo MY_form_open('facturacion/guarda_validacion'); ?>

                                    <?php echo MY_form_input('observaciones', 'observaciones', 'Observaciones', 'text', 'Observaciones', 12, false); ?>

                                    <?php echo form_hidden('remision', $row->remision); ?>

                                    <?php echo MY_form_submit(); ?>
                                    
                                    <?php echo form_close(); ?>

                                </div>
                            </div>
