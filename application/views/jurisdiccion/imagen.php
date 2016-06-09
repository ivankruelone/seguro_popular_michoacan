                            <div class="row-fluid">
                                <div class="span12">

                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Colectivo ID</th>
                                                <th>Folio</th>
                                                <th>Fecha</th>
                                                <th># Sucursal</th>
                                                <th>Sucursal</th>
                                                <th>Programa</th>
                                                <th>Usuario</th>
                                                <th>Médico</th>
                                                <th>Status</th>
                                                <th>Alta/Cierre/Guia</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            foreach($colectivo->result() as $row){
                                                
                                                $factura = null;
                                                $descargaXML = null;
                                                $descargaPDF = null;
                                                $folioFactura = null;
                                                $fechaFactura = null;

                                                switch ($row->statusColectivo) {
                                                    case 0:
                                                        $link_edita = anchor('jurisdiccion/edita/'.$row->colectivoID, 'Edita <i class="icon-pencil bigger-130"> </i>');                                                   
                                                        $imprime = null;
                                                        $aprobar = null;
                                                        break;
                                                    case 1:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = anchor('jurisdiccion/aprobar/'.$row->colectivoID, 'Aprobar <i class="icon-check bigger-130"> </i>', array('class' => 'aprobar'));
                                                        break;
                                                    case 2:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        break;
                                                    case 3:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        break;
                                                    case 4:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        break;
                                                    case 5:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        break;
                                                }
                                                
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->colectivoID; ?></td>
                                                <td><?php echo $row->folio; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td><?php echo $row->nombreusuario; ?><br />MovimientoID: <span style="color: green;"><?php echo $row->movimientoID; ?></span></td>
                                                <td><?php echo $row->cvemedico . ' - ' . $row->nombremedico; ?></td>
                                                <td><span style="color: blue;"><?php echo $row->etapa; ?></span><br />Paquete: <span style="color: green;"><?php echo $row->referencia; ?></span></td>
                                                <td><?php echo $row->altaColectivo . '<br />' . $row->fechaCierre . '<br />' . $row->fechaGuia; ?></td>
                                                <td><?php echo $row->observaciones; ?></td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
							
                            <div class="row-fluid">
                                <div class="span12">
                                
                                <?php
                                
                                echo form_open('jurisdiccion/imagen_submit', array('enctype' => 'multipart/form-data'));
                                
                                ?>
                                
                                Please choose a file: <input type="file" name="uploadFile" /><br />
                                <input type="submit" value="Subir archivo" />
                                
                                <?php

                                echo form_hidden('colectivoID', $colectivoID);
                                
                                echo form_close();
                                
                                ?>
                                
                                
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">

                                <?php

                                foreach ($query->result() as $row2) {
                                    echo '<div>';
                                    
                                    if($row2->usuario == $this->session->userdata('usuario'))
                                        echo anchor('jurisdiccion/eliminar_imagen/' . $row2->colectivo_imagenID . '/' . $colectivoID, 'Eliminar <i class="icon-trash bigger-130"> </i>', array('class' => 'eliminar'));

                                    
                                    echo '<br />';
                                    echo img($row2->rutaImagen);
                                    echo '<br />';
                                    echo '</div>';
                                }

                                ?>

                                </div>
                            </div>
