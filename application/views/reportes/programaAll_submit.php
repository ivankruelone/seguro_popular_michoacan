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
                                                <th>OPORTUNIDADES</th>
                                                <th>PROGRAMAS PRIORITARIOS</th>
                                                <th>BENEFICIENCIA PUBLICA</th>
                                                <th>ADULTO MAYOR</th>
                                                <th>PAQUETES</th>
                                                <th>INTRAHOSPITALARIOS</th>
                                                <th>CLINICA DE HERIDAS</th>
                                                <th>TOTAL</th>
                                                <?php if($this->session->userdata('superuser') == 1){?>
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
                                            $op = 0;
                                            $pp = 0;
                                            $bp = 0;
                                            $am = 0;
                                            $pq = 0;
                                            $sm = 0;
                                            $ch = 0;
                                            $to = 0;
                                            
                                            $tImporte = 0;
                                            $tIVA = 0;
                                            $tServicio = 0;
                                            $tServicioIVA = 0;
                                            $total = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                                $piezas = $row->todo;
                                                
                                                $importe = $row->preciosinser * $piezas;
                                                    
                                                if((int)$row->tipoprod == 1)
                                                {
                                                    $iva = $row->preciosinser * $piezas * .16;
                                                }else{
                                                    $iva = 0;
                                                }
                                                
                                                $servicio = $piezas * 8.55;
                                                $servicio_iva = $servicio * .16;
                                                $subtotal = $importe + $iva + $servicio + $servicio_iva;
                                                
                                                $tImporte = $tImporte + $importe;
                                                $tIVA = $tIVA + $iva;
                                                $tServicio = $tServicio + $servicio;
                                                $tServicioIVA = $tServicioIVA + $servicio_iva;
                                                $total = $total + $subtotal;
                                                
                                                $num++;    
                                                
                                            
//r.fecha, r.cvepaciente, r.cveservicio, r.nombremedico, r.folioreceta, r.cvearticulo, r.descripcion, 
//r.tiporequerimiento, r.cantidadsurtida, a.precioven, r.cantidadsurtida * a.precioven as total, 
//a.preciocon, a.precioven - a.preciosinser as importedescuento, (a.precioven - a.preciosinser) * r.cantidadsurtida as subtotaldescuento,
// a.preciosinser * r.cantidadsurtida as importecondescuento, a.iva, a.preciosinser * r.cantidadsurtida + iva as total                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo utf8_encode($row->completo); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->pa, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->sp, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->op, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->pp, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->bp, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->am, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->pq, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->sm, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->ch, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($row->todo, 0); ?></td>
                                                <?php if($this->session->userdata('superuser') == 1){?>
                                                <td style="text-align: right;"><?php echo number_format ($row->preciosinser, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($servicio_iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($subtotal, 2); ?></td>
                                                <?php }?>
                                                </tr>
                                                
                                                
                                            <?php 
                                            
                                            $pa = $pa + $row->pa;
                                            $sp = $sp + $row->sp;
                                            $op = $op + $row->op;
                                            $pp = $pp + $row->pp;
                                            $bp = $bp + $row->bp;
                                            $am = $am + $row->am;
                                            $pq = $pq + $row->pq;
                                            $sm = $sm + $row->sm;
                                            $ch = $ch + $row->ch;
                                            $to = $to + $row->todo;

                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td style="text-align: right;" colspan="3">Totales</td>
                                                <td style="text-align: right;" id="pa"><?php echo number_format ($pa, 0); ?></td>
                                                <td style="text-align: right;" id="sp"><?php echo number_format ($sp, 0); ?></td>
                                                <td style="text-align: right;" id="op"><?php echo number_format ($op, 0); ?></td>
                                                <td style="text-align: right;" id="pp"><?php echo number_format ($pp, 0); ?></td>
                                                <td style="text-align: right;" id="bp"><?php echo number_format ($bp, 0); ?></td>
                                                <td style="text-align: right;" id="am"><?php echo number_format ($am, 0); ?></td>
                                                <td style="text-align: right;" id="pq"><?php echo number_format ($pq, 0); ?></td>
                                                <td style="text-align: right;" id="sm"><?php echo number_format ($sm, 0); ?></td>
                                                <td style="text-align: right;" id="ch"><?php echo number_format ($ch, 0); ?></td>
                                                <td style="text-align: right;" id="to"><?php echo number_format ($to, 0); ?></td>
                                                <?php if($this->session->userdata('superuser') == 1){?>
                                                <td>&nbsp;</td>
                                                <td style="text-align: right;"><?php echo number_format($tImporte, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($tIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($tServicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($tServicioIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
                                                <?php }?>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave</th>
                                                <th>Descripcion</th>
                                                <th>POBLACION ABIERTA</th>
                                                <th>SEGURO POPULAR</th>
                                                <th>OPORTUNIDADES</th>
                                                <th>PROGRAMAS PRIORITARIOS</th>
                                                <th>BENEFICIENCIA PUBLICA</th>
                                                <th>ADULTO MAYOR</th>
                                                <th>PAQUETES</th>
                                                <th>SEGURO MEDICO SIGLO XXI</th>
                                                <th>CLINICA DE HERIDAS</th>
                                                <th>TOTAL</th>
                                                <?php if($this->session->userdata('superuser') == 14){?>
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
