							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <table id="servicios-table" class="table table-striped table-bordered table-hover">
                                        <caption>Sucursal: <?php echo $sucursal; ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave de Servicio</th>
                                                <th>Servicio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($query->result() as $row){?>
                                            <tr>
                                                <td><?php echo $row->cveservicios; ?></td>
                                                <td><?php echo $row->desservicios; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                            
                                </div>
                            </div>
