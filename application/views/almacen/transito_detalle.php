							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="table" class="table table-striped table-bordered table-hover">
                                        <caption>Registros: <?php echo count($query); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Clave</th>
                                                <th>Sustancia activa</th>
                                                <th>Descripción</th>
                                                <th>Presentación</th>
                                                <th>Piezas</th>
                                                <th>Aplicadas</th>
                                                <th>Lote</th>
                                                <th>Caducidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $piezas = 0;
                                            $aplicadas = 0;
                                            $n = 1;

                                            foreach($query as $row){

                                            ?>
                                            <tr>
                                                <td><?php echo $n; ?></td>
                                                <td><?php echo $row->id; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->aplicadas, 0); ?></td>
                                                <td><?php echo $row->lote; ?></td>
                                                <td><?php echo $row->caducidad; ?></td>
                                            </tr>
                                            <?php 

                                            $piezas = $piezas + $row->piezas;
                                            $aplicadas = $aplicadas + $row->aplicadas;
                                            $n++;

                                            } 


                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" style="text-align: right;">Total de piezas: </td>
                                                <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
                                                <td style="text-align: right;"><?php echo number_format($aplicadas, 0); ?></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                            
                                </div>
                            </div>
