							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <p><?php echo anchor('catalogosweb/nuevo_articulo', 'Nuevo articulo'); ?></p>
                                    
                                    <?php echo $this->pagination->create_links(); ?>
                                    
                                    <table class="table table-condensed">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th colspan="<?php echo $numReg; ?>"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            foreach($query->result() as $row){
                                                
                                                $semaforo = '<i class="icon-circle" style="color: '.$row->semaforoColor.'"> ' . $row->semaforoDescripcion . '</i>';

                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->cvearticulo; ?></td>
                                                <td><?php echo $row->susa; ?></td>
                                                <td><?php echo $row->descripcion; ?></td>
                                                <td><?php echo $row->pres; ?></td>

                                                <?php

                                                $consulta = $this->admin_model->getCoberturasByArticulosCrossing($row->id);

                                                foreach($consulta->result() as $c)
                                                {


                                                    $data = array(
                                                        'name'          => 'opcion_'.$c->nivelatencion.'_'.$c->idprograma.'_'.$row->id,
                                                        'id'            => 'opcion_'.$c->nivelatencion.'_'.$c->idprograma.'_'.$row->id,
                                                        'value'         => 'accept',
                                                        'idprograma'    => $c->idprograma,
                                                        'nivelatencion' => $c->nivelatencion,
                                                        'id'            => $row->id,
                                                    );

                                                    if($c->checked == 'TRUE')
                                                    {
                                                        $data['checked'] = 'checked';
                                                    }

                                                ?>

                                                <td style="text-align: center; font-size: x-small;"><?php echo form_checkbox($data); ?><br /><?php echo $c->cobertura; ?> <br /><?php echo $c->nivelatenciondescripcion; ?></td>


                                                <?php
                                                }

                                                ?>



                                            </tr>
                                            <?php 
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
