<?php
class Facturacion_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */
    function __construct()
    {
        parent::__construct();
    }

    function getPosiblesRemisiones($fecha1, $fecha2, $clvsucursal)
    {
    	$sql = "SELECT nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, sum(canreq) as canreq, sum(cansur) as cansur, sum(cansur * precio) as importe, sum(case when iva = 0 then 0 else (cansur * precio * 0.16) end) as iva_producto, sum(cansur * servicio) as servicio, sum(cansur * servicio * 0.16) as iva_servicio
FROM receta r
join receta_detalle d using(consecutivo)
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on d.iva = u.cvesuministro
where remision = 0 and fecha between ? and ? and clvsucursal = ?
group by clvsucursal, nivelatencion, iva, tiporequerimiento, idprograma;";

		$query = $this->db->query($sql, array((string)$fecha1, (string)$fecha2, (int)$clvsucursal));

		return $query;

    }

    function getPosibleRemisionDatos($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma)
    {

    	$sql = "SELECT nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, sum(canreq) as canreq, sum(cansur) as cansur, sum(cansur * precio) as importe, sum(case when iva = 0 then 0 else (cansur * precio * 0.16) end) as iva_producto, sum(cansur * servicio) as servicio, sum(cansur * servicio * 0.16) as iva_servicio
FROM receta r
join receta_detalle d using(consecutivo)
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on d.iva = u.cvesuministro
where remision = 0 and fecha between ? and ? and clvsucursal = ? and iva = ? and tiporequerimiento = ? and idprograma = ?
group by clvsucursal, nivelatencion, iva, tiporequerimiento, idprograma;";

		$query = $this->db->query($sql, array((string)$perini, (string)$perfin, (int)$clvsucursal, $iva, $tiporequerimiento, $idprograma));

		return $query;

    }

    function validaRemisionPrevia($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma)
    {

    	$sql = "SELECT precio
FROM receta r
join receta_detalle d using(consecutivo)
where remision = 0 and fecha between ? and ? and clvsucursal = ? and iva = ? and tiporequerimiento = ? and idprograma = ? and precio = 0;";
		$query = $this->db->query($sql, array((string)$perini, (string)$perfin, (int)$clvsucursal, $iva, $tiporequerimiento, $idprograma));

		return $query->num_rows();

    }

    function generaRemision($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma)
    {
    	$this->db->trans_start();

    	$query = $this->getPosibleRemisionDatos($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma);

    	if($query->num_rows() > 0)
    	{
    		$row = $query->row();

    		$data = array(
    			'perini'			=> (string)$perini,
    			'perfin'			=> (string)$perfin,
    			'clvsucursal'		=> $row->clvsucursal,
    			'iva'				=> $row->iva,
    			'tiporequerimiento'	=> $row->tiporequerimiento,
    			'idprograma'		=> $row->idprograma,
    			'canreq'			=> $row->canreq,
    			'cansur'			=> $row->cansur,
    			'importe'			=> $row->importe,
    			'iva_producto'		=> $row->iva_producto,
    			'servicio'			=> $row->servicio,
    			'iva_servicio'		=> $row->iva_servicio,
    			'usuario'			=> $this->session->userdata('usuario')
    		);

    		$this->db->insert('remision', $data);

    		$remision = $this->db->insert_id();

    		$sql_update = "UPDATE receta r, receta_detalle d set remision = ? where r.consecutivo = d.consecutivo and fecha between ? and ? and clvsucursal = ? and iva = ? and tiporequerimiento = ? and idprograma = ?;";

    		$this->db->query($sql_update, array($remision, (string)$perini, (string)$perfin, (int)$clvsucursal, $iva, $tiporequerimiento, $idprograma));
    	}


		$this->db->trans_complete(); 
    }

    function getListadoRemisiones($clvsucursal)
    {
    	$sql = "SELECT r.*, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remisionStatus = 1 and clvsucursal = ?;";

		$query = $this->db->query($sql, array($clvsucursal));

		return $query;
    }

    function getRemisionesAll()
    {
        $sql = "SELECT r.*, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remisionStatus = 1
order by remision desc;";

        $query = $this->db->query($sql);

        return $query;
    }

    function getRemisionesFirmadasAll()
    {
        $sql = "SELECT r.*, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remisionStatus = 1 and firmada = 1 and facturada = 0
order by remision desc;";

        $query = $this->db->query($sql);

        return $query;
    }

    function getRemisionesFacturadasAll()
    {
        $sql = "SELECT r.*, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remisionStatus = 1 and firmada = 1 and facturada = 1
order by remision desc;";

        $query = $this->db->query($sql);

        return $query;
    }


    function getRemisionByRemision($remision)
    {
    	$sql = "SELECT r.*, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, anexo
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remision = ?;";

		$query = $this->db->query($sql, array($remision));

		return $query;
    }

    function  getRemisionCabeza($remision)
    {
        $query = $this->getRemisionByRemision($remision);
        $row = $query->row();
        
        $tabla = '<table>
            <tr>
                <td style="text-align: center; " colspan="6" ><b>'.REMISION_LINEA1.'</b></td>
                <td rowspan="3" style="text-align: right; font-size: large;">REMISION: <b>'.$row->remision.'</b><br />No. DE FACTURA<br />_________________</td>
            </tr>
            <tr>
                <td style="text-align: center; " colspan="6"><b>'.REMISION_LINEA2.'</b></td>
            </tr>
            <tr>
                <td style="text-align: center; " colspan="6"><b>'.REMISION_LINEA3.'</b></td>
            </tr>
            <tr><br />
                <td style="width: 7%;">UNIDAD: </td>
                <td style="width: 7%;"><b>'.$row->clvsucursal.'</b></td>
                <td style="width: 7%;">SUCURSAL: </td>
                <td style="width: 50%;"><b>'.($row->descsucursal).'</b></td>
                <td style="width: 8%;">SUMINISTRO: </td>
                <td style="width: 21%;"><b>'.($row->suministro).'</b></td>
            </tr>
            <tr>
                <td style="width: 12%;">REQUERIMIENTO: </td>
                <td style="width: 10%;"><b>'.$row->requerimiento.'</b></td>
                <td style="width: 10%;">PROGRAMA: </td>
                <td style="width: 39%;"><b>'.($row->programa).'</b></td>
                <td style="width: 8%;">PERIODO: </td>
                <td style="width: 21%;"><b>DEL '.$row->perini.' AL '.$row->perfin.'</b></td>
            </tr>
        </table>
        ';
        
        $tabla .= "
        <br />";
        
        $tabla .= "
        <table>
            <thead>
                <tr>
                    <th style=\"width: 6%;\"><b>Fecha</b></th>
                    <th style=\"width: 9%;\"><b>Folio</b></th>
                    <th style=\"width: 7%;\"><b>Cve. Pac.</b></th>
                    <th style=\"width: 13%;\"><b>Paciente</b></th>
                    <th style=\"width: 7%;\"><b>Cve. Medico</b></th>
                    <th style=\"width: 15%; text-align: left; \"><b>Medico</b></th>
                    <th style=\"width: 7%; text-align: left; \"><b>Cve. Art.</b></th>
                    <th style=\"width: 19%; text-align: left; \"><b>Descripcion</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>P. unitario</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>Cant. sol.</b></th>
                    <th style=\"width: 6%; text-align: right; \"><b>Cant. sur.</b></th>
                </tr>
            </thead>
            </table>";
        return $tabla;
    }
    
    function getRemisionDetalle($remision)
    {
        $sql = "SELECT fecha, folioreceta, cvepaciente, apaterno, amaterno, nombre, cvemedico, nombremedico, cvearticulo, concat(susa, ' ', descripcion, ' ', pres) as completo, precio, canreq, cansur
        FROM receta_detalle d
join receta r using(consecutivo)
join articulos a using(id)
where remision = ?
order by fecha, folioreceta, cvearticulo * 1;";

		$query = $this->db->query($sql, array($remision));
        
        return $query;
    }

    function getRemisionFirmas($clvsucursal)
    {
        $ext = $this->getSucursalesExt($clvsucursal);
        $director = null;
        $administrador = null;

        if($ext->num_rows()  > 0)
        {
            $e = $ext->row();
            $director = $e->director;
            $administrador = $e->administrador;
        }

        $tabla ="
        <table>
        </tfoot>
                <tr>
                    <td style=\"text-align: center; \">DIRECTOR</td>
                    <td style=\"text-align: center; \">ADMINISTRADOR</td>
                </tr>
                <tr>
                    <td style=\"text-align: center; \">".$director."</td>
                    <td style=\"text-align: center; \">".$administrador."</td>
                </tr>
                <tr>
                    <td style=\"text-align: center; \"><br /><br /><br /><br />______________________________________________</td>
                    <td style=\"text-align: center; \"><br /><br /><br /><br />______________________________________________</td>
                </tr>
                </tfoot>
                </table>";

        return $tabla;
    }

    function getSucursalesExt($clvsucursal)
    {
    	$sql = "SELECT * FROM sucursales_ext s where clvsucursal = ?;";
		$query = $this->db->query($sql, array($clvsucursal));
        
        return $query;
    }

    function getPanorama()
    {
    	$sql = "SELECT EXTRACT(year FROM fecha) as anio, EXTRACT(month FROM fecha) as mes, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, sum(canreq) as canreq, sum(cansur) as cansur, sum(cansur * precio) as importe, sum(case when iva = 0 then 0 else (cansur * precio * 0.16) end) as iva_producto, sum(cansur * servicio) as servicio, sum(cansur * servicio * 0.16) as iva_servicio
FROM receta r
join receta_detalle d using(consecutivo)
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on d.iva = u.cvesuministro
where remision = 0
group by anio, mes, clvsucursal, nivelatencion, iva, tiporequerimiento, idprograma
order by anio, mes, clvsucursal;";


		$query = $this->db->query($sql);
        
        return $query;
    }

    function existFactura($remision)
    {
        $this->db->where('remision', $remision);
        $query  = $this->db->get('remision_factura');

        return $query->num_rows();
    }

    function cancelaRemision($remision)
    {
        $this->db->trans_start();

        if($this->existFactura($remision) == 0)
        {
            $rem = $this->getRemisionByRemision($remision);
            $r = $rem->row();

            if($r->remisionStatus == 1)
            {
                $sql1 = "UPDATE receta_detalle set remision = 0 where remision = ?;";
                $this->db->query($sql1, array((int)$remision));

                $sql2 = "UPDATE remision set remisionStatus = 0 where remision = ?;";
                $this->db->query($sql2, array((int)$remision));
            }

        }else
        {

        }

        $this->db->trans_complete();
    }

    function verificaFirma($remision, $observaciones)
    {
        $data = array('firmada' => 1, 'observacionesFirma' => $observaciones, 'usuarioValidaFirma' => $this->session->userdata('usuario'));
        $this->db->set('fechaValidaFirma', 'now()', false);
        $this->db->update('remision', $data, array('remision' => $remision));
    }

    function getFacturasByRemision($remision)
    {
        $sql = "SELECT *
FROM remision_factura r
join remision_tipo_factura t using(tipoFactura)
where remision = ?;";
        $query = $this->db->query($sql, array((int)$remision));

        return $query;
    }

    function getFacturaJSON($contratoID, $remision, $tipoFactura)
    {
        $referencia = $this->getFacturaReferencia($contratoID, $remision, $tipoFactura);
        
        $contrato = $this->Catalogosweb_model->getContratoByContratoID($contratoID);
        $c = $contrato->row();
        
        $dat = array('rfc' => $c->rfc, 'idFactura' => 0);
        
        $productos = $this->getFacturaProductosByRemision($remision, $tipoFactura);
        
        $i = 0;
        
        foreach($productos->result() as $p)
        {
            $b[$i]['item'] = $i;
            $b[$i]['piezas'] = $p->cansur;
            $b[$i]['unidad'] = 'PIEZAS';
            $b[$i]['ean'] = $p->cvearticulo;
            $b[$i]['descripcion'] = $p->descripcion; 
            $b[$i]['precio'] = $p->precio;
            $b[$i]['iva'] = $p->iva;
            $i++;
        }
        
        $a = array();
        $a['json']['datos'] = $dat;
        $a['json']['referencia'] = $referencia;
        $a['json']['productos'] = $b;
        $a['json']['addenda'] = $this->getAddenda($contratoID, $remision, $tipoFactura);
        
        return json_encode($a);
    }

    function getClientesByRemision($remision)
    {
        $sql = "SELECT * FROM receptores_sucursal r
JOIN receptores e using(rfc)
where clvsucursal = (select clvsucursal from remision where remision = ?);";

        $query = $this->db->query($sql, (int)$remision);
        
        return $query;
    }
    
    function getClientesByRemisionCombo($remision)
    {
        $query = $this->getClientesByRemision($remision);
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $a[$row->rfc] = $row->rfc . ' - ' . $row->razon;
        }
        
        return $a;
    }

    function getFacturaProductosByRemision($remision, $tipoFactura)
    {
        switch ($tipoFactura) {
            case 1:

                $sql = "SELECT r.id, a.cvearticulo, a.clave, trim(concat(a.susa, ' ', a.descripcion, ' ', a.pres)) as descripcion, r.precio, sum(cansur) as cansur, iva
        FROM receta_detalle r
        join articulos a using(id)
        where remision = ?
        group by id
        order by cvearticulo * 1;";
                break;

            case 2:

                $sql = "SELECT sum(cansur) as cansur, '16000C' as cvearticulo, '16000C' as clave, 'SERVICIO' as descripcion, servicio as precio, 1 as iva
FROM receta_detalle r
where remision = ?;";
                break;
        }

        $query = $this->db->query($sql, array($remision));

        return $query;
    }

    function getFacturaReferencia($contratoID, $remision, $tipoFactura)
    {
        $referencia = null;
        $this->load->model('Catalogosweb_model');

        $contrato = $this->Catalogosweb_model->getContratoByContratoID($contratoID);
        $c = $contrato->row();

        $rem = $this->getRemisionByRemision($remision);
        $r = $rem->row();


        if($r->idprograma == 0 || $r->idprograma == 5)
        {
            $cliente = $c->razon;
        }else
        {
            $cliente = 'REGIMEN ESTATAL DE PROTECCION SOCIAL EN SALUD';
        }

        switch ($tipoFactura) {
            case 1:
                $facMedica = '';
                $referencia .= "FACTURA DEL SUMINISTRO Y DISTRIBUCION DE " . $r->suministro . " PRESTADO A " . $cliente . ", MISMA QUE AMPARA LA REMISION NO. " . $r->remision . " CORRESPONDIENTE AL PERIODO DEL " . $r->perini . " AL " . $r->perfin . " DEL " . $r->descsucursal . " CON CLAVE " . $r->clvsucursal . " DEL PROGRAMA " . $r->programa . " DEL ANEXO " .$r->anexo.", TIPO DE REMISION \"" . substr($r->requerimiento, 0, 1) . "\", NO. DE CONTRATO " . $c->numero;
                break;
            case 2:
                $facMedica = $this->getFacMedicaByRemision($remision);
                $referencia .= "FACTURA DEL SERVICIO DE DISTRIBUCION DE " . $r->suministro . " PRESTADO A " . $c->razon . ", MISMA QUE AMPARA LA REMISION NO. " . $r->remision . " DE LA FACTURA NO. " . $facMedica . " CORRESPONDIENTE AL PERIODO DEL " . $r->perini . " AL " . $r->perfin . " DEL " . $r->descsucursal . " CON CLAVE " . $r->clvsucursal . " DEL PROGRAMA " . $r->programa . " DEL ANEXO " .$r->anexo. ", TIPO DE REMISION \"" . substr($r->requerimiento, 0, 1) . "\", NO. DE CONTRATO " . $c->numero;
                break;
        }

        return $referencia;

    }

    function getFacMedicaByRemision($remision)
    {
        $sql = "SELECT numfac FROM remision_factura r where remision = ? and tipoFactura= 1 and statusFactura = 1;";
        $query = $this->db->query($sql, array($remision));

        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->numfac;
        }else
        {
            return null;
        }
    }

    function getAddenda($contratoID, $remision, $tipoFactura)
    {
        $this->load->model('Catalogosweb_model');

        $contrato = $this->Catalogosweb_model->getContratoByContratoID($contratoID);
        $c = $contrato->row();

        $rem = $this->getRemisionByRemision($remision);
        $r = $rem->row();

        switch ($tipoFactura) {
            case 1:
                $facMedica = '';
                break;
            case 2:
                $facMedica = $this->getFacMedicaByRemision($remision);
                break;
        }

        $addenda = array(
            'unidad'    => $r->descsucursal,
            'cveUnidad' => $r->clvsucursal,
            'anexo'     => $r->anexo,
            'contato'   => $c->numero,
            'programa'  => $r->programa,
            'tipo'      => substr($r->requerimiento, 0, 1),
            'facMedica' => $facMedica,
            'folio'     => $remision,
            'fechaIn'   => $r->perini,
            'fechaFin'  => $r->perfin,
            'remision'  => $remision
        );

        return $addenda;
    }

    function getFacturaRemota($contratoID, $remision, $tipoFactura)
    {
        $json = $this->getFacturaJSON($contratoID, $remision, $tipoFactura);

        $result = $this->util->postFacturarGeneral($json);

        if(isset($result->exito) && $result->exito == '1')
        {
            $this->saveFactura($result, $remision, $tipoFactura);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function saveFactura($result, $remision, $tipoFactura)
    {
        if($tipoFactura == 1)
        {
            $facturaProducto = '';
        }else
        {
            $facturaProducto = $this->getFacMedicaByRemision($remision);
        }

        $data = array(
            'remision'          => $remision,
            'tipoFactura'       => $tipoFactura,
            'f_id'              => $result->idFactura,
            'numfac'            => $result->factura,
            'xml'               => $result->urlxml,
            'pdf'               => $result->urlpdf,
            'facturaProducto'   => $facturaProducto,
            'fechaFactura'      => $result->fecha,
            'totalFactura'      => $result->totalFactura,
            'ivaFactura'        => $result->ivaFactura,
            'uuid'              => $result->uuid
        );
            
        $this->db->insert('remision_factura', $data);
    }

    function setFacturada($remision)
    {
        $data = array('facturada' => 1, 'usuarioFacturacion' => $this->session->userdata('usuario'));
        $this->db->set('fechaFacturacion', 'now()', false);
        $this->db->update('remision', $data, array('remision' => $remision));
    }

    function getFactura($remision_facturaID)
    {
        $this->db->where('remision_facturaID', $remision_facturaID);
        $query = $this->db->get('remision_factura');

        return $query;
    }

    function getPaquetes()
    {
        $sql = "SELECT movimientoID, referencia, colectivo, fecha, clvsucursalReferencia, descsucursal, sum(piezas) as piezas, sum(piezas * precioven) as importe, sum(case when tipoprod = 1 then piezas * precioven * 0.16 else 0 end) as iva, sum(piezas * servicio) as servicio, sum(piezas * servicio * 0.16) as iva_servicio
FROM movimiento m
join movimiento_detalle d using(movimientoID)
join articulos a using(id)
join sucursales s on s.clvsucursal = m.clvsucursalReferencia
where subtipoMovimiento = 22 and statusMovimiento = 1
group by movimientoID;";
        
        $query = $this->db->query($sql);

        return $query;
    }

    function getRecetas()
    {
        $sql = "SELECT clvsucursal, descsucursal, sum(cansur) as piezas, sum(cansur * precio) as importe, sum(case when iva = 1 then cansur * precio * 0.16 else 0 end) as iva, sum(cansur * servicio) as servicio, sum(cansur * servicio * 0.16) as iva_servicio
FROM receta r
join receta_detalle d using(consecutivo)
join sucursales s using(clvsucursal)
group by clvsucursal;";
        
        $query = $this->db->query($sql);

        return $query;
    }

    function actualizaFactura($arr)
    {
        $data = array('uuid' => $arr->uuid, 'statusFactura' => $arr->statusFactura);
        $this->db->update('remision_factura', $data, array('f_id' => $arr->f_id));
        return $data;
    }

    function getReporteFacturas()
    {
    	$sql = "SELECT remision_facturaID, numfac, uuid, clvsucursal, descsucursal, programa, perini, perfin, case when tipoFactura = 1 then suministro else tipoFacturaDescripcion end as concepto, totalFactura, ivaFactura, remision, case when statusFactura = 1 then 'ACTIVA' else 'CANCELADA' end as vigencia, DATEDIFF(perini, '1899-12-30') as fecha1, DATEDIFF(perfin, '1899-12-30') as fecha2
FROM remision_factura f
join remision r using(remision)
join sucursales s using(clvsucursal)
join programa p using(idprograma)
join remision_tipo_factura t using(tipoFactura)
join temporal_suministro u on r.iva = u.cvesuministro
;";
		
		$query = $this->db->query($sql);

		return $query;
    }

    function getReporteFacturasExcel()
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->library('excel');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
            die($cacheMethod . " caching method is not available" . EOL);
        }
        $this->load->model('almacen_model');
        $query = $this->getReporteFacturas();
        
            $hoja = 0;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja);
            $this->excel->getActiveSheet()->getTabColor()->setRGB('EAAC1C');
            $this->excel->getActiveSheet()->setTitle('REPORTE DE FACTURAS');
                            
            $this->excel->getActiveSheet()->mergeCells('A1:L1');
            $this->excel->getActiveSheet()->mergeCells('A2:L2');
            $this->excel->getActiveSheet()->mergeCells('A3:L3');
            $this->excel->getActiveSheet()->mergeCells('A4:L4');

            $this->excel->getActiveSheet()->setCellValue('A1', COMPANIA);
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('A2', REMISION_LINEA1);
            $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->setCellValue('A3', REMISION_LINEA2);
            $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->setCellValue('A4', "REPORTE DE FACTURAS");
            $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(15);
            $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);

            $num = 5;
            $data_empieza = $num + 1;
            
            $this->excel->getActiveSheet()->setCellValue('A'.$num, '#');
            $this->excel->getActiveSheet()->setCellValue('B'.$num, 'SERIE');
            $this->excel->getActiveSheet()->setCellValue('C'.$num, 'FOLIO');
            $this->excel->getActiveSheet()->setCellValue('D'.$num, 'FOLIO FISCAL');
            $this->excel->getActiveSheet()->setCellValue('E'.$num, '# SUCURSAL');
            $this->excel->getActiveSheet()->setCellValue('F'.$num, 'SUCURSAL');
            $this->excel->getActiveSheet()->setCellValue('G'.$num, 'COBERTURA');
            $this->excel->getActiveSheet()->setCellValue('H'.$num, 'FECHA INICIAL');
            $this->excel->getActiveSheet()->setCellValue('I'.$num, 'FECHA FINAL');
            $this->excel->getActiveSheet()->setCellValue('J'.$num, 'CONCEPTO');
            $this->excel->getActiveSheet()->setCellValue('K'.$num, 'TOTAL');
            $this->excel->getActiveSheet()->setCellValue('L'.$num, 'IVA');
            $this->excel->getActiveSheet()->setCellValue('M'.$num, 'REMISION');
            $this->excel->getActiveSheet()->setCellValue('N'.$num, 'VIGENCIA');
            
            $i = 1;
            
            if($query->num_rows() > 0)
            {
            
                
            foreach($query->result()  as $row)
            {                
                $num++;
                
                $this->excel->getActiveSheet()->setCellValue('A'.$num, $row->remision_facturaID);
                $this->excel->getActiveSheet()->setCellValue('B'.$num, preg_replace('/[0-9]/', '', $row->numfac));
                $this->excel->getActiveSheet()->setCellValue('C'.$num, preg_replace('/[A-Z]/', '', $row->numfac));
                $this->excel->getActiveSheet()->setCellValue('D'.$num, $row->uuid);
                $this->excel->getActiveSheet()->setCellValue('E'.$num, $row->clvsucursal);
                $this->excel->getActiveSheet()->setCellValue('F'.$num, $row->descsucursal);
                $this->excel->getActiveSheet()->setCellValue('G'.$num, $row->programa);
                $this->excel->getActiveSheet()->setCellValue('H'.$num, $row->fecha1);
                $this->excel->getActiveSheet()->setCellValue('I'.$num, $row->fecha2);
                $this->excel->getActiveSheet()->setCellValue('J'.$num, $row->concepto);
                $this->excel->getActiveSheet()->setCellValue('K'.$num, $row->totalFactura);
                $this->excel->getActiveSheet()->setCellValue('L'.$num, $row->ivaFactura);
                $this->excel->getActiveSheet()->setCellValue('M'.$num, $row->remision);
                $this->excel->getActiveSheet()->setCellValue('N'.$num, $row->vigencia);
                //
                //$this->excel->getActiveSheet()->getRowDimension($num)->setRowHeight(20);
                //$this->excel->getActiveSheet()->getRowDimension($num)->setVisible(true);
                //$this->excel->getActiveSheet()->setCellValue('m'.$num, '=H'.$num.'*L'.$num);
                
                if($row->vigencia == 'ACTIVA')
                {
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => '5BD244'
                        )
                    ));
                }else{
                    $this->excel->getActiveSheet()->getStyle('A' . $num . ':N' . $num)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                             'rgb' => 'FF6961'
                        )
                    ));
                }
                
                $i++;
                
            }
            
            $data_termina = $num;
            
            $this->excel->getActiveSheet()->setCellValue('K'.($data_termina + 1), '=sum(K'.$data_empieza.':K'.$data_termina.')');
            $this->excel->getActiveSheet()->setCellValue('L'.($data_termina + 1), '=sum(L'.$data_empieza.':L'.$data_termina.')');
            
            $this->excel->getActiveSheet()->getStyle('K'.$data_empieza.':K'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');
            $this->excel->getActiveSheet()->getStyle('L'.$data_empieza.':L'.($data_termina + 1))->getNumberFormat()->setFormatCode('#,##0');

            $this->excel->getActiveSheet()->getStyle('H'.$data_empieza.':H'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $this->excel->getActiveSheet()->getStyle('I'.$data_empieza.':I'.($data_termina + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            
            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            
            $this->excel->getActiveSheet()->getStyle('A'.$data_empieza.':N'.$data_termina)->getAlignment()->setWrapText(true);
            
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );
            
            $this->excel->getActiveSheet()->getStyle('A'.($data_empieza - 1).':N'.($data_termina + 1))->applyFromArray($styleArray);
            
            $this->excel->getActiveSheet()->freezePaneByColumnAndRow(0, $data_empieza);
            $this->excel->getActiveSheet()->setAutoFilter('A'.($data_empieza - 1).':N'.($data_termina));
            
            
            }
            $hoja++;
    }

    function getTotalesByRequerimiento()
    {
        $sql = "SELECT tiporequerimiento, requerimiento, count(*) as cuenta, sum(canreq) as canreq, sum(cansur) as cansur, sum(subtotal) as total, (sum(cansur) / sum(canreq)) * 100 as abasto
        FROM receta r
join receta_detalle_concentrado d using(consecutivo)
join temporal_requerimiento q using(tiporequerimiento)
group by tiporequerimiento;";
        
        $query = $this->db->query($sql);

        return $query;
    }

}