<div class="row-fluid">
    <div class="span12">

    <?php

    $cvemedico = null;
    $nombremedico = null;
    $cveservicios = 9;

    if($query->num_rows()  > 0)
    {
    	$row = $query->row();
    	
    	$cvemedico = $row->cvemedico;
    	$nombremedico = $row->nombremedico;
    	$cveservicios = $row->cveservicios;

    }

    echo MY_form_open('medico/nueva__configuracion');

    ?>

    	<table>

    	    <tr>
		        <td><?php echo MY_form_input('cvemedico', 'cvemedico', 'Clave de Medico', 'text', 'Clave de Medico:', 12, TRUE, $cvemedico); ?></td>
		    </tr>

    	    <tr>
		        <td colspan="4"><?php echo MY_form_input('nombremedico', 'nombremedico', 'Nombre de Medico', 'text', 'Nombre de Medico:', 12, TRUE, $nombremedico); ?></td>
		    </tr>

		    <tr>
		   
        		<td><?php echo MY_form_dropdown2('Servicio:', 'cveservicios', $categoria, $cveservicios, 2); ?></td>
		    
		    </tr>


    	</table>

    <?php
    	echo MY_form_submit();
    	echo form_close();
    ?>

    </div>
</div>