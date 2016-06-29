                                    <p style="text-align: center;"><?php echo $this->pagination->create_links(); ?></p>
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
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
                                                <th>Imagen</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            foreach($query->result() as $row){
                                                
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
                                                        $rechaza = null;
                                                        break;
                                                    case 1:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        if($this->session->userdata('clvpuesto') == 4 || $this->session->userdata('clvpuesto') == 22 || $this->session->userdata('superuser') == 1)
                                                        {
                                                            $aprobar = anchor('jurisdiccion/aprobar/'.$row->colectivoID, 'Aprobar <i class="icon-check bigger-130"> </i>', array('class' => 'aprobar'));
                                                            $rechaza = anchor('jurisdiccion/rechazar/'.$row->colectivoID, 'Rechazar <i class="icon-backward bigger-130"> </i>', array('class' => 'rechazar'));
                                                        }else
                                                        {
                                                            $aprobar = null;
                                                            $rechaza = null;
                                                        }
                                                        
                                                        break;
                                                    case 2:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        $rechaza = null;
                                                        break;
                                                    case 3:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        $rechaza = null;
                                                        break;
                                                    case 4:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        $rechaza = null;
                                                        break;
                                                    case 5:
                                                        $link_edita = null;
                                                        $imprime = anchor('jurisdiccion/imprime/'.$row->colectivoID, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                        $aprobar = null;
                                                        $rechaza = null;
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
                                                <td><?php echo anchor('jurisdiccion/imagen/'.$row->colectivoID, 'Imagen <i class="icon-camera bigger-130"> </i>'); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center; "><?php echo $link_edita; ?></td>
                                                <td style="text-align: center; "><?php echo $imprime; ?></td>
                                                <td style="text-align: center; "><?php echo anchor('jurisdiccion/captura/'.$row->colectivoID, 'Captura <i class="icon-barcode bigger-130"> </i>'); ?></td>
                                                <td style="text-align: center; "><?php echo $aprobar; ?></td>
                                                <td style="text-align: center; "><?php echo $rechaza; ?></td>
                                                <td><?php echo $descargaXML; ?></td>
                                                <td><?php echo $descargaPDF; ?></td>
                                                <td><?php echo $fechaFactura; ?></td>
                                                <td colspan="4"></td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
