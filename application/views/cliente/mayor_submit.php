 							<div class="row-fluid">
									<div class="span12">
                                    
                                    <?php echo anchor('reportes/mayorExcel/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>
                                        
                                    </div>
                            </div>

 							<div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave</th>
                                                <th>Sustancia activa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Cantidad surtida</th>
                                                <?php if($this->session->userdata('valuacion') == 1){ ?>
                                                <th style="text-align: right;">Precio Unitario</th>
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
                                            
                                            $num = 0;
                                            $surtido = 0;
                                            $importe = 0;
                                            $iva_producto = 0;
                                            $servicio = 0;
                                            $iva_servicio = 0;
                                            $total = 0;
                                            
                                            
                                            foreach($query->result() as $row){

                                            $subtotal = $row->importe + $row->iva_producto + $row->servicio + $row->iva_servicio;
                                            $num++;                                               
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo number_format($row->surtido, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){ ?>
                                                <td style="text-align: right;"><?php echo number_format($row->precio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                                <?php } ?>
                                            </tr>
                                                
                                                
                                            <?php 

                                            $surtido = $surtido + $row->surtido;
                                            $importe = $importe + $row->importe;
                                            $iva_producto = $iva_producto + $row->iva_producto;
                                            $servicio = $servicio + $row->servicio;
                                            $iva_servicio = $iva_servicio + $row->iva_servicio;
                                            $total = $total + $subtotal;
                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">Totales</td>
                                                <td><?php echo number_format($surtido, 0); ?></td>
                                                <?php if($this->session->userdata('valuacion') == 1){ ?>
                                                <td style="text-align: right;"></td>
                                                <td style="text-align: right;"><?php echo number_format($importe, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_producto, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva_servicio, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
                                                <?php } ?>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Clave</th>
                                                <th>Sustancia activa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Cantidad surtida</th>
                                                <?php if($this->session->userdata('valuacion') == 1){ ?>
                                                <th style="text-align: right;">Precio Unitario</th>
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

                            
                            <div class="row-fluid">
                                <div class="span12">
                                
                                <div id="grafica"></div>
                                
                                </div>
                            </div>
