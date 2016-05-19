                        	<div class="row-fluid">
                                <div class="span12">

                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Lote</th>
                                                <th>Caducidad</th>
                                                <th style="text-align: right;">Cantidad</th>
                                                <th>EAN</th>
                                                <th>Marca</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            $cantidad = 0;
                                            
                                            foreach($query->result() as $row){
                                                
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo $row->lote; ?></td>
                                                <td><?php echo $row->caducidad; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <td><?php echo $row->ean; ?></td>
                                                <td><?php echo $row->marca; ?></td>
                                            </tr>
                                            <?php 
                                            
                                                $cantidad = $cantidad + $row->cantidad;
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8">Total</td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>