                                    <table id="ventas-table" class="table table-bordered table-hover">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th style="text-align: right;">Remision</th>
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
                                                <th>Servicio</th>
                                                <th>IVA Servicio</th>
                                                <th>Total Servicio</th>
                                                <th>Total</th>
                                                <th>Imprimir</th>
                                                <th>Eliminar</th>
                                                <th>Firmada</th>
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

                                            if(strlen($row->observacionesFirma) > 0)
                                            {
                                                $observacionesFirma = '<i class="red icon-comment" title="'.$row->observacionesFirma.'"> Observaciones </i> ';
                                            }else
                                            {
                                                $observacionesFirma = null;
                                            }

                                            if($row->remisionStatus == 1)
                                            {
                                                $imprime = anchor('facturacion/imprimirRemision/'.$row->remision.'/'.$row->clvsucursal, 'Imprimir <i class="icon-print"></i>', array('target' => '_blank'));
                                                if($row->firmada == 1)
                                                {
                                                    $cancela = null;
                                                }else
                                                {
                                                    $cancela = anchor('facturacion/eliminar_remision/'.$row->remision.'/'.$row->clvsucursal, 'Cancelar <i class="icon-minus"></i>', array('class' => 'eliminarRemision'));
                                                }
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
                                                $firmada = $observacionesFirma;
                                            }
                                                
                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>;">
                                                <td style="text-align: right;"><?php echo $row->remision; ?></td>
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
                                                <td style="text-align: right;"><?php echo number_format ($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->servicio + $row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio, 2); ?></td>
                                                <td style="text-align: center;"><?php echo $imprime; ?></td>
                                                <td style="text-align: center;"><?php echo $cancela; ?></td>
                                                <td style="text-align: center;"><?php echo $firmada; ?></td>
                                            </tr>

                                            <?php

                                            if($row->remisionStatus == 1 && $row->firmada == 1)
                                            {

                                                $rem = $this->facturacion_model->getFacturasByRemision($row->remision);

                                                if($row->facturada == 0 && $rem->num_rows() == 0)
                                                {
                                                    $facturar = anchor('facturacion/facturar/' . $row->remision, 'Facturar');
                                                }else
                                                {
                                                    $facturar = null;
                                                }

                                            ?>

                                            <tr style="background-color: <?php echo $color; ?>;">
                                                <td><?php echo $facturar; ?></td>
                                                <td colspan="15">
                                                    <table class="table table-condensed" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Tipo</th>
                                                                <th>Factura ID</th>
                                                                <th># Factura</th>
                                                                <th>XML</th>
                                                                <th>PDF</th>
                                                                <th>Factura Producto</th>
                                                                <th>Folio fiscal</th>
                                                                <th style="text-align: right;">Total Factura</th>
                                                                <th style="text-align: right;">Iva Factura</th>
                                                                <th style="text-align: center;">Status</th>
                                                                <th style="text-align: center;">Fecha Factura</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            foreach ($rem->result() as $r) {
                                                                # code...
                                                            $descargaXML = anchor('facturacion/descargaXML/'.$r->remision_facturaID, 'Descarga XML <i class="icon-download bigger-130"> </i>');
                                                            $descargaPDF = anchor('facturacion/descargaPDF/'.$r->remision_facturaID, 'Descarga PDF <i class="icon-download bigger-130"> </i>');

                                                            if($r->statusFactura == 1)
                                                            {
                                                                $statusFactura = 'ACTIVA';
                                                                $colorFactura = null;
                                                            }else
                                                            {
                                                                $statusFactura = 'CANCELADA';
                                                                $colorFactura = ROJO_PASTEL;
                                                            }

                                                            ?>
                                                            <tr style="background-color: <?php echo $colorFactura; ?>;">
                                                                <td><?php echo $r->tipoFacturaDescripcion; ?></td>
                                                                <td><?php echo $r->f_id; ?></td>
                                                                <td><?php echo $r->numfac; ?></td>
                                                                <td><?php echo $descargaXML; ?></td>
                                                                <td><?php echo $descargaPDF; ?></td>
                                                                <td><?php echo $r->facturaProducto; ?></td>
                                                                <td><?php echo $r->uuid; ?></td>
                                                                <td style="text-align: right;"><?php echo number_format($r->totalFactura, 2); ?></td>
                                                                <td style="text-align: right;"><?php echo number_format($r->ivaFactura, 2); ?></td>
                                                                <td style="text-align: center;"><?php echo $statusFactura; ?></td>
                                                                <td style="text-align: center;"><?php echo $r->fechaFactura; ?></td>
                                                            </tr>

                                                            <?php

                                                            }

                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <?php

                                            }

                                            ?>
                                                
                                                
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
                                                <td colspan="8" style="text-align: right;">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($req, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($sur, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;" style="font-size: large;"><?php echo number_format($total, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio + $iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($total + $servicio + $iva_servicio, 2); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th style="text-align: right;">Remision</th>
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
                                                <th>Servicio</th>
                                                <th>IVA Servicio</th>
                                                <th>Total Servicio</th>
                                                <th>Total</th>
                                                <th>Imprimir</th>
                                                <th>Eliminar</th>
                                                <th>Firmada</th>
                                            </tr>
                                        </thead>
                                    </table>
