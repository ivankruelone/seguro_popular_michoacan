                            <div class="row-fluid">
                                <div class="span12">
                                
                                <table class="table">
                                    <thead>
                                        <th># Suc.</th>
                                        <th>Sucursal</th>
                                        <th>Folio</th>
                                        <th>Fecha surtido</th>
                                        <th>Cve. paciente</th>
                                        <th>Paciente</th>
                                        <th>Programa</th>
                                        <th>Requerimiento</th>
                                        <th>Cve medico</th>
                                        <th>Medico</th>
                                        <th>Cve. articulo</th>
                                        <th>Descripcion</th>
                                        <th>Requeridas</th>
                                        <th>Surtidas</th>
                                        <th>Cobertura</th>
                                    </thead>
                                    
                                    <tbody>
                                        <?php 

                                        foreach($query->result() as $row){

                                            if($row->cobertura == 'FC')
                                            {
                                                $color = ROJO_PASTEL;
                                                $cobertura = 'FUERA DE COBERTURA';
                                            }else
                                            {
                                                $color = null;
                                                $cobertura = 'CUBIERTO';
                                            }

                                        ?>
                                        <tr style="background-color: <?php echo $color; ?>;">
                                            <td><?php echo $row->suc; ?></td>
                                            <td><?php echo $row->descsucursal; ?></td>
                                            <td><?php echo $row->folio; ?></td>
                                            <td><?php echo $row->fechasurtido; ?></td>
                                            <td><?php echo $row->cvepaciente; ?></td>
                                            <td><?php echo $row->nombrepaciente; ?></td>
                                            <td><?php echo $row->programa; ?></td>
                                            <td><?php echo $row->requerimiento; ?></td>
                                            <td><?php echo $row->cvemedico; ?></td>
                                            <td><?php echo $row->nombremedico; ?></td>
                                            <td><?php echo $row->clave; ?></td>
                                            <td><?php echo $row->descri; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($row->req, 0); ?></td>
                                            <td style="text-align: right;"><?php echo number_format($row->sur, 0); ?></td>
                                            <td><?php echo $cobertura; ?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                
                                </div>
                            </div>