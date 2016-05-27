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
                                                
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            $num = 0;
                                            
                                            
                                            foreach($query->result() as $row){
                                            $num++;                                               
                                            ?>
                                            <tr>
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo number_format($row->surtido, 0); ?></td>
                                                </tr>
                                                
                                                
                                            <?php 
                                            
                                            }
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                    
                            
                                </div>
                            </div>

                            
                            <div class="row-fluid">
                                <div class="span12">
                                
                                <div id="grafica"></div>
                                
                                </div>
                            </div>
