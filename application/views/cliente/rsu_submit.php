 							<div class="row-fluid">
									<div class="span12">
                                    
                                    <?php echo anchor('reportes/rsuExcel/'.$fecha1.'/'.$fecha2.'/'.$this->uri->segment(2), '<i class="icon-save"></i>Excel', array('class' => 'btn btn-success btn-app'));?>

                                        
                                    </div>
                            </div>

 							<div class="row-fluid">
                                <div class="span12">
                            
                                    <table id="ventas-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Recetas surtidas</th>
                                                
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
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo number_format($row->cuenta, 0); ?></td>
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
