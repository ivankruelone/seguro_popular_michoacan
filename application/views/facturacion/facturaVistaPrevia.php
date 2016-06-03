<?php
	$bandera = 1;
?>
<table class="table table-condensed">
    <thead>
        <tr>
            <th style="text-align: right;">Piezas</th>
            <th>Unidad</th>
            <th>Clave</th>
            <th>Descripcion</th>
            <th style="text-align: right;">P. unitario</th>
            <th style="text-align: right;">Importe</th>
            <th style="text-align: right;">IVA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        
        $piezas = 0;
        $importe = 0;
        $iva_total = 0;
        
        foreach($query->result() as $row){
            
            if($row->iva == 0)
            {
                $iva = 0;
            }else{
                $iva = $row->precio * $row->cansur * 0.16;
            }
            
            if($row->precio == 0)
            {
                $bandera = 0;
            }
        
        ?>
        <tr>
            <td style="text-align: right;"><?php echo $row->cansur; ?></td>
            <td>PIEZAS</td>
            <td><?php echo $row->cvearticulo; ?></td>
            <td><?php echo $row->descripcion; ?></td>
            <td style="text-align: right;"><?php echo number_format($row->precio, 4); ?></td>
            <td style="text-align: right;"><?php echo number_format($row->precio * $row->cansur, 4); ?></td>
            <td style="text-align: right;"><?php echo number_format($iva, 4); ?></td>
        </tr>
        <?php 

            $piezas = $piezas + $row->cansur;
            $importe = $importe + $row->precio * $row->cansur;
            $iva_total = $iva_total = $iva;
        
        } 
        
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Totales</td>
            <td style="text-align: right;"><?php echo number_format($importe, 4); ?></td>
            <td style="text-align: right;"><?php echo number_format($iva_total, 4); ?></td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: right;">Total en Factura</td>
            <td style="text-align: right;"><?php echo number_format($importe + $iva_total, 4); ?></td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="7">Total de piezas: <?php echo $piezas?>.</td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="7"><?php echo $referencia?>.</td>
        </tr>
    </tfoot>
</table>
<p>Remision valida para factura: <span id="valido"><?php echo $bandera; ?></span></p>
