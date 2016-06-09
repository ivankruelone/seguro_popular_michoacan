<table cellpadding="2" cellspacing="2">
    <tr>
        <td style="background-color: <?php echo ROJO; ?>; width: 50px;"></td>
        <td style="font-weight: bold;">FUERA DE COBERTURA PARA ESTA UNIDAD.</td>
    </tr>
</table>

<table class="table table-condensed">
    <thead>
        <tr>
            <th>Detalle</th>
            <th>ID</th>
            <th>Clave</th>
            <th>Susa</th>
            <th>Descripcion</th>
            <th>Presentacion</th>
            <th style="text-align: right;">Piezas</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        
        $piezas = 0;
        
        foreach($query->result() as $row){
            
            if($row->statusMovimiento == 0 || $row->statusMovimiento == 3)
            {
                $link_elimina = anchor('movimiento/elimina_detalle_prepedido/'.$row->movimientoPrepedido, 'Elimina', array('class' => 'elimina_detalle'));
            }else{
                $link_elimina = null;
            }
            
            if($row->statusMovimiento == 1 && $row->subtipoMovimiento == 13)
            {
                $link_devolucion = anchor('movimiento/devolucion/'.$row->movimientoDetalle, 'Devolucion', array('class' => 'devolucion'));
            }elseif($row->statusMovimiento == 0 && $row->subtipoMovimiento == 2)
            {
                $link_devolucion = anchor('movimiento/modifica/'.$row->movimientoDetalle.'/'.$movimientoID, 'Modificar');
            }else{
                $link_devolucion = null;
            }

            if($row->cubierto == 0)
            {
                $color = ROJO;
            }else
            {
                $color = '';
            }
        
        ?>
        <tr style="background-color: <?php echo $color; ?>">
            <td><?php echo $row->movimientoPrepedido; ?></td>
            <td><?php echo $row->id; ?></td>
            <td><?php echo $row->cvearticulo; ?></td>
            <td><?php echo $row->susa; ?></td>
            <td><?php echo $row->descripcion; ?></td>
            <td><?php echo $row->pres; ?></td>
            <td style="text-align: right;"><?php echo number_format($row->piezas, 0); ?></td>
            <td><?php echo $link_elimina; ?></td>
        </tr>
        <?php 

            $piezas = $piezas + $row->piezas;
        
        } 
        
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: right;">Totales</td>
            <td style="text-align: right;"><?php echo number_format($piezas, 0); ?></td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
<script type="text/javascript">
<!--
    $('.elimina_detalle').on('click', elimina);
    
    function elimina(event)
    {
        event.preventDefault();
        
        if(confirm("Deseas eliminar este registro??"))
        {
            var $url = event.currentTarget.href;
            var $variables = { };
            var posting = $.post( $url, $variables );
                
                 posting.done(function( data ) {
                    
                    detalle();
                    
                 });
            return true;
        }else{
            return false;
        }
    }

    function detalle()
    {
        
        $movimientoID = $('#movimientoID').html();
        
        var $url = '<?php echo site_url('movimiento/detallePrepedido'); ?>';
        var $variables = { movimientoID: $movimientoID };
        var posting = $.post( $url, $variables );
            
             posting.done(function( data ) {
                
                $('#detalle').html(data);
                
             });
        
    }
-->
</script>