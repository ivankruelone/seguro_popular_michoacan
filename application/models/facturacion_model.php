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
    	$sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio
FROM remision r
join sucursales s using(clvsucursal)
join temporal_nivel_atencion a using(nivelatencion)
join programa p using(idprograma)
join temporal_requerimiento q using(tiporequerimiento)
join temporal_suministro u on r.iva = u.cvesuministro
where clvsucursal = ?;";

		$query = $this->db->query($sql, array($clvsucursal));

		return $query;
    }

    function getRemisionByRemision($remision)
    {
    	$sql = "SELECT remision, perini, perfin, nivelatencion, iva, tiporequerimiento, idprograma, clvsucursal, descsucursal, nivelatenciondescripcion, suministro, requerimiento, programa, canreq, cansur, importe, iva_producto, servicio, iva_servicio
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

}
