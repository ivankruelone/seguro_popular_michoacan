<?php
	$row = $query->row();
?>
                            <div class="row-fluid">
                                <div class="span12">
                                
                                    <?php
                                    
                                    if($row->statusColectivo == 0){
                                    
                                    ?>
                                
                                    <p style="text-align: left;"><?php echo anchor('jurisdiccion/cierre/'.$row->colectivoID, 'Cerrar este paquete', array('id' => 'cierre')); ?></p>
                                    
                                    <?php
                                    
                                    }
                                    
                                    ?>
                                    
                                
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
                                                <th>Alta/Cierre</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="colectivoID"><?php echo $row->colectivoID; ?></td>
                                                <td><?php echo $row->folio; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->clvsucursal; ?></td>
                                                <td><?php echo $row->descsucursal; ?></td>
                                                <td><?php echo $row->programa; ?></td>
                                                <td><?php echo $row->nombreusuario; ?></td>
                                                <td><?php echo $row->cvemedico . ' - ' . $row->nombremedico; ?></td>
                                                <td><span style="color: blue;"><?php echo $row->etapa; ?></span></td>
                                                <td><?php echo $row->altaColectivo; ?></td>
                                                <td><?php echo $row->observaciones; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>                                
                                    
                                </div>
                            </div>
                            
                            <?php
                                    
                            if($row->statusColectivo == 0){

                            ?>
                                

							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <div class="widget-box">
											<div class="widget-header">
												<h4>Datos de los productos | Cobertura: <span style="color: blue; "><?php echo $row->programa; ?></span> (<span id="cobertura"><?php echo $row->idprograma; ?></span>) | Nivel de Atención: <span id="nivelatencionReferencia" style="color: blue;"><?php echo $row->nivelatencionReferencia; ?></span></h4>
											</div>
                                    
                                    <div class="widget-body">
												<div class="widget-main">
                                                
                                                <?php echo form_open('jurisdiccion/captura_submit', array('class' => 'form-inline', 'id' => 'captura_form2')); ?>
														<input name="articulo2" id="articulo2" type="text" class="input-large" placeholder="Clave de articulo" />
														<input name="piezas" id="piezas" type="numeric" class="input-small" placeholder="Piezas" />

														<button class="btn btn-info btn-small" id="button_aceptar2">
															<i class="icon-key bigger-110"></i>
															Aceptar
														</button>
                                                <?php echo form_close(); ?>
                                                <table class="table">
                                                    <tr>
                                                        <td id="susa" style="color: green;"></td>
                                                        <td id="descripcion" style="color: blue;"></td>
                                                        <td id="pres" style="color: red;"></td>
                                                    </tr>
                                                </table>
												</div>
											</div>
                                    </div>
                                    
								</div>	
                            </div>
                            


                            
                            <?php
                            
                            }
                            
                            ?>

							<div class="row-fluid">
                                <div class="span12" id="detalle">
                                
                            
                                </div>
                            </div>
