<?php

$row = $query->row();

?>
							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php echo MY_form_open('jurisdiccion/edita_submit'); ?>
                                    
                                    <?php echo MY_form_input('folio', 'folio', 'Folio', 'text', 'Folio', 3, true, $row->folio); ?>

                                    <?php echo MY_form_datepicker('Fecha del documento', 'fecha', 3, true, $row->fecha); ?>

                                    <?php echo MY_form_dropdown2('Sucursal', 'clvsucursal', $sucursales, $row->clvsucursal, 6); ?>

                                    <?php echo MY_form_dropdown2('Cobertura', 'idprograma', $programa, $row->idprograma, 6); ?>

                                    <?php echo MY_form_input('observaciones', 'observaciones', 'Observaciones', 'text', 'Observaciones', 12, false, $row->observaciones); ?>

                                    <?php echo MY_form_input('cvemedico', 'cvemedico', 'Clave de Médico', 'text', 'Clave de médico', 3, true, $row->cvemedico); ?>

                                    <?php echo MY_form_input('nombremedico', 'nombremedico', 'Nombre de médico', 'text', 'Nombre de Médico', 12, true, $row->nombremedico); ?>

                                    <?php echo MY_form_submit(); ?>

                                    <?php echo form_hidden('colectivoID', $row->colectivoID); ?>
                                    
                                    <?php echo form_close(); ?>
                                    
								</div>	
                            </div>