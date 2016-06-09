							<div class="row-fluid">
                                <div class="span12">
                                    
                                    <?php
                                    
                                        if($tipoMovimiento == 1)
                                        {

                                            $suc = "Destino";
                                            $suc_ref = "Origen";

                                        }elseif($tipoMovimiento == 2)
                                        {

                                            $suc = "Origen";
                                            $suc_ref = "Destino";

                                        }else
                                        {

                                            $suc = "";
                                            $suc_ref = "";

                                        }

                                    if($subtipoMovimiento == 15){
                                    
                                    ?>
                                    
                                    <p style="text-align: center; color: red;">Este tipo de movimiento borra el inventario actual y el movimiento se convierte en el nuevo inventario.</p>
                                    
                                    <?php
                                    
                                    }
                                    
                                    ?>
                                    
                                    <p><?php if($this->session->userdata('consulta') == 0 && ALMACEN != $this->session->userdata('clvsucursal')) echo anchor('almacen/nuevo_pedido/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Nuevo Pedido'); ?></p>
                                    
                                    <p style="text-align: center;"><?php echo $this->pagination->create_links(); ?></p>
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Movimiento</th>
                                                <th>Tipo de Movimiento</th>
                                                <th>Subtipo de Movimiento</th>
                                                <th>Orden</th>
                                                <th>Referencia</th>
                                                <th>Fecha</th>
                                                <th>Proveedor</th>
                                                <th><?php echo $suc; ?></th>
                                                <th><?php echo $suc_ref; ?></th>
                                                <th>Usuario</th>
                                                <th>Alta/Cierre/Baja</th>
                                                <th>Observaciones</th>
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
                                                
                                                if($row->statusMovimiento == 0)
                                                {
                                                    $status = '<span style="color: green; ">'.$row->statusMovimientoDescripcion.'</span>';
                                                    if($this->session->userdata('consulta') == 0)
                                                    {
                                                        $link_edita = anchor('movimiento/edita/'.$row->movimientoID, 'Edita <i class="icon-pencil bigger-130"> </i>');
                                                    }else
                                                    {
                                                        $link_edita = null;
                                                    }
                                                    
                                                    $imprime = null;
                                                    $cancela = anchor('movimiento/cancela/' . $row->movimientoID . '/' . $tipoMovimiento . '/' . $subtipoMovimiento, '<span style="color: red;">Cancelar</span>', array('class' => 'cancelar'));
                                                    $abrir = null;
                                                }elseif($row->statusMovimiento == 1){
                                                    $status = '<span style="color: blue; ">'.$row->statusMovimientoDescripcion.'</span>';
                                                    $link_edita = null;
                                                    $imprime = anchor('movimiento/imprime/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Imprime <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                    $cancela = null;

                                                    if($this->session->userdata('superuser') == 1)
                                                    {
                                                        $abrir = anchor('movimiento/abrir/' . $row->movimientoID . '/' . $tipoMovimiento . '/' . $subtipoMovimiento, '<span style="color: blue;">Abrir</span>', array('class' => 'abrir', 'movimientoID' => $row->movimientoID));
                                                    }else
                                                    {
                                                        $abrir = null;
                                                    }

                                                }elseif($row->statusMovimiento == 2)
                                                {
                                                    $status = '<span style="color: red; ">'.$row->statusMovimientoDescripcion.'</span>';
                                                    $link_edita = null;
                                                    $imprime = null;
                                                    $cancela = null;
                                                    $abrir = null;
                                                }elseif($row->statusMovimiento == 3)
                                                {
                                                    $status = '<span style="color: red; ">'.$row->statusMovimientoDescripcion.'</span>';
                                                    $link_edita = null;
                                                    $imprime = null;
                                                    $cancela = null;
                                                    $abrir = null;
                                                }elseif($row->statusMovimiento == 4)
                                                {
                                                    $status = '<span style="color: red; ">'.$row->statusMovimientoDescripcion.'</span>';
                                                    $link_edita = null;
                                                    $imprime = null;
                                                    $cancela = null;
                                                    $abrir = null;
                                                }
                                                
                                                if($subtipoMovimiento == 13 || $subtipoMovimiento == 21 || $subtipoMovimiento == 22 || $subtipoMovimiento == 23)
                                                {
                                                    if($this->session->userdata('consulta') == 0)
                                                    {
                                                        $embarque = anchor('movimiento/embarque/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Embarque <i class="icon-truck bigger-130"> </i>');
                                                    }else
                                                    {
                                                        $embarque = null;
                                                    }
                                                    
                                                    
                                                    if($row->statusPrepedido == 0)
                                                    {
                                                        if($this->session->userdata('consulta') == 0)
                                                        {
                                                            $prepedido = anchor('movimiento/prepedido/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Captura aqui');
                                                        }else
                                                        {
                                                            $prepedido = null;
                                                        }
                                                        
                                                        $guia = null;
                                                    }else{
                                                        $prepedido = null;
                                                        if(ALMACEN == $this->session->userdata('clvsucursal'))
                                                        {
                                                            $guia = anchor('almacen/aprobar_pedido/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Aprobar pedido', array('class' => 'aprobar_pedido', 'movimientoID' => $row->movimientoID));
                                                        }else
                                                        {
                                                            $guia = null;
                                                        }
                                                        
                                                    }
                                                    
                                                    if($row->statusMovimiento == 1 && $row->idFactura > 0)
                                                    {
                                                        $factura = null;
                                                        $folioFactura = '<span style="color: red;">'.$row->folioFactura.' <i class="icon-file bigger-130"> </i> </span>';
                                                        $descargaXML = anchor('movimiento/descargaXML/'.$row->movimientoID, 'Descarga XML <i class="icon-download bigger-130"> </i>');
                                                        $descargaPDF = anchor('movimiento/descargaPDF/'.$row->movimientoID, 'Descarga PDF <i class="icon-download bigger-130"> </i>');
                                                        $fechaFactura = $row->fechaFactura;
                                                    }elseif($row->statusMovimiento == 1 && $row->idFactura == 0){
                                                        $folioFactura = null;
                                                        $descargaXML = null;
                                                        $descargaPDF = null;
                                                        $fechaFactura = null;
                                                        if($this->session->userdata('consulta') == 0)
                                                        {
                                                            $factura = anchor('movimiento/factura/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Facturar <i class="icon-file bigger-130"> </i>');
                                                        }else
                                                        {
                                                            $factura = null;
                                                        }
                                                        
                                                    }else{
                                                        $folioFactura = null;
                                                        $descargaXML = null;
                                                        $descargaPDF = null;
                                                        $fechaFactura = null;
                                                        $factura = null;
                                                    }
                                                    
                                                }else{
                                                    $embarque = null;
                                                    $prepedido = null;
                                                    $guia = null;
                                                    $factura = null;
                                                    $descargaXML = null;
                                                    $descargaPDF = null;
                                                }
                                                
                                                if($subtipoMovimiento == 3)
                                                {
                                                    if($row->statusMovimiento == 1 && $row->asignaFactura == 0)
                                                    {
                                                        $prepedido = anchor('movimiento/asigna_factura/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'Asisgna Factura <i class="icon-folder-close bigger-130"> </i>');
                                                    }
                                                    
                                                }

                                                if($subtipoMovimiento == 5 && $row->statusMovimiento == 1)
                                                {
                                                    $embarque = anchor('movimiento/imprimeExcedente/'.$row->movimientoID.'/'.$tipoMovimiento.'/'.$subtipoMovimiento, 'FTO EX. <i class="icon-print bigger-130"> </i>', array('target' => '_blank'));
                                                }
                                                
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $row->movimientoID; ?> <br /><?php echo $status; ?></td>
                                                <td><?php echo $row->tipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->subtipoMovimientoDescripcion; ?></td>
                                                <td><?php echo $row->orden; ?></td>
                                                <td><?php echo $row->referencia; ?></td>
                                                <td><?php echo $row->fecha; ?></td>
                                                <td><?php echo $row->razon; ?></td>
                                                <td><?php echo $row->sucursal; ?></td>
                                                <td><?php echo $row->sucursal_referencia . '<br /><span style="color: blue;">' . $row->programa . '</span>'. '<br /><span style="color: green;">' . $row->colectivo . '</span>'; ?></td>
                                                <td><?php echo $row->nombreusuario; ?></td>
                                                <td><?php echo $row->fechaAlta.'<br />'.$row->fechaCierre.'<br />'.$row->fechaCancelacion; ?></td>
                                                <td><?php echo $row->observaciones; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $link_edita; ?></td>
                                                <td><?php echo $prepedido; ?></td>
                                                <td><?php echo $guia; ?></td>
                                                <td colspan="9"></td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                    
								</div>	
                            </div>
