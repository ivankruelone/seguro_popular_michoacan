<div class="row-fluid">
    <div class="span12">

    <?php

	    echo MY_form_open('medico/nueva__receta');

    ?>

    	<table>

    	    <tr>
		        <td><?php echo MY_form_input('expediente', 'expediente', 'Numero de expediente', 'text', 'Numero de expediente:', 12); ?></td>
		        <td><?php echo MY_form_input('pat', 'pat', 'Paterno', 'text', 'Paterno:', 12); ?></td>
		        <td><?php echo MY_form_input('mat', 'mat', 'Materno', 'text', 'Materno:', 12); ?></td>
		    </tr>

		    <tr>
		        <td><?php echo MY_form_input('nombre', 'nombre', 'Nombre', 'text', 'Nombre:', 12); ?></td>
		        <td><?php echo MY_form_dropdown2('Sexo:', 'sexo', $sexo, 1, 2); ?></td>
		        <td><?php echo MY_form_input('edad', 'edad', 'Edad', 'number', 'Edad:', 12); ?></td>
		    </tr>

		    <tr>
		        <td><?php echo MY_form_dropdown2('Programa:', 'idprograma', $programa, 0, 12); ?></td>
		    </tr>

		    <tr>
		    <?php 

		        if(CIE103 == 1)
		        {

		    ?>


		        <td><?php echo MY_form_input('cie103', 'cie103', 'CIE Primaria', 'text', 'CIE Primaria:', 12, TRUE, null, FALSE, '^[a-zA-Z0-9]{3}$'); ?></td>



		    <?php

		        }

		    ?>


		    <?php 

		        if(CIE104 == 1)
		        {

		    ?>


		        <td><?php echo MY_form_input('cie104', 'cie104', 'CIE Secundaria', 'text', 'CIE Secundaria:', 12, TRUE, null, FALSE, '^[a-zA-Z0-9]{3,4}$'); ?></td>



		    <?php

		        }

		    ?>

		    </tr>    

		    <tr>
		    	
		    	<td colspan="4" style="font-size: larger; color: red;">Producto 1</td>

		    </tr>

		    <tr>
		    
		        <td colspan="4"><?php echo MY_form_input('cveArticulo1', 'cveArticulo1', 'Clave de articulo', 'text', 'Clave de Articulo:', 12, TRUE); ?></td>
		    
		    </tr>
		    
		    <tr>

		        <td id="susa1" style="color: blue;" ></td>
		        <td id="descripcion1" style="color: green;" ></td>
		        <td id="pres1" style="color: red;" ></td>
		        <td id="ampuleo1" style="color: brown;" ></td>

		    </tr>
		    
		    <tr>
		        <td><?php echo MY_form_input('req1', 'req1', 'Solicitada', 'text', 'Solicitada:', 12, TRUE); ?></td>
		        <td colspan="3"><?php echo MY_form_input('dosis1', 'dosis1', 'Posologia', 'text', 'Posologia:', 12, TRUE); ?></td>
		      
		    </tr>
		    
		    <tr>
		    	
		    	<td colspan="4" style="font-size: larger; color: red;">Producto 2</td>

		    </tr>

		    <tr>
		    
		        <td colspan="4"><?php echo MY_form_input('cveArticulo2', 'cveArticulo2', 'Clave de articulo', 'text', 'Clave de Articulo:', 12, FALSE); ?></td>
		    
		    </tr>
		    
		    <tr>

		        <td id="susa2" style="color: blue;" ></td>
		        <td id="descripcion2" style="color: green;" ></td>
		        <td id="pres2" style="color: red;" ></td>
		        <td id="ampuleo2" style="color: brown;" ></td>

		    </tr>
		    
		    <tr>
		        <td><?php echo MY_form_input('req2', 'req2', 'Solicitada', 'text', 'Solicitada:', 12, FALSE); ?></td>
		        <td colspan="3"><?php echo MY_form_input('dosis2', 'dosis2', 'Posologia', 'text', 'Posologia:', 12, FALSE); ?></td>
		      
		    </tr>

		    <tr>
		    	
		    	<td colspan="4" style="font-size: larger; color: red;">Producto 3</td>

		    </tr>

		    <tr>
		    
		        <td colspan="4"><?php echo MY_form_input('cveArticulo3', 'cveArticulo3', 'Clave de articulo', 'text', 'Clave de Articulo:', 12, FALSE); ?></td>
		    
		    </tr>
		    
		    <tr>

		        <td id="susa3" style="color: blue;" ></td>
		        <td id="descripcion3" style="color: green;" ></td>
		        <td id="pres3" style="color: red;" ></td>
		        <td id="ampuleo3" style="color: brown;" ></td>

		    </tr>
		    
		    <tr>
		        <td><?php echo MY_form_input('req3', 'req3', 'Solicitada', 'text', 'Solicitada:', 12, FALSE); ?></td>
		        <td colspan="3"><?php echo MY_form_input('dosis3', 'dosis3', 'Posologia', 'text', 'Posologia:', 12, FALSE); ?></td>
		      
		    </tr>


    	</table>

    <?php
    	echo MY_form_submit();
    	echo form_close();
    ?>

    </div>
</div>