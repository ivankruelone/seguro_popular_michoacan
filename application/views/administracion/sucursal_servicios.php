							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="servicios-table" class="table table-striped table-bordered table-hover">
                                        <caption>Sucursal: <?php echo $sucursal; ?></caption>
                                        <thead>
                                            <tr>
                                                <th># Servicio</th>
                                                <th>Servicio</th>
                                                <th>Activo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            foreach($query->result() as $row){

                                                
                                                $data = array(
                                                    'name'          => 'activo_'.$row->cveservicios,
                                                    'id'            => $row->cveservicios,
                                                    'clvsucursal'   => $clvsucursal,
                                                    'cveservicios'  => $row->cveservicios,
                                                    'value'         => 1,
                                                    'style'         => 'margin:10px',
                                                    );

                                                if((int)$row->activo === (int)0)
                                                {
                                                    
                                                }else{
                                                    $data['checked'] = 'checked'; 
                                                }

                                            ?>
                                            <tr>
                                                <td><?php echo $row->cveservicios; ?></td>
                                                <td><?php echo $row->desservicios; ?></td>
                                                <td style="text-align: center;"><?php echo form_checkbox($data); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
