                            <div class="row-fluid">
                                    <div class="span12">
                                    
                                    <?php echo anchor('facturacion/getFacturasExcel', '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>
                                        
                                    </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <caption>Regitros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Serie</th>
                                                <th>Folio</th>
                                                <th>Folio fiscal</th>
                                                <th>Sucursal</th>
                                                <th>Cobertura</th>
                                                <th>Fecha Inicial</th>
                                                <th>Fecha Final</th>
                                                <th>Concepto</th>
                                                <th style="text-align: right;">Total</th>
                                                <th style="text-align: right;">IVA</th>
                                                <th>Remisión</th>
                                                <th style="text-align: center;">Vigencia</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $total = 0;
                                            $iva = 0;
                                            
                                            foreach($query->result() as $row){
                                            ?>
                                            <tr>
                                                <td><?php echo preg_replace('/[0-9]/', '', $row->numfac); ?></td>
                                                <td><?php echo preg_replace('/[A-Z]/', '', $row->numfac); ?></td>
                                                <td><?php echo $row->uuid; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td><?php echo $row->perini; ?></td>
                                                <td><?php echo $row->perfin; ?></td>
                                                <td><?php echo $row->concepto; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->totalFactura, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->ivaFactura, 2); ?></td>
                                                <td><?php echo $row->remision; ?></td>
                                                <td style="text-align: center;"><?php echo $row->vigencia; ?></td>
                                            </tr>
                                                
                                                
                                            <?php 

                                            $total = $total + $row->totalFactura;
                                            $iva = $iva + $row->ivaFactura;
                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8">Totales</td>
                                                <td style="text-align: right;"><?php echo number_format($total, 2); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($iva, 2); ?></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                        <thead>
                                            <tr>
                                                <th>Serie</th>
                                                <th>Folio</th>
                                                <th>Folio fiscal</th>
                                                <th>Sucursal</th>
                                                <th>Cobertura</th>
                                                <th>Fecha Inicial</th>
                                                <th>Fecha Final</th>
                                                <th>Concepto</th>
                                                <th>Total</th>
                                                <th>IVA</th>
                                                <th>Remisión</th>
                                                <th>Vigencia</th>
                                             </tr>
                                        </thead>
                                    </table>
                                    
                            
                                </div>
                            </div>