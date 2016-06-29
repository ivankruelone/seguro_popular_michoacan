<?php
	if($query2->num_rows() > 0)
    {
        $row2 = $query2->row();
        
        $domicilio = $row2->domicilio;
    }else{
        $domicilio = anchor('catalogosweb/domicilio', 'Establece el domicilio');
    }

    $clvpuesto = $this->session->userdata('clvpuesto');

    $bandera = 1;

    if($clvpuesto == 15 || $clvpuesto == 16 || $clvpuesto == 17 || $clvpuesto == 18)
    {
        $bandera = 0;
    }
?>
<div class="row-fluid">
    <div class="span12">
        <p>Nivel de Atenci&oacute;n: <?php echo $this->session->userdata('nivelAtencion'); ?></p>
        <p># Sucursal: <?php echo $this->session->userdata('clvsucursal'); ?></p>
        <p>Sucursal: <?php echo $this->session->userdata('sucursal'); ?></p>
        
        <p># Jurisdicci&oacute;n: <?php echo $this->session->userdata('jur'); ?></p>

        <p>Dia de pedido: <?php echo $this->session->userdata('diaDescripcion'); ?></p>
        
        <p>Foliador CxP: <?php echo $this->session->userdata('cxp'); ?></p>
        
        <p>Domicilio: <?php echo $domicilio; ?></p>
        
        <p>Version: <?php echo $this->util->getVersion(); ?> (<?php echo $this->util->getFechaVersion(); ?>)</p>

        <p>Fecha y hora Apache: <?php echo date('Y-m-d H:i:s'); ?></p>

        <p>Fecha y hora MySQL: <?php echo $fhmysql; ?></p>

        <p>Aleatorio: <?php echo $this->session->userdata('aleatorio'); ?></p>
        
        <?php
        
        if($query3->num_rows() > 0){
        
        
        ?>
        <h3 style="color: red;">Recetas incorrectas, Favor de revisar.</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Folio de receta</th>
                    <th>Clave de articulo</th>
                    <th>Observaciones</th>
                    <th>Edita receta</th>
                    <th>Liberar receta(Ya se corrigi&oacute;)</th>
                </tr>
            </thead>
                <tbody>
                
                <?php foreach($query3->result() as $row3){?>
                    <tr>
                        <td><?php echo $row3->folioreceta; ?></td>
                        <td><?php echo $row3->cvearticulo; ?></td>
                        <td><?php echo $row3->observaciones; ?></td>
                        <td><?php echo anchor('captura/edita/'.$row3->consecutivo, 'Edita la receta'); ?></td>
                        <td><?php echo anchor('captura/liberar/'.$row3->consecutivo, 'Libera la receta'); ?></td>
                    </tr>
                <?php }?>
                </tbody>
        </table>
        
        <?php } ?>

        <!--
        
        <table class="table">
            <thead>
                <tr>
                    <th>Tabla</th>
                    <th>Ultima actualizacion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($query->result() as $row){?>
                <tr>
                    <td><?php echo $row->tabla; ?></td>
                    <td><?php echo $row->ultima_actualizacion; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        -->

        <?php if($bandera == 1)
        {

        ?>
        <h2 style="color: red; ">ATENCIÓN: Las siguientes claves cambiaron de presentación, favor de ajustar sus inventarios a la nueva presentación, asi como las recetas y colectivos que tengan capturados.</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CLAVE</th>
                    <th>SUSTANCIA</th>
                    <th>DESCRIPCION</th>
                    <th>PRESENTACION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($badpres->result() as $b){?>
                <tr>
                    <td><?php echo $b->id; ?></td>
                    <td><?php echo $b->cvearticulo; ?></td>
                    <td><?php echo $b->susa; ?></td>
                    <td><?php echo $b->descripcion; ?></td>
                    <td><?php echo $b->pres; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php

            if($badinv->num_rows > 0)
            {

        ?>

        <h3 style="color: green; ">Revisa los siguientes renglones de tu inventario y ajustalos de ser necesario.</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CLAVE</th>
                    <th>SUSTANCIA</th>
                    <th>DESCRIPCION</th>
                    <th>PRESENTACION</th>
                    <th>LOTE</th>
                    <th>CADUCIDAD</th>
                    <th>INVENTARIO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($badinv->result() as $i){?>
                <tr>
                    <td><?php echo $i->id; ?></td>
                    <td><?php echo $i->cvearticulo; ?></td>
                    <td><?php echo $i->susa; ?></td>
                    <td><?php echo $i->descripcion; ?></td>
                    <td><?php echo $i->pres; ?></td>
                    <td><?php echo $i->lote; ?></td>
                    <td><?php echo $i->caducidad; ?></td>
                    <td><?php echo $i->cantidad; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php

            }

            if($badrec->num_rows() > 0)
            {

        ?>
        <h3 style="color: blue; ">Revisa los siguientes folios de tu captura de recetas y ajustalos de ser necesario.</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>FOLIO</th>
                    <th>FECHA</th>
                    <th>ID</th>
                    <th>CLAVE</th>
                    <th>SUSTANCIA</th>
                    <th>DESCRIPCION</th>
                    <th>PRESENTACION</th>
                    <th>CANTIDAD SURTIDA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($badrec->result() as $c){?>
                <tr>
                    <td><?php echo $c->folioreceta; ?></td>
                    <td><?php echo $c->fecha; ?></td>
                    <td><?php echo $c->id; ?></td>
                    <td><?php echo $c->cvearticulo; ?></td>
                    <td><?php echo $c->susa; ?></td>
                    <td><?php echo $c->descripcion; ?></td>
                    <td><?php echo $c->pres; ?></td>
                    <td><?php echo $c->cansur; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php



            }



        }

        ?>


        
        <h2>Changelog</h2>

        <!--
        
        <h3>Version: 1.0.5 (01/05/2015)</h3>
<pre>
* Se agrega la referencia en la impresion de movimientos.
* Se agrega la version al programa, para rastrear si esta actualizado o no una sucursal.
* Se Reduce el tiempo de espera de las conexiones a 2 segundos, reduciendo el tiempo de guardado(En mejora; Se hara un handshake al iniciar sesion para saber si hay conectividad y bajar a cero las esperas).
* Se ordeno el reporte de recetas por fecha y folio.
    Atte. Ing. Iv&aacute;n Zu&ntilde;iga P&eacute;rez
    Correo: ivan.zuniga@farfenix.com.mx
</pre>

        <h3>Version: 1.0.4 (23/04/2015)</h3>
<pre>
* Exportar folios desde el almacen central y desde otras unidades.
* Actualizacion de catalogo mas rapida(inicio de sesion).
* Implementacion de chequeo de recetas desde el central.
* Adios Amigos.
    Atte. Ing. Iv&aacute;n Zu&ntilde;iga P&eacute;rez
    Correo: ivan.zuniga@farfenix.com.mx
</pre>

        <h3>Version: 1.0.3 (17/04/2015)</h3>
<pre>
* Se agrega Cantidad en la captura de recetas, para saber la cantidad actual de inventario.
* Implementaci&oacute;n de antibi&oacute;ticos y reporte.
* Pr&oacute;xima actualizaci&oacute;n: Sem&aacute;foros, Importar transpasos del almacen central, implementaci&oacute;n de pedido autom&aacute;tico por optimo.
    Atte. Ing. Iv&aacute;n Zu&ntilde;iga P&eacute;rez
    Correo: ivan.zuniga@farfenix.com.mx
</pre>
        
        <h3>Version: 1.0.2 (09/04/2015)</h3>
<pre>
* Correccion de bugs por timeout y memoria
* Reporte de caducidades (PDF)
* Reporte de Inventario (PDF)
* Reporte de Consumos (PDF)
* Reporte de Negados (PDF)
* Reporte de Recetas (PDF)
* Proxima actualizacion: Manejo de antibioticos, Semaforos, Importar transpasos del almacen central.
    Atte. Ing. Iv&aacute;n Zu&ntilde;iga P&eacute;rez
</pre>

    -->

    </div>
</div>