							<div class="row-fluid">
                                <div class="span12">
                                
                                    <table id="proveedores-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Submenu</th>
                                                <th style="text-align: center;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            foreach($query->result() as $row){
                                                
                                                if($row->opcion == null)
                                                {
                                                    $valor = FALSE;
                                                }else{
                                                    $valor = TRUE;
                                                }
                                                
                                                $data = array(
                                                    'name'        => 'opcion_'.$row->submenu,
                                                    'id'          => $row->submenuID,
                                                    'value'       => $clvpuesto,
                                                    'checked'     => $valor,
                                                    'style'       => 'margin:10px',
                                                    );
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->menu; ?></td>
                                                <td><?php echo $row->submenu; ?></td>
                                                <td style="text-align: center;"><?php echo form_checkbox($data); ?></td>
                                            </tr>
                                            <?php 
                                            
                                            } 
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                
        <?php echo MY_form_open('administracion/perfil_bulk_puesto'); ?>
                                    
        <?php echo form_hidden('clvpuesto', $clvpuesto); ?>
        
        <?php echo MY_form_submit(); ?>
                                    
        <?php echo form_close(); ?>

                                </div>
                            </div>