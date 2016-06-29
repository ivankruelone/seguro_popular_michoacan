 							<div class="row-fluid">
									<div class="span12">
                                    
                                        <?php echo anchor('reportes/programaExcel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>

                                    </div>
                            </div>

                             <div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave</th>
                                                <th>DESCRIPCION</th>
                                                <th>POBLACION ABIERTA</th>
                                                <th>SEGURO POPULAR</th>
                                                <th>PROSPERA</th>
                                                <th>SEGURO MEDICO SIGLO XXI</th>
                                                <th>TOTAL</th>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <th>Precio Unitario</th>
                                                <th>Importe</th>
                                                <th>IVA</th>
                                                <th>Servicio</th>
                                                <th>IVA Servicio</th>
                                                <th>Subtotal</th>
                                                <?php }?>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $num = 0;
                                            $pa = 0;
                                            $sp = 0;
                                            $pr = 0;
                                            $sm = 0;
                                            $to = 0;
                                            
                                            $tImporte = 0;
                                            $tIVA = 0;
                                            $tServicio = 0;
                                            $tServicioIVA = 0;
                                            $total = 0;
                                            $importe = 0;
                                            $iva_producto = 0;
                                            $servicio = 0;
                                            $iva_servicio = 0;
                                            $total = 0;
                                            
                                            foreach($query->result() as $row){

                                            	$subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                                                
                                                
                                                $num++;    
                                                
                                            
//r.fecha, r.cvepaciente, r.cveservicio, r.nombremedico, r.folioreceta, r.cvearticulo, r.descripcion, 
//r.tiporequerimiento, r.cantidadsurtida, a.precioven, r.cantidadsurtida * a.precioven as total, 
//a.preciocon, a.precioven - a.preciosinser as importedescuento, (a.precioven - a.preciosinser) * r.cantidadsurtida as subtotaldescuento,
// a.preciosinser * r.cantidadsurtida as importecondescuento, a.iva, a.preciosinser * r.cantidadsurtida + iva as total                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo ($row->completo); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->pa, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->sp, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->pr, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->sm, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->total, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td style="text-align: right;"><?php echo number_format ($row->precioven, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($subtotal, 2); ?></td>
                                                <?php }?>
                                                </tr>
                                                
                                                
                                            <?php 
                                            
                                            $pa = $pa + $row->pa;
                                            $sp = $sp + $row->sp;
                                            $pr = $pr + $row->pr;
                                            $sm = $sm + $row->sm;
                                            $to = $to + $row->total;
                                            $importe = $importe + $row->importe;
                                            $iva_producto = $iva_producto + $row->iva_producto;
                                            $servicio = $servicio + $row->servicio;
                                            $iva_servicio = $iva_servicio + $iva_servicio;
                                            $total = $total + $subtotal;
                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td style="text-align: right;" colspan="3">Totales</td>
                                                <td style="text-align: right;" id="pa"><?php echo number_format ($pa, 0); ?></td>
                                                <td style="text-align: right;" id="sp"><?php echo number_format ($sp, 0); ?></td>
                                                <td style="text-align: right;" id="pr"><?php echo number_format ($pr, 0); ?></td>
                                                <td style="text-align: right;" id="sm"><?php echo number_format ($sm, 0); ?></td>
                                                <td style="text-align: right;" id="to"><?php echo number_format ($to, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td>&nbsp;</td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
                                                <?php }?>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave</th>
                                                <th>Descripcion</th>
                                                <th>POBLACION ABIERTA</th>
                                                <th>SEGURO POPULAR</th>
                                                <th>PROSPERA</th>
                                                <th>SEGURO MEDICO SIGLO XXI</th>
                                                <th>TOTAL</th>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <th>Precio Unitario</th>
                                                <th>Importe</th>
                                                <th>IVA</th>
                                                <th>Servicio</th>
                                                <th>IVA Servicio</th>
                                                <th>Subtotal</th>
                                                <?php }?>
                                             </tr>
                                        </tfoot>
                                    </table>
                                    
                            
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                
                                <div id="grafica"></div>
                                
                                </div>
                            </div>
