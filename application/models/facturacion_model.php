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
    	$sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio, remisionStatus, firmada, facturada, observacionesFirma
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
        $sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio, remisionStatus, firmada, facturada, observacionesFirma
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
        $sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio, remisionStatus, firmada, facturada, observacionesFirma
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where remisionStatus = 1 and firmada = 1
order by remision desc;";

        $query = $this->db->query($sql);

        return $query;
    }

    function getRemisionByRemision($remision)
    {
    	$sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio, remisionStatus, firmada, facturada, observacionesFirma
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
where remision = ?;";

		$query = $this->db->query($sql, array($remision));
        
        return $query;
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

        switch ($tipoFactura) {
            case 1:
                $referencia .= "FACTURA DEL SUMINISTRO Y DISTRIBUCION DE " . $r->suministro . " PRESTADO A " . $c->razon . ", MISMA QUE AMPARA LA REMISION NO. " . $r->remision . " CORRESPONDIENTE AL PERIODO DEL " . $r->perini . " AL " . $r->perfin . " DEL " . $r->descsucursal . " CON CLAVE " . $r->clvsucursal . " DEL PROGRAMA " . $r->programa . ", TIPO DE REMISION \"" . substr($r->requerimiento, 0, 1) . "\"";
                break;
            case 2:
                $referencia .= "FACTURA DEL SERVICIO DE DISTRIBUCION DE " . $r->suministro . " PRESTADO A " . $c->razon . ", MISMA QUE AMPARA LA REMISION NO. " . $r->remision . " CORRESPONDIENTE AL PERIODO DEL " . $r->perini . " AL " . $r->perfin . " DEL " . $r->descsucursal . " CON CLAVE " . $r->clvsucursal . " DEL PROGRAMA " . $r->programa . ", TIPO DE REMISION \"" . substr($r->requerimiento, 0, 1) . "\"";
                break;
        }

        return $referencia;

    }

    function getFacturaRemota($contratoID, $remision, $tipoFactura)
    {
        $json = $this->getFacturaJSON($contratoID, $remision, $tipoFactura);

        $result = $this->util->postFacturarGeneral($json);

        if(isset($result->exito) && $result->exito == '1')
        {
            $this->saveFactura($result, $remision, $tipoFactura);
        }
        else
        {

        }
    }

    function saveFactura($result, $remision, $tipoFactura)
    {
        if($tipoFactura == 1)
        {
            $facturaProducto = '';
        }else
        {
            $facturaProducto = '';
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
        );
            
        $this->db->insert('remision_factura', $data);
    }

    function getFactura($remision_facturaID)
    {
        $this->db->where('remision_facturaID', $remision_facturaID);
        $query = $this->db->get('remision_factura');

        return $query;
    }

}