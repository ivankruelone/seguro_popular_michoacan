 							<div class="row-fluid">
                                <div class="span12">
                                
                                <?php echo anchor('reportes/Medico_Excel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>

                                
                                <h3><?php echo $cveMedico . ' - ' . $medico;?></h3>
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>FECHA</th>
                                                <th>FOLIO</th>
                                                <th>PROGRAMA</th>
                                                <th>CLAVE PACIENTE</th>
                                                <th>PACIENTE</th>
                                                <th>CLAVE</th>
                                                <th>DESCRIPCIOM</th>
                                                <th>REQUERIDAS</th>
                                                <th>SURTIDAS</th>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <th>PRECIO UNITARIO</th>
                                                <th>IMPORTE</th>
                                                <th>IVA</th>
                                                <th>SERVICIO</th>
                                                <th>IVA SERVICIO</th>
                                                <th>SUBTOTAL</th>
                                                <?php }?>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $num = 0;
                                            $req = 0;
                                            $sur = 0;
                                            
                                            $tImporte = 0;
                                            $tIVA = 0;
                                            $tServicio = 0;
                                            $tServicioIVA = 0;
                                            $total = 0;

                                            foreach($query->result() as $row){
                                            $num++;    
                                                
                                                $piezas = $row->cansur;
                                                
                                                $importe = $row->precioven * $piezas;
                                                    
                                                if((int)$row->tipoprod == 1)
                                                {
                                                    $iva = $row->precioven * $piezas * IVA;
                                                }else{
                                                    $iva = 0;
                                                }
                                                
                                                $servicio = $piezas * SERVICIO;
                                                $servicio_iva = $servicio * IVA;
                                                $subtotal = $importe + $iva + $servicio + $servicio_iva;
                                                
                                                $tImporte = $tImporte + $importe;
                                                $tIVA = $tIVA + $iva;
                                                $tServicio = $tServicio + $servicio;
                                                $tServicioIVA = $tServicioIVA + $servicio_iva;
                                                $total = $total + $subtotal;
                                            
//r.fecha, r.cvepaciente, r.cveservicio, r.nombremedico, r.folioreceta, r.cvearticulo, r.descripcion, 
//r.tiporequerimiento, r.cantidadsurtida, a.precioven, r.cantidadsurtida * a.precioven as total, 
//a.preciocon, a.precioven - a.preciosinser as importedescuento, (a.precioven - a.preciosinser) * r.cantidadsurtida as subtotaldescuento,
// a.preciosinser * r.cantidadsurtida as importecondescuento, a.iva, a.preciosinser * r.cantidadsurtida + iva as total                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo utf8_encode($row->folioreceta); ?></td>
                                                <td><?php echo utf8_encode($row->programa); ?></td>
                                                <td><?php echo utf8_encode($row->cvepaciente); ?></td>
                                                <td><?php echo utf8_encode($row->paciente); ?></td>
                                                <td><?php echo utf8_encode($row->cvearticulo); ?></td>
                                                <td><?php echo utf8_encode($row->completo); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->canreq, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cansur, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td style="text-align: right;"><?php echo number_format($row->precioven, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio_iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                                <?php }?>
                                                </tr>
                                                
                                                
                                            <?php 
                                            
                                            $req = $req + $row->canreq;
                                            $sur = $sur + $row->cansur;

                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td style="text-align: right;" colspan="8">Totales</td>
                                                <td style="text-align: right;" id="req"><?php echo number_format ($req, 0); ?></td>
                                                <td style="text-align: right;" id="sur"><?php echo number_format ($sur, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td>&nbsp;</td>
                                                <td style="text-align: right;"><?php echo number_format ($tImporte, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tServicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tServicioIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($total, 2); ?></td>
                                                <?php }?>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha</th>
                                                <th>Folio</th>
                                                <th>Programa</th>
                                                <th>Clave Paciente</th>
                                                <th>Paciente</th>
                                                <th>Clave</th>
                                                <th>Descripcion</th>
                                                <th>Requeridas</th>
                                                <th>Surtidas</th>
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
