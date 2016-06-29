							<div class="row-fluid">
                                <div class="span12">

<table cellpadding="2" cellspacing="2">
    <tr>
        <td style="background-color: #F08080; width: 50px;"></td>
        <td style="font-weight: bold;">FUERA DE COBERTURA PARA ESTA UNIDAD.</td>
    </tr>
</table>                                    
                                    <table class="table table-condensed table-hover">
                                        <caption>Registros: <?php echo $query->num_rows(); ?></caption>
                                        <thead>
                                            <tr>
                                                <th>Clave</th>
                                                <th>Susa</th>
                                                <th>Descripcion</th>
                                                <th>Presentacion</th>
                                                <th style="text-align: right;">Inventario</th>
                                                <th style="text-align: right;">Demanda</th>
                                                <th>Buffer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                
                                            foreach($query->result() as $row){
                                                

                                                $data = array(
                                                  'name'        => 'buffer_'.$row->clvsucursal.'_'.$row->id,
                                                  'id'          => 'buffer_'.$row->clvsucursal.'_'.$row->id,
                                                  'value'       => $row->buffer,
                                                  'min'         => 0,
                                                  'max'         => 99999,
                                                  'type'        => 'number',
                                                  'clvsucursal' => $row->clvsucursal,
                                                  'id'          => $row->id,
                                                  'class'       => 'input-small',
                                                  'style'       => 'font-size: 20px; '
                                                );

                                                if($row->cobertura == 1)
                                                {
                                                    $color = null;
                                                }else
                                                {
                                                    $color = '#F08080';
                                                }

                                                if(ALMACEN == $this->session->userdata('clvsucursal'))
                                                {
                                                    $input = number_format($row->buffer, 0);
                                                }else
                                                {
                                                    $input = form_input($data);
                                                }

                                            
                                            ?>
                                            <tr style="background-color: <?php echo $color; ?>; vertical-align: middle;">
                                                <td style=" vertical-align: middle;"><?php echo $row->cvearticulo; ?></td>
                                                <td style=" vertical-align: middle;"><?php echo $row->susa; ?></td>
                                                <td style=" vertical-align: middle;"><?php echo $row->descripcion; ?></td>
                                                <td style=" vertical-align: middle;"><?php echo $row->pres; ?></td>
                                                <td style="text-align: right; font-size: large; color: #8B0000; font-weight: bolder; vertical-align: middle; "><?php echo number_format($row->inv, 0); ?></td>
                                                <td style="text-align: right; font-size: large; vertical-align: middle;"><?php echo number_format($row->demanda, 0); ?></td>
                                                <td style=" vertical-align: middle;"><?php echo $input; ?></td>
                                            </tr>
                                            <?php 
                                            
                                            
                                            }
                                            
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
