							<div class="row-fluid">
                                <div class="span12">
                                
                                <?php
                                
                                $error = $this->session->flashdata('error');
                                if(strlen($error) >0){
                                
                                ?>
                                
                                <div class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">
											<i class="icon-remove"></i>
										</button>

										<strong>
											<i class="icon-remove"></i>
											Error !
										</strong>

										<?php echo $error; ?>
										<br />
									</div>
                                    
                                    <?php
                                    
                                    }
                                    
                                    ?>
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/0', 'Imprimir inventario de Medicamento', array('target' => '_blank')); ?></p>
                                    
                                    <p><?php echo anchor('inventario/imprimeInventario/1', 'Imprimir inventario de Material de Curacion', array('target' => '_blank')); ?></p>

                                    <p><?php echo anchor('inventario/reasignaUbicacion/', 'Reasigna ubicación'); ?></p>

                                    <p style="text-align: center;"><?php echo $this->pagination->create_links(); ?></p>

                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave</th>
                                                <th>Nombre comercial</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th>Lote</th>
                                                <th>Caducidad</th>
                                                <th style="text-align: right;">Cantidad</th>
                                                <th>EAN</th>
                                                <th>Marca</th>
                                                <th>Suministro</th>
                                                <th style="text-align: center;">Ubicacion</th>
                                                <th colspan="3">Edicion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            $cantidad = 0;
                                            
                                            foreach($query->result() as $row){
                                                

                                                if($this->session->userdata('consulta') == 0)
                                                {
                                                    $edita_datos = anchor('inventario/datos/'.$row->inventarioID, 'Datos');
                                                    $ubicacion = anchor('inventario/asigna_ubicacion/'.$row->inventarioID.'/'.$this->uri->segment(2), $row->pasilloID.'-'.$row->moduloID.'-'.$row->nivelID.'-'.$row->posicionID, array('title' => $row->area. '-' . $row->pasillo.'-'.$row->moduloID.'-'.$row->nivelID.'-'.$row->posicionID));
                                                }else
                                                {
                                                    $edita_datos = null;
                                                    $ubicacion = '<span title="'.$row->area. '-' . $row->pasillo.'-'.$row->moduloID.'-'.$row->nivelID.'-'.$row->posicionID.'">' . $row->pasilloID.'-'.$row->moduloID.'-'.$row->nivelID.'-'.$row->posicionID . '</span>';
                                                }
                                                
                                                if($this->session->userdata('superuser') == 1 || $this->session->userdata('ajuste') == 1)
                                                {
                                                    $edita_cantidad = anchor('inventario/cantidad/'.$row->inventarioID, 'Cantidad');
                                                }else{
                                                    $edita_cantidad = null;
                                                }
                                                
                                                if($row->ventaxuni == 1 && $row->numunidades > 1)
                                                {
                                                    if($this->session->userdata('consulta') == 0)
                                                    {
                                                        $convierte = anchor('inventario/convierte/'.$row->inventarioID, 'Convertir a piezas');
                                                    }else
                                                    {
                                                        $convierte = null;
                                                    }
                                                    
                                                }else{
                                                    $convierte = null;
                                                }
                                                
                                                
                                                
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->comercial; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>
                                                <td><?php echo $row->lote; ?></td>
                                                <td><?php echo $row->caducidad; ?></td>
                                                <td style="text-align: right;"><?php echo number_format($row->cantidad, 0); ?></td>
                                                <td><?php echo $row->ean; ?></td>
                                                <td><?php echo $row->marca; ?></td>
                                                <td><?php echo $row->suministro; ?></td>
                                                <td style="text-align: center;"><?php echo $ubicacion; ?></td>
                                                <td><?php echo $edita_datos; ?></td>
                                                <td><?php echo $edita_cantidad; ?></td>
                                                <td><?php echo $convierte; ?></td>
                                            </tr>
                                            <?php 
                                            
                                                $cantidad = $cantidad + $row->cantidad;
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">Total</td>
                                                <td style="text-align: right;"><?php echo number_format($cantidad, 0); ?></td>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
								</div>	
                            </div>
