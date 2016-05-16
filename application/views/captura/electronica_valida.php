<div class="row-fluid">
    <div class="span12">
        
<?php
    echo MY_form_open('captura/recetas__electronica_agregar');
    
    $data = array(
    'name' => 'button',
    'id' => 'addproducto',
    'value' => 'true',
    'type' => 'button',
    'class' => 'btn',
    'content' => 'Agregar'
    );

    $row = $query->row();

?>

<table>
    <caption id="tipo">Receta electronica</caption>
    <tr>
        <td colspan="3">Sucursal: <span style="color: red;"><?php echo $this->session->userdata('clvsucursal') . ' - ' .$this->session->userdata('sucursal'); ?></span></td>
    
    </tr>

    <tr>
        
        <!--<td><?php //echo MY_form_datepicker('Fecha de Consulta', 'fechaCon', 6, true);?></td>-->
        <td><?php echo MY_form_datepicker('Fecha de surtido', 'fechaSur', 6, true, date('Y-m-d'), true);?></td>
        <td><?php echo MY_form_input('folioReceta', 'folioReceta', 'Folio Receta', 'text', 'Folio Receta:', 12, true, $row->recetaID, true); ?></td>
        <td>Usuario: <span style="color: red;"><?php echo $this->session->userdata('usuario'); ?></span> - <?php echo date('Y-m-d H:i:s'); ?> - <span id="diferencia"><?php echo $config['dias_diferencia']; ?></span></td>

    </tr>
    
    <tr>
            
        <td><?php echo MY_form_dropdown2('Programa:', 'tipoReceta', $tipoReceta, $row->idprograma, 2, 'icon-reorder', true, true); ?></td>
        <td><?php echo MY_form_dropdown2('Requerimiento:', 'tipoReq', $requerimiento, $row->tiporequerimiento, 2, 'icon-reorder', true, true); ?></td>
        <td><?php echo MY_form_dropdown2('Servicio:', 'categoria', $categoria, $row->cveservicios, 2, 'icon-reorder', true, true); ?></td>
    </tr>

    <tr>
        <td><?php echo MY_form_input('expediente', 'expediente', 'Numero de expediente', 'text', 'Numero de expediente:', 12, true, $row->cvepaciente, true); ?></td>
        <td><?php echo MY_form_input('pat', 'pat', 'Paterno', 'text', 'Paterno:', 12, true, $row->apaterno, true); ?></td>
        <td><?php echo MY_form_input('mat', 'mat', 'Materno', 'text', 'Materno:', 12, true, $row->amaterno, true); ?></td>
    </tr>

    <tr>
   
        <td><?php echo MY_form_input('nombre', 'nombre', 'Nombre', 'text', 'Nombre:', 12, true, $row->nombre, true); ?></td>
        <td><?php echo MY_form_dropdown2('Sexo:', 'sexo', $sexo, $row->genero, 2, 'icon-reorder', true, true); ?></td>
        <td><?php echo MY_form_input('edad', 'edad', 'Edad', 'number', 'Edad:', 12, true, $row->edad, true); ?></td>
    
    </tr>

    <tr>
    
        <td><?php echo MY_form_input('cveMedico', 'cveMedico', 'Clave de medico', 'text', 'Clave de Medico:', 12, true, $row->cvemedico, true); ?></td>
        <td colspan="2"><?php echo MY_form_input('medico', 'medico', 'Medico', 'text', 'Medico:', 12, true, $row->nombremedico, true); ?></td>
    
    </tr>
    
    <tr>
    <?php 

        if(CIE103 == 1)
        {

    ?>


        <td><?php echo MY_form_input('cie103', 'cie103', 'CIE Primaria', 'text', 'CIE Primaria:', 12, TRUE, $row->cie103, TRUE, '^[a-zA-Z0-9]{3}$'); ?></td>



    <?php

        }

    ?>


    <?php 

        if(CIE104 == 1)
        {

    ?>


        <td><?php echo MY_form_input('cie104', 'cie104', 'CIE Secundaria', 'text', 'CIE Secundaria:', 12, TRUE, $row->cie104, TRUE, '^[a-zA-Z0-9]{3,4}$'); ?></td>



    <?php

        }

    ?>

    </tr>    

</table>

<div id="productos">

	<table class="table">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Sustancia Activa</th>
				<th>Descripcion</th>
				<th>Presentacion</th>
				<th>Cantidad Requerida</th>
				<th>Cantidad Surtida</th>
				<th>Lote</th>
			</tr>
		</thead>
		<tbody>
			<?php 

			foreach ($detalle->result() as $det)
			{

				$dataSur = array(
					'id' => $det->detalleID,
					'name'	=> 'sur_' . $det->detalleID,
					'required'	=> 'required',
					'type'		=> 'number',
					'min'		=> 0,
                    'max'       => $det->req
				);
			

			?>
			<tr>
				<td><?php echo $det->cvearticulo; ?></td>
				<td><?php echo $det->susa; ?></td>
				<td><?php echo $det->descripcion; ?></td>
				<td><?php echo $det->pres; ?></td>
				<td style="text-align: right;"><?php echo $det->req; ?></td>
				<td><?php  echo form_input($dataSur); ?></td>
				<td><?php  echo form_dropdown('lote_'. $det->detalleID, $this->captura_model->getLoteDrop($det->cvearticulo)); ?></td>
			</tr>
			<?php 

			}

			?>
		</tbody>
	</table>

</div>



<?php
	echo form_hidden('recetaID', $row->recetaID);
    echo MY_form_submit();
    echo form_close();
?>


    </div>
</div>
