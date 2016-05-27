							<div class="row-fluid">
                                <div class="span12">
                             <?php echo anchor('reportes/periodoExcel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>        
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>FOLIO RECETA</th>
                                                <th>UNIDAD</th>
                                                <th>CLAVE PACIENTE</th>
                                                <th>NOMBRE</th>
                                                <th>PATERNO</th>
                                                <th>MATERNO</th>
                                                <th>CLAVE ARTICULO</th>                                                                                            
                                                <th>DESCRIPCION</th>
                                                <th>CLAVE MEDICO</th>
                                                <th>NOMBRE MEDICO</th>
                                                <th>CIE</th>
                                                <!-- <th>Nivel de atencion</th> -->
                                                <th>PROGRAMA</th>
                                                <th>REQUERIMIENTO</th>
                                                <th>FECHA CONSULTA</th>
                                                <th>FECHA SURTIDO</th>
                                                <th>CANTIDAD REQUERIDA</th>
                                                <th>CANTIDAD SURTIDA</th>
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
                                            
                                            $cantidadsurtida = 0;
                                            $cantidadrequerida = 0;
                                            $num=0;

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
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->folioreceta; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->cvepaciente; ?></td>
                                                <td><?php echo ($row->nombre); ?></td>
                                                <td><?php echo ($row->apaterno); ?></td>
                                                <td><?php echo ($row->amaterno); ?></td>                                                                                              
                                                <td><?php echo ($row->cvearticulo); ?></td>
                                                <td><?php echo ($row->descripcion); ?></td>
                                                <td><?php echo ($row->cvemedico); ?></td>
                                                <td><?php echo ($row->nombremedico); ?></td>
                                                <td><?php echo ($row->cie103); ?></td>
                                                <td><?php echo ($row->programa); ?></td>
                                                <td><?php echo ($row->requerimiento); ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->fechaexp; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->canreq, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cansur, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td style="text-align: right;"><?php echo number_format ($row->precioven, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($servicio_iva, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($subtotal, 2); ?></td>
                                                <?php }?>
                                                </tr>
                                                
                                                
                                            <?php 
                                    
                                                $cantidadsurtida = $cantidadsurtida + $row->cansur;
                                                $cantidadrequerida = $cantidadrequerida + $row->canreq;
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="16" style="text-align: right;">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($cantidadrequerida, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($cantidadsurtida, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){?>
                                                <td>&nbsp;</td>
                                                <td style="text-align: right;"><?php echo number_format ($tImporte, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tServicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($tServicioIVA, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format ($total, 2); ?></td>
                                                <?php }?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                            
                                </div>
                            </div>
