<?php
class Movimiento_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */
     
    var $urlExchange;
    var $url;
    var $formato_datos = "/format/json";
    var $json;
    
    function __construct()
    {
        parent::__construct();
        $this->urlExchange = 'http://almacenoaxaca.homeip.net/index.php/Exchange/';
        $this->url = "http://189.203.201.184/oaxacacentral/index.php/catalogos/";
    }

    
    function __get_data($url, $referencia)
    {
        
    	$ch = curl_init();
    	$timeout = 2;
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    	$data = curl_exec($ch);
    	curl_close($ch);
        
        
        
    	return $data;
    
    }

    function __getData($url)
    {
        
    	$ch = curl_init();
    	$timeout = 2;
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    	$json = curl_exec($ch);
    	curl_close($ch);
        $data = json_decode($json);
        
    	return $data;
    
    }

    function __getCatalogo($cat, $referencia)
    {
        $suc = '/referencia/'.$referencia;
        return $this->url.$cat.$suc.$this->formato_datos;
    }

    function hola()
    {
        return null;
    }
    
    function getSubtipoMovimientoByMovimientoID($movimientoID)
    {
        $this->db->select('subtipoMovimiento');
        $this->db->where('movimientoID', $movimientoID);
        $query = $this->db->get('movimiento');
        
        $row = $query->row();
        return $row->subtipoMovimiento;
    }
    
    function getInventarioBySubtipoMovimiento($movimientoID, $areaID)
    {
        $subtipoMovimiento = $this->getSubtipoMovimientoByMovimientoID($movimientoID);
        
        if($subtipoMovimiento == 6)
        {
            $sql = "SELECT area, inventarioID, cvearticulo, susa, descripcion, pres, cantidad, lote, caducidad
FROM inventario i
join articulos a using(id)
join ubicacion u using(ubicacion)
where cantidad > 0 and caducidad <= date(now()) and areaID = ?
order by susa, descripcion;";
        }else{
            $sql = "SELECT area, inventarioID, cvearticulo, susa, descripcion, pres, cantidad, lote, caducidad
FROM inventario i
join articulos a using(id)
join ubicacion u using(ubicacion)
where cantidad > 0 and caducidad >= date(now()) and areaID = ?
order by susa, descripcion;";
        
        }
        
        $query = $this->db->query($sql, $areaID);
        
        return $query;
    }
    
    function getMovimientosCuenta($tipoMovimiento, $subtipoMovimiento)
    {
        $sql = "SELECT count(*) as cuenta
FROM movimiento m
join tipo_movimiento t using(tipoMovimiento)
join subtipo_movimiento s using(subtipoMovimiento)
join movimiento_status a using(statusMovimiento)
join sucursales s1 using(clvsucursal)
join sucursales s2 on m.clvsucursalReferencia = s2.clvsucursal
join proveedor p using(proveedorID)
join usuarios u using(usuario)
where m.tipoMovimiento = ? and m.subtipoMovimiento = ? and m.clvsucursal = ?;";
        $query = $this->db->query($sql, array($tipoMovimiento, $subtipoMovimiento, $this->session->userdata('clvsucursal')));
        $row = $query->row();
        return $row->cuenta;
    }
    
    function getMovimientos($tipoMovimiento, $subtipoMovimiento, $limit, $offset = 0)
    {
        $sql = "SELECT movimientoID, statusMovimiento, statusPrepedido, asignaFactura, observaciones, tipoMovimientoDescripcion, subtipoMovimientoDescripcion, orden, referencia, fecha, razon, s1.descsucursal as sucursal, s2.descsucursal as sucursal_referencia, nombreusuario, fechaAlta, fechaCierre, fechaCancelacion, idFactura, folioFactura, fechaFactura, urlpdf, urlxml, IFNULL(o.programa, 'TODAS') as programa, colectivo, statusMovimientoDescripcion
        FROM movimiento m
join tipo_movimiento t using(tipoMovimiento)
join subtipo_movimiento s using(subtipoMovimiento)
join movimiento_status a using(statusMovimiento)
join sucursales s1 using(clvsucursal)
join sucursales s2 on m.clvsucursalReferencia = s2.clvsucursal
join proveedor p using(proveedorID)
join usuarios u using(usuario)
left join programa o on m.cobertura = o.idprograma
where m.tipoMovimiento = ? and m.subtipoMovimiento = ? and m.clvsucursal = ?
order by m.movimientoID desc
limit ? offset ?
;";

        $query = $this->db->query($sql, array($tipoMovimiento, $subtipoMovimiento, $this->session->userdata('clvsucursal'), (int)$limit, (int)$offset));
        return $query;
    }
    

    
    function getMovimientoByMovimientoID($movimientoID)
    {
        $sql = "SELECT m.tipoMovimiento, m.subtipoMovimiento, m.statusMovimiento, remision, movimientoID, statusMovimiento, observaciones, tipoMovimientoDescripcion, subtipoMovimientoDescripcion, orden, nuevo_folio, referencia, fecha, razon, clvsucursalReferencia, s1.descsucursal as sucursal, s2.descsucursal as sucursal_referencia, clvsucursalReferencia, m.clvsucursal, nombreusuario, fechaAlta, fechaCierre, fechaCancelacion, upper(concat(s2.calle, ', ', s2.colonia, ', C. P. ', s2.cp, ', ', s2.municipio)) as domicilio, idFactura, folioFactura, urlxml, urlpdf, fechaFactura, year(fecha) as anio, month(fecha) as mes, s3.nombreSucursalPersonalizado, s3.domicilioSucursalPersonalizado, s2.numjurisd, j.jurisdiccion, IFNULL(o.programa, 'TODAS') as programa, m.cobertura, s2.nivelatencion as nivelatencionReferencia, colectivo
        FROM movimiento m
join tipo_movimiento t using(tipoMovimiento)
join subtipo_movimiento s using(subtipoMovimiento)
join movimiento_status a using(statusMovimiento)
join sucursales s1 using(clvsucursal)
join sucursales s2 on m.clvsucursalReferencia = s2.clvsucursal
left join sucursales_ext s3 on m.clvsucursalReferencia = s3.clvsucursal
left join jurisdiccion j on s2.numjurisd = j.numjurisd
join proveedor p using(proveedorID)
join usuarios u using(usuario)
left join programa o on m.cobertura = o.idprograma
where m.movimientoID = ? and m.clvsucursal = ?;";

        $query = $this->db->query($sql, array($movimientoID, $this->session->userdata('clvsucursal')));
        return $query;
    }

    function getMovimiento($movimientoID)
    {
        $this->db->where('movimientoID', $movimientoID);
        $query = $this->db->get('movimiento');
        return $query;
    }
    
    function insertMovimiento($tipoMovimiento, $subtipoMovimiento, $fecha, $orden, $referencia, $sucursal_referencia, $proveedor, $observaciones, $remision, $idprograma, $colectivo)
    {

        if($subtipoMovimiento == 22 && $referencia == 'AUTO')
        {
            $referencia = $this->getFolioPaquete();
        }

        $data = array(
            'tipoMovimiento'    => $tipoMovimiento,
            'subtipoMovimiento' => $subtipoMovimiento,
            'orden'             => $orden,
            'referencia'        => $referencia,
            'fecha'             => $fecha,
            'statusMovimiento'  => 0,
            'proveedorID'       => $proveedor,
            'clvsucursal'       => $this->session->userdata('clvsucursal'),
            'clvsucursalReferencia' => $sucursal_referencia,
            'usuario'           => $this->session->userdata('usuario'),
            'observaciones'     => $observaciones,
            'remision'          => $remision,
            'cobertura'         => $idprograma,
            'colectivo'         => strtoupper($colectivo)
            );
        
        $this->db->set('fechaAlta', 'now()', false);
        $this->db->insert('movimiento', $data);
        $movimientoID = $this->db->insert_id();
        
        if($movimientoID > 0 && $orden > 0)
        {
            $this->getProductosFolprv($orden);
        }
        
        if($sucursal_referencia == 19000 && $tipoMovimiento == 1 && $subtipoMovimiento == 2)
        {
            //$this->cargaPedido($referencia, $movimientoID);
        }elseif($sucursal_referencia <> 19000 && $tipoMovimiento == 1 && $subtipoMovimiento == 2)
        {
            //$this->cargaPedidoUnidades($referencia, $movimientoID);
        }

        return $movimientoID;
    }
    
    function getLotes($cvearticulo)
    {
        $sql = "SELECT inventarioID, lote, caducidad, cantidad, area, pasillo
        FROM inventario i 
        join articulos a using(id)
        left join ubicacion u using(ubicacion)
        where cvearticulo = ? and i.clvsucursal = ? 
        having cantidad > 0 
        order by pasilloTipo, caducidad;";
        $query = $this->db->query($sql, array((string)$cvearticulo, (int)$this->session->userdata('clvsucursal')));
        return $query;
    }
    
    function getLotesAunCaducados($cvearticulo)
    {
        $sql = "SELECT inventarioID, lote, caducidad, cantidad FROM inventario i join articulos a using(id) where cvearticulo = ? and clvsucursal = ? having cantidad > 0 order by caducidad;";
        $query = $this->db->query($sql, array($cvearticulo, $this->session->userdata('clvsucursal')));
        return $query;
    }

    function updateMovimiento($tipoMovimiento, $subtipoMovimiento, $fecha, $orden, $referencia, $sucursal_referencia, $proveedor, $observaciones, $idprograma, $movimientoID)
    {
        $data = array(
            'tipoMovimiento'    => $tipoMovimiento,
            'subtipoMovimiento' => $subtipoMovimiento,
            'orden'             => $orden,
            'referencia'        => $referencia,
            'fecha'             => $fecha,
            'proveedorID'       => $proveedor,
            'clvsucursal'       => $this->session->userdata('clvsucursal'),
            'clvsucursalReferencia' => $sucursal_referencia,
            'usuario'           => $this->session->userdata('usuario'),
            'observaciones'     => $observaciones,
            'cobertura'         => $idprograma
            );
        
        $this->db->update('movimiento', $data, array('movimientoID' => $movimientoID));
    }
    
    function getArticuloByClave($cvearticulo)
    {
        $sql = "SELECT * FROM articulos a where id in(SELECT id FROM inventario i where ean = ? and ean > 0);";
        $query2 = $this->db->query($sql, (string)$cvearticulo);
        
        if($query2->num_rows() > 0)
        {
            return $query2;
        }else{
            $this->db->where('cvearticulo', (string)$cvearticulo);
            $query = $this->db->get('articulos');
            return $query;
        }
        
        
    }

    function getArticuloByClaveSalida($cvearticulo, $nivelatencionReferencia, $cobertura)
    {
        if($cobertura == 100)
        {
            $filtro = null;
        }else
        {
            $filtro = ' and idprograma = ' . (int)$cobertura;
        }

        $sql = "SELECT * 
        FROM articulos a 
        join articulos_cobertura c using(id)
        where id in(SELECT id FROM inventario i where ean = ? and ean > 0) and activo = 1 and nivelatencion = ? $filtro
        group by id;";
        $query2 = $this->db->query($sql, array((string)$cvearticulo, $nivelatencionReferencia));
        
        if($query2->num_rows() > 0)
        {
            return $query2;
        }else{
            $sql3 = "SELECT a.*
from articulos a
join articulos_cobertura c using(id)
where activo = 1 and cvearticulo = ? and nivelatencion = ? $filtro
group by id;";
            $query3 = $this->db->query($sql3, array((string)$cvearticulo, (int)$nivelatencionReferencia));
            
            return $query3;
        }
        
        
    }

    function insertDetalle($movimientoID, $id, $piezas, $costo, $lote, $caducidad, $ean, $marca, $ubicacion, $comercial)
    {
        $query = $this->getArticuloByClave($id);
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            
            if($row->activo == 1)
            {
            $data = array(
                'movimientoID'  => $movimientoID,
                'id'            => $row->id,
                'piezas'        => $piezas,
                'costo'         => $costo,
                'lote'          => $lote,
                'caducidad'     => $caducidad,
                'ean'           => $ean,
                'marca'         => $marca,
                'ubicacion'     => $ubicacion,
                'comercial'     => $comercial
                );
            
            $this->db->insert('movimiento_detalle', $data);
            }else{
                
            }
            
        }else{
            
        }
    }
    
    function getInventarioByID($inventarioID)
    {
        $this->db->where('clvsucursal', $this->session->userdata('clvsucursal'));
        $this->db->where('inventarioID', $inventarioID);
        $query = $this->db->get('inventario');
        return $query;
    }
    
    function insertDetalle2($movimientoID, $inventarioID, $piezas)
    {
        $query = $this->getInventarioByID($inventarioID);
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            
            if($row->cantidad < $piezas)
            {
                $this->session->set_flashdata('error', 'Hay menos piezas en el inventario con ese lote, de las ' . $piezas. ' que requieres solo se pueden surtir ' . $row->cantidad . '.');
                $piezas = $row->cantidad;
                
            }
            
            $data = array(
                'movimientoID'  => $movimientoID,
                'id'            => $row->id,
                'piezas'        => $piezas,
                'costo'         => $row->costo,
                'lote'          => $row->lote,
                'caducidad'     => $row->caducidad,
                'ean'           => $row->ean,
                'marca'         => $row->marca,
                'ubicacion'     => $row->ubicacion,
                'comercial'     => $row->comercial
                );
            
            $this->db->insert('movimiento_detalle', $data);
        }else{
            
        }
    }

    function insertDetalle3($movimientoID, $cveArticulo, $piezas)
    {
            
        $query = $this->getArticuloByClave($cveArticulo);
            
        if($query->num_rows() > 0)
        {
            
            $row = $query->row();    
            
            $data = array(
                'movimientoID'  => $movimientoID,
                'id'            => $row->id,
                'piezas'        => $piezas
                );
            
            $this->db->insert('movimiento_prepedido', $data);
        }
    }

    function getDetalle($movimientoID)
    {
        $sql = "SELECT m.*, area, cvearticulo, susa, descripcion, pres, statusMovimiento, tipoprod, tipoMovimiento, subtipoMovimiento
        FROM movimiento_detalle m
join articulos a using(id)
join movimiento o using(movimientoID)
left join ubicacion u using(ubicacion)
where movimientoID = ?
order by a.tipoprod, a.cvearticulo * 1 asc;";
        $query = $this->db->query($sql, $movimientoID);
        return $query;
    }
    
    function getDetallePrepedido($movimientoID)
    {
        $sql = "SELECT m.*, cvearticulo, susa, descripcion, pres, statusMovimiento, tipoprod, tipoMovimiento, subtipoMovimiento, case when nivelatencion is null then 0 else 1 end as cubierto
FROM movimiento_prepedido m
join articulos a using(id)
join movimiento o using(movimientoID)
left join cobertura c on m.id = c.id and nivelatencion = (SELECT nivelAtencion
FROM movimiento m
join sucursales s on m.clvsucursalReferencia = s.clvsucursal
where movimientoID = ?)
where movimientoID = ?;";
        $query = $this->db->query($sql, array($movimientoID, $movimientoID));
        return $query;
    }

    function getDetalleByMovimientoDetalle($movimientoDetalle)
    {
        $sql = "SELECT m.*, referencia, cvearticulo, susa, descripcion, pres, statusMovimiento, tipoprod, tipoMovimiento, subtipoMovimiento, clvsucursal
        FROM movimiento_detalle m
join articulos a using(id)
join movimiento o using(movimientoID)
where movimientoDetalle = ?;";
        $query = $this->db->query($sql, $movimientoDetalle);
        return $query;
    }
    
    function eliminaDetalle($movimientoDetalle)
    {
        $this->db->delete('movimiento_detalle', array('movimientoDetalle' => $movimientoDetalle));
    }
    
    function eliminaDetallePrepedido($movimientoPrepedido)
    {
        $this->db->delete('movimiento_prepedido', array('movimientoPrepedido' => $movimientoPrepedido));
    }

    function getArticuloDatos($cvearticulo, $orden)
    {
        $query = $this->getArticuloByClave($cvearticulo);
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            if($row->activo == 1)
            {
                
                $datos = $this->getOrdenDetalleByClave($orden, $cvearticulo);
                
                if(isset($datos->error) || $orden == 0)
                {
                    return $row->id.'|'.$row->cvearticulo.'|'.$row->susa.'|'.$row->descripcion.'|'.$row->pres.'|-1|0|0';
                }else{
                    
                    foreach($datos as $datos)
                    {
                        
                    }
                    return $row->id.'|'.$row->cvearticulo.'|'.$row->susa.'|'.$row->descripcion.'|'.$row->pres.'|'.($datos->cans - $row->aplica).'|'.$datos->codigo.'|'.$datos->costo;
                }
                
                
                
            }else{
                return '0|0|NO ENCONTRADO|NO ENCONTRADO|NO ENCONTRADO|-1|0|0';
            }
            
        }else{
            return '0|0|NO ENCONTRADO|NO ENCONTRADO|NO ENCONTRADO|-1|0|0';
        }
    }
    
    function getArticuloDatosSalida($cvearticulo, $nivelatencionReferencia, $cobertura, $orden = 0)
    {
        $query = $this->getArticuloByClaveSalida($cvearticulo, $nivelatencionReferencia, $cobertura);
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            if($row->activo == 1)
            {
                
                return $row->id.'|'.$row->cvearticulo.'|'.$row->susa.'|'.$row->descripcion.'|'.$row->pres.'|-1|0|0';
                
            }else{
                return '0|0|NO ENCONTRADO O FUERA DE COBERTURA|NO ENCONTRADO|NO ENCONTRADO|-1|0|0';
            }
            
        }else{
            return '0|0|NO ENCONTRADO O FUERA DE COBERTURA|NO ENCONTRADO|NO ENCONTRADO|-1|0|0';
        }
    }

    function getMarca($ean)
    {
        $sql = "SELECT ean, marca FROM inventario i where ean = ? group by ean;";
        $query = $this->db->query($sql, (double)$ean);
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->ean . '|' . $row->marca;
        }else{
            return '|';
        }
    }
    
    function getOrdenDetalleByClave($folprv, $clave)
    {
        $clave = str_replace('/', 'diagonal', $clave);
        if(PATENTE == 1)
        {
            return $this->util->getDataOficina('ordenDetalleCodigo', array('folprv' => $folprv, 'codigo' => $clave));
        }else{
            return $this->util->getDataOficina('ordenDetalleClave', array('folprv' => $folprv, 'clave' => $clave));
        }
    }
    
    function getArticulosJSON($term)
    {
        $this->load->library('Services_JSON');
        $j = new Services_JSON();
        
        $sql = "select * from articulos where (id like '%$term%' or cvearticulo like '%$term%' or susa like '%$term%' or descripcion like '%$term%') and activo = 1  limit 20;";
        $query = $this->db->query($sql);
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $b = array('id' => $row->id, 'cvearticulo' => $row->cvearticulo, 'susa' => $row->susa, 'descripcion' => $row->descripcion, 'pres' => $row->pres, 'value' => $row->cvearticulo.'|'.$row->cvearticulo.'|'.$row->susa.'|'.$row->descripcion.'|'.$row->pres);
            array_push($a, $b);
        }
        return $j->encode($a);
        
    }
    
    function getArticulosJSONSalida($term, $nivelatencionReferencia, $cobertura)
    {
        $this->load->library('Services_JSON');
        $j = new Services_JSON();

        if($cobertura == 100)
        {
            $filtro = null;
        }else
        {
            $filtro = ' and idprograma = ' . (int)$cobertura;
        }
        
        $sql = "select * 
        from articulos a
        join articulos_cobertura c using(id)
        where (id like '%$term%' or cvearticulo like '%$term%' or susa like '%$term%' or descripcion like '%$term%') and activo = 1 and nivelatencion = ? $filtro
        group by id
        limit 20;";
        $query = $this->db->query($sql, array($nivelatencionReferencia));
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $b = array('id' => $row->id, 'cvearticulo' => $row->cvearticulo, 'susa' => $row->susa, 'descripcion' => $row->descripcion, 'pres' => $row->pres, 'value' => $row->cvearticulo.'|'.$row->cvearticulo.'|'.$row->susa.'|'.$row->descripcion.'|'.$row->pres);
            array_push($a, $b);
        }
        return $j->encode($a);
        
    }

    function getProveedorJSON($term)
    {
        $this->load->library('Services_JSON');
        $j = new Services_JSON();
        
        $sql = "SELECT * FROM proveedor p where proveedorID like '%$term%' or rfc like '%$term%' or razon like '%$term%' limit 20;";
        $query = $this->db->query($sql);
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $b = array('proveedorID' => $row->proveedorID, 'rfc' => $row->rfc, 'razon' => $row->razon, 'value' => $row->proveedorID.'|'.$row->rfc.'|'.$row->razon);
            array_push($a, $b);
        }
        return $j->encode($a);
        
    }

    function getSucursalJSON($term)
    {
        $this->load->library('Services_JSON');
        $j = new Services_JSON();
        
        $sql = "SELECT * FROM sucursales s where clvsucursal like '%$term%' or descsucursal like '%$term%' limit 20;";
        $query = $this->db->query($sql);
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $b = array('clvsucursal' => $row->clvsucursal, 'descsucursal' => $row->descsucursal, 'value' => $row->clvsucursal.'|'.$row->descsucursal);
            array_push($a, $b);
        }
        return $j->encode($a);
        
    }

    function getEmbarque($movimientoID)
    {
        $this->db->where('movimientoID', $movimientoID);
        $query = $this->db->get('movimiento_embarque');
        return $query;
    }
    
    function replaceEmbarque($movimientoID, $embarco, $operador, $unidad, $placas, $cajas = 0, $hieleras = 0, $surtio, $valido, $observaciones)
    {
        $data = array(
            'movimientoID' => $movimientoID,
            'embarco' => $embarco,
            'operador' => $operador,
            'unidad' => $unidad,
            'placas' => $placas,
            'cajas' => $cajas,
            'hieleras' => $hieleras,
            'surtio' => $surtio,
            'valido' => $valido,
            'observaciones' => $observaciones
            );
            
            $this->db->replace('movimiento_embarque', $data);
    }

    function header($movimientoID)
    {
        $query = $this->getMovimientoByMovimientoID($movimientoID);
        $row = $query->row();

        
        $logo = array(
                                  'src' => base_url().'assets/img/logo.png',
                                  'width' => '120'
                        );
                        
        if($row->tipoMovimiento == 1)
        {

            $suc = "DESTNO";
            $suc_ref = "ORIGEN";

        }elseif($row->tipoMovimiento == 2)
        {

            $suc = "ORIGEN";
            $suc_ref = "DESTINO";

        }else
        {
            $suc = "";
            $suc_ref = "";
        }

        if($row->subtipoMovimiento == 22)
        {
            $eti_referencia = 'FOLIO: ';
            $eti_colectivo = 'COLECTIVO: ';
            $dato_colectivo = $row->colectivo;
        }else
        {
            $eti_referencia = 'REFERENCIA: ';
            $eti_colectivo = 'Fol CxP: ';
            $dato_colectivo = $row->nuevo_folio;
        }


        
        
        $tabla = '<table cellpadding="1">
            <tr>
                <td rowspan="8" width="100px">'.img($logo).'</td>
                <td rowspan="8" width="450px" align="center"><font size="8">'.COMPANIA.'<br />'.$suc.': '.$row->sucursal.'<br />MOVIMIENTO: '.$row->tipoMovimientoDescripcion.' - '.$row->subtipoMovimientoDescripcion.'<br />PROVEEDOR: '.$row->razon.'<br />'.$suc_ref.': '.$row->sucursal_referencia.'<br />JURISDICCION: '.$row->numjurisd.' - '.$row->jurisdiccion.'<br />COBERTURA: '.$row->programa.'<br />Observaciones: '.$row->observaciones .'</font><br />Referencia: '.barras($row->referencia).'</td>
                <td width="75px">ID Movimiento: </td>
                <td width="95px" align="right">'.$row->movimientoID.'</td>
            </tr>
            <tr>
                <td width="75px">FECHA: </td>
                <td width="95px" align="right">'.$row->fecha.'</td>
            </tr>
            <tr>
                <td width="75px">'.$suc_ref.': </td>
                <td width="95px" align="right">'.$row->clvsucursalReferencia.'</td>
            </tr>
            <tr>
                <td width="75px">Orden: </td>
                <td width="95px" align="right">'.$row->orden.'</td>
            </tr>
            <tr>
                <td width="75px">'.$eti_referencia.'</td>
                <td width="95px" align="right">'.$row->referencia.'</td>
            </tr>
            <tr>
                <td width="75px">Remision: </td>
                <td width="95px" align="right">'.$row->remision.'</td>
            </tr>
            <tr>
                <td width="75px">'.$eti_colectivo.'</td>
                <td width="95px" align="right">'.$dato_colectivo.'</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">ID: '.barras($row->movimientoID).'</td>
            </tr>
        </table>';
        
        return $tabla;
    }

    function headerExcedente($movimientoID)
    {
        $query = $this->getMovimientoByMovimientoID($movimientoID);
        $row = $query->row();

        
        $logo = array(
                                  'src' => base_url().'assets/img/logo.png',
                                  'width' => '120'
                        );
                                
        $tabla = '<table cellpadding="1">
            <tr>
                <td width="100px">'.img($logo).'</td>
                <td width="620px" align="center"><font size="12">'.COMPANIA.'<br />TRASPASO DE EXCEDENTES<br />SISTEMA DE CALIDAD NMX-CC-9001-IMNC-2008</font></td>
            </tr>
        </table>
        <br />
        <table>
            <tr>
                <td width="100px">NO. SUCURSAL</td>
                <td width="620px" align="center">NOMBRE DE LA SUCURSAL</td>
            </tr>
            <tr>
                <td width="100px">'.$row->clvsucursal.'</td>
                <td width="620px" align="center">'.$row->sucursal.'</td>
            </tr>
        </table>';
        
        return $tabla;
    }

    function detalle($movimientoID)/*HOJA DE PEDIDO hoja 1*/
    {
        $query = $this->getDetalle($movimientoID);

        $subtipoMovimiento = $this->getSubtipoMovimientoByMovimientoID($movimientoID);

        
        $tabla = '
        <style>
        table
        {
        	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        }
        th
        {
        	font-weight: normal;
        	border-bottom: 2px solid #000000;
        }
        td
        {
        	border-bottom: 1px solid #000000;
        }
        </style>';
        
        $tabla.= '<table cellpadding="4">
         
        <thead>
        

              
          
            <tr>
                <th width="50px">Clave</th>
                <th width="130px">Nom. Generico</th>
                <th width="160px">Descripci&oacute;n</th>
                <th width="130px">Presentacion</th>
                <th width="50px" align="right">Costo</th>
                <th width="40px" align="right">Sur.</th>
                <th width="50px" align="right">Importe</th>
                <th width="50px" align="right">IVA</th>
                <th width="50px" align="right">LOT CAD</th>
            </tr>
        </thead>
        <tbody>
        ';

        $importeTotal = 0;
        $piezas = 0;
        $ivaTotal = 0;
        $total = 0;

        foreach($query->result() as $row)
        {

            if($subtipoMovimiento == 1 || $subtipoMovimiento == 3)
            {
               
            }else
            {
                $row->costo = 0;
            }

            $importe = $row->costo * $row->piezas;
            
            if($row->tipoprod == 0)
            {
                $iva = 0;
            }else{
                $iva = $row->costo * $row->piezas * IVA;
            }
            
            $subtotal = $importe + $iva;
            
            
            $tabla.= '<tr>
                <td width="50px">'.$row->cvearticulo.'</td>
                <td width="130px">'.trim($row->comercial.' '.$row->susa).'</td>
                <td width="160px">'.$row->descripcion.'</td>
                <td width="130px">'.$row->pres.'</td>
                <td width="50px" align="right">'.number_format($row->costo, 2).'</td>
                <td width="40px" align="right">'.number_format($row->piezas, 0).'</td>
                <td width="50px" align="right">'.number_format($importe, 2).'</td>
                <td width="50px" align="right">'.number_format($iva, 2).'</td>
                <td width="50px" align="left">'.$row->lote.' - '.formato_caducidad($row->caducidad).'</td>
            </tr>
            ';


            $importeTotal = $importeTotal + $importe;
            $piezas = $piezas + $row->piezas;
            $ivaTotal = $ivaTotal + $iva;
            $total = $total + $subtotal;

        }
            
        

        
        $tabla.= '</tbody>
        <tfoot>
            <tr>
                <td colspan="5" align="right">Subtotales</td>
                <td align="right">'.number_format($piezas, 0).'</td>
                <td align="right">'.number_format($importeTotal, 2).'</td>
                <td align="right">'.number_format($ivaTotal, 2).'</td>
                <td align="right">&nbsp;</td>
            </tr>
            
            <tr>
                <td colspan="7" align="right">Total de documento</td>
                <td align="right">'.number_format(($importeTotal + $ivaTotal), 2).'</td>
                <td align="right">&nbsp;</td>
            </tr>
           
        </tfoot>
        </table>';
        
     
        
        return $tabla;
    }
    
    function detalleExcedente($movimientoID)/*HOJA DE PEDIDO hoja 1*/
    {
        $query = $this->getDetalle($movimientoID);
        
        $tabla = '
        <style>
        table
        {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        }
        th
        {
            font-weight: normal;
            border-bottom: 2px solid #000000;
        }
        td
        {
            border-bottom: 1px solid #000000;
        }
        </style>';
        
        $tabla.= '<table cellpadding="4">
         
        <thead>
        

              
          
            <tr>
                <th width="30px">#</th>
                <th width="50px">Clave</th>
                <th width="130px">Nom. Generico</th>
                <th width="160px">Descripci&oacute;n</th>
                <th width="130px">Presentacion</th>
                <th width="70px">Lote</th>
                <th width="70px">Caducidad</th>
                <th width="50px" align="right">Piezas</th>
            </tr>
        </thead>
        <tbody>
        ';

        $piezas = 0;
        $i = 1;

        foreach($query->result() as $row)
        {
            
            $tabla.= '<tr>
                <td width="30px">'.$i.'</td>
                <td width="50px">'.$row->cvearticulo.'</td>
                <td width="130px">'.trim($row->comercial.' '.$row->susa).'</td>
                <td width="160px">'.$row->descripcion.'</td>
                <td width="130px">'.$row->pres.'</td>
                <td width="70px">'.$row->lote.'</td>
                <td width="70px">'.formato_caducidad($row->caducidad).'</td>
                <td width="50px" align="right">'.number_format($row->piezas, 0).'</td>
            </tr>
            ';


            $piezas = $piezas + $row->piezas;
            $i++;

        }
            
        

        
        $tabla.= '</tbody>
        <tfoot>
            <tr>
                <td colspan="3">RECIBIO</td>
                <td colspan="2">RESPONSABLE DE LA FARMACIA</td>
                <td>NO. DE CAJAS: </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">NOMBRE</td>
                <td>FIRMA</td>
                <td>NOMBRE</td>
                <td>FIRMA</td>
                <td>FECHA: </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td></td>
                <td>HORA: </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        </table>
        <br /><br /><br />
        <table>
            <thead>
                <tr>
                    <th colspan="2" width="690px" style="text-align: center; ">A U T O R I Z A C I O N<br /></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="345px" style="text-align: center; "><br /><br /><br /><br />_________________________________________<br /><br /><br />GTE. GENERAL LICITACION</td>
                    <td width="345px" style="text-align: center; "><br /><br /><br /><br />_________________________________________<br /><br /><br />GTE. DE ALMACEN</td>
                </tr>
            </tbody>
        </table>';
        
     
        
        return $tabla;
    }

    function embarque($movimientoID)
    {
        $this->db->where('movimientoID', $movimientoID);
        $query = $this->db->get('movimiento_embarque');
        return $query;
    }

    function formato01($movimientoID)/*Formato de embarque HOJA 2*/
    {
        $query2 = $this->embarque($movimientoID);
        if($query2->num_rows() > 0)
        {
            $row2 = $query2->row();
            $embarco = $row2->embarco;
            $operador = $row2->operador;
            $unidad = $row2->unidad;
            $placas = $row2->placas;
            $cajas = $row2->cajas;
            $hieleras = $row2->hieleras;
            $surtio = $row2->surtio;
            $valido = $row2->valido;
            $observaciones = $row2->observaciones;
        }else{
            $embarco = null;
            $operador = null;
            $unidad = null;
            $placas = null;
            $cajas = null;
            $hieleras = null;
            $surtio = null;
            $valido = null;
            $observaciones = null;
            
        }
        

        $query = $this->getMovimientoByMovimientoID($movimientoID);
        $row = $query->row();

        $alm_formato='<table cellspacing="0" cellpadding="4" border="1" width="720">

<tr align="center">
<td>Embarco:</td>
<td colspan="2">'.$embarco.'</td>
<td colspan="6" rowspan="5">Movimiento ID:<h1>'.$row->movimientoID.'</h1>Fecha:<h1>'.$row->fecha.'</h1></td>
</tr>

<tr align="center">
<td>Operador:</td>
<td colspan="2">'.$operador.'</td>
</tr>

<tr align="center">
<td>Unidad:</td>
<td colspan="2">'.$unidad.'</td>
</tr>

<tr align="center">
<td>Placas:</td>
<td colspan="2">'.$placas.'</td>
</tr>

<tr align="center">
<td colspan="3"><br/><br /><br /></td>
</tr>

<tr >
<td colspan="9" style="font-size: large">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Numero de Cajas:'.$cajas.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Numero de Hieleras:'.$hieleras.'</td>
</tr>

<tr >
<td colspan="9" style="font-size: large">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Surtio:'.$surtio.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Validado por:'.$valido.'</td>
</tr>


<tr align="LEFT">
<td colspan="9"></td>
</tr>

<tr align="LEFT">
<td colspan="9" style="font-size: large" ><br/>Observaciones:<br/> '.$observaciones.'</td>
</tr>





<tr  align="center">
<td colspan="9">NOMBRE Y FIRMA DE QUIEN EMBARCA</td>
</tr>

<tr align="center">
<td colspan="8" rowspan="2">'.$embarco.'</td>

<td></td>
</tr>

<tr align="center">
<td></td>


</tr>



<tr >
<td colspan="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AUTORIZO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RESPONSABLE</td>
</tr>

<tr align="center">
<td colspan="4" rowspan="2"></td>
<td colspan="4" rowspan="2"></td>
<td></td>
</tr>



<tr align="center">
<td></td>


</tr>

<tr align="center">
<td colspan="9" ></td>

</tr>






<tr align="center">
<td colspan="2">Firma del Operador:</td>
<td colspan="5">Sucursal</td>
<td colspan="2">Sello Unidad Hospitalaria:</td>
</tr>
<tr align="center">
<td colspan="2">'.$operador.'<br /><br /><br /></td>
<td colspan="5"> '.$row->sucursal_referencia.'</td>
<td colspan="2" rowspan="3"></td>
</tr>
<tr align="center">
<td colspan="7">Nombre,Cargo y Firma de quien recibe <br/><br/><br/><br/><br/><br/><br/><br/><br/></td>
</tr>
<tr align="center">
<td colspan="7">OBSERVACIONES:<br /><br /><br /></td>
</tr>
</table>';

        return $alm_formato;
    }
    
    function opcionesDevolucion()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('opcion_devolucion');
        return $query->row();
    }

    function formato02($movimientoID)/*Formato de devoluciones HOJA 3*/
    {
        $query2 = $this->embarque($movimientoID);
        if($query2->num_rows() > 0)
        {
            $row2 = $query2->row();
            $embarco = $row2->embarco;
            $operador = $row2->operador;
            $unidad = $row2->unidad;
            $placas = $row2->placas;
            $cajas = $row2->cajas;
            $hieleras = $row2->hieleras;
            $surtio = $row2->surtio;
            $valido = $row2->valido;
            $observaciones = $row2->observaciones;
        }else{
            $embarco = null;
            $operador = null;
            $unidad = null;
            $placas = null;
            $cajas = null;
            $hieleras = null;
            $surtio = null;
            $valido = null;
            $observaciones = null;
            
        }
        

        $query = $this->getMovimientoByMovimientoID($movimientoID);
        $row = $query->row();
        
        $row3 = $this->opcionesDevolucion();
        
        $alm_formato1='<table cellspacing="0" cellpadding="4" border="0.8" width="720">
        
<tr align="center">
<td colspan="11" bgcolor="#C8C8C8"> FORMATO DE INCIDENCIAS</td>
</tr>
<tr align="center">
<td>Num.Suc</td>
<td colspan="7">'.$row->clvsucursalReferencia.'</td>
<td colspan="3" rowspan="4">Nombre y Firma de quien elaboro incidencias</td>
</tr>

<tr align="center">
<td>Cliente:</td>
<td colspan="7">'.$row->sucursal_referencia.'</td>
</tr>

<tr align="center">
<td>Operador:</td>
<td colspan="7">'.$operador.' </td>
</tr>

<tr align="lefth" >
<td  colspan="11" bgcolor="#F3F3F3">'.$row3->devolucion.''.$row3->devolucion1.''.$row3->devolucion2.'</td>
</tr>

<tr align="center">
<td  bgcolor="#C8C8C8">Causa</td>
<td colspan="2" bgcolor="#C8C8C8">Clave</td>
<td colspan="3" bgcolor="#C8C8C8">Descripcion</td>
<td bgcolor="#C8C8C8">Lote</td>
<td bgcolor="#C8C8C8">Caducidad</td>
<td bgcolor="#C8C8C8">Cantidad</td>
<td colspan="2" bgcolor="#C8C8C8">Observaciones</td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>
<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>

<tr align="center">
<td ></td>
<td colspan="2"></td>
<td colspan="3"></td>
<td ></td>
<td ></td>
<td ></td>
<td colspan="2"></td>
</tr>


<tr align="left">
<td colspan="11"> 

<h1 style="color:red; font-size:28px;">NOTA: POR ESTE CONDUCTO RECIBIMOS LAS INCIDENCIAS Y DEVOLUCIONES DETECTADAS EN SU FOLIO DE ENVIO, CONFORME A LA VERIFICACION DE LOS PRODUCTOS QUE RECIBIO FISICAMENTE, Y CUYA CANTIDAD SERA DESCONTADA DE DICHO  FOLIO.</h1>

 </td>
</tr>



</table>';

        return $alm_formato1;
    }
    
    function cargaPedido($referencia, $movimientoID)
    {
        
        $result = $this->getPedido($referencia);
        
        if(count($result['detalle']) > 0)
        {
            //referencia, clave, cansur, lote, caducidad
            
            $a = array();
            
            foreach($result['detalle'] as $row)
            {
                $row['referencia'] = $referencia;
                $row['movimientoID'] = $movimientoID;
                array_push($a, $row);
            }
            
            $this->db->insert_batch('pedido_transpaso', $a);
            
            $sql = "insert into movimiento_detalle (movimientoID, id, piezas, costo, lote, caducidad, ean, marca)(SELECT movimientoID, a.id, cansur, 0, lote, caducidad, 0, '' FROM pedido_transpaso p
    join articulos a on p.clave = a.cvearticulo
    where movimientoID = ? and referencia = ?);";
            
            $this->db->query($sql, array($movimientoID, $referencia));
            
        }
    }
    
    function cargaPedidoUnidades($referencia, $movimientoID)
    {
        $this->json = $this->__get_data($this->__getCatalogo('movimientoDetalle', $referencia), $referencia);
        
        $arreglo = json_decode($this->json, true);
        
        if(count($arreglo) > 0)
        {
            //referencia, clave, cansur, lote, caducidad
            
            $a = array();
            
            foreach($arreglo as $row)
            {
                $row['movimientoID'] = $movimientoID;
                array_push($a, $row);
            }
            
            $this->db->insert_batch('movimiento_detalle', $a);
            
        }
    }

    function getPedido($referencia)
    {
        //$username = 'admin';
        //$password = '1234';
         
        // Alternative JSON version
        // $url = 'http://twitter.com/statuses/update.json';
        // Set up and execute the curl process
        $curl_handle = curl_init();
       	$timeout = 2;
        curl_setopt($curl_handle, CURLOPT_URL, $this->urlExchange.'getPedido');
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
    	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array('referencia' => $referencia));
         
        // Optional, delete this line if your API is open
        //curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);
         
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
         
        $result = json_decode($buffer, true);
        
        return $result;
    }
    
    function getPedidoUnidad($referencia)
    {
        //$username = 'admin';
        //$password = '1234';
         
        // Alternative JSON version
        // $url = 'http://twitter.com/statuses/update.json';
        // Set up and execute the curl process
        $curl_handle = curl_init();
    	$timeout = 2;
        
        curl_setopt($curl_handle, CURLOPT_URL, 'http://189.203.201.184/oaxacacentral/index.php/catalogos/movimientoDetalle/referencia/'.$referencia.'/format/json');
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
    	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array('referencia' => $referencia));
         
        // Optional, delete this line if your API is open
        //curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);
         
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
         
        $result = json_decode($buffer, true);
        
        return $result;
    }

    function modificaDetalle($data, $movimientoDetalle)
    {
        $this->db->update('movimiento_detalle', $data, array('movimientoDetalle' => $movimientoDetalle));
    }
    
    function getAreasGuia($movimientoID)
    {
        $sql = "SELECT areaID, area FROM movimiento_prepedido m
join articulos a using(id)
left join inventario i using(id)
left join ubicacion u using(ubicacion)
where m.movimientoID = ? and pasilloTipo <> 2 and i.clvsucursal = ?
group by areaID;";
        $query = $this->db->query($sql, array((int)$movimientoID, $this->session->userdata('clvsucursal')));

        return $query;
    }
    
    function getProductosFolprv($folprv)
    {
        $arreglo = $this->util->getDataOficina('ordenDetalle', array('folprv' => $folprv));
        foreach($arreglo as $a)
        {
            if(PATENTE == 1)
            {
                $this->__agregaArticuloPatente($a->codigo);
            }else{
                $this->__agregaArticulo($a->clagob);
            }
        }
        
    }
    
    function __agregaArticulo($clave)
    {
        $this->load->model('Catalogosweb_model');
        $clave = trim(str_replace('/', '|', $clave));
        $articulo = $this->util->getDataOficina('articuloClave', array('clave' => $clave));
        
        
        if(!isset($articulo->error))
        {
            foreach($articulo as $a)
            {
                $this->Catalogosweb_model->insertaArticulo($a);
            }
            
        }
    }
    
    function __agregaArticuloPatente($clave)
    {
        echo $clave;
        $this->load->model('Catalogosweb_model');
        $articulo = $this->util->getDataOficina('patenteSinOrigen', array('ean' => $clave));
        
        
        if(!isset($articulo->error))
        {
            foreach($articulo as $a)
            {
                $this->Catalogosweb_model->insertaArticulo3($a);
            }
            
        }
    }

    function cierrePrepedido($movimientoID)
    {
        $data = array(
            'statusPrepedido' => 1
        );
        
        $this->db->update('movimiento', $data, array('movimientoID' => $movimientoID));
    }
    
    function getNuevoFolioFromReferencia($referencia)
    {
        $sql = "SELECT nuevo_folio FROM movimiento m where referencia = ? and nuevo_folio > 0 limit 1;";
        $query = $this->db->query($sql, (string)$referencia);
        
        return $query;
    }
    
    function asignaFactura($movimientoID, $referencia)
    {
        
        $query = $this->getNuevoFolioFromReferencia($referencia);
        
        
        if($query->num_rows() == 0)
        {
            $folio = $this->util->getDataOficina('folio', array('foliador' => $this->session->userdata('cxp')));
        }else{
            $row = $query->row();
            
            $folio = new StdClass();
            $folio->folio = $row->nuevo_folio;
        }
        
        
        
        $data = array('referencia' => $referencia, 'asignaFactura' => 1, 'nuevo_folio' => $folio->folio);
        $this->db->set('asignaFacturaFecha', 'now()', false);
        $this->db->update('movimiento', $data, array('movimientoID' => $movimientoID));
    }
    
    function getClientesBySucursal($clvsucursal)
    {
        $sql = "SELECT * FROM receptores_sucursal r
JOIN receptores e using(rfc)
where clvsucursal = ?;";

        $query = $this->db->query($sql, $clvsucursal);
        
        return $query;
    }
    
    function getClientesByMovimientoID($movimientoID)
    {
        $sql = "SELECT * FROM receptores_sucursal r
JOIN receptores e using(rfc)
where clvsucursal = (select clvsucursalReferencia from movimiento where movimientoID = ?);";

        $query = $this->db->query($sql, (int)$movimientoID);
        
        return $query;
    }
    
    function getClientesByMovimientoIDCombo($movimientoID)
    {
        $query = $this->getClientesByMovimientoID($movimientoID);
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $a[$row->rfc] = $row->rfc . ' - ' . $row->razon;
        }
        
        return $a;
    }
    
    function getContratoCombo($rfc)
    {
        $this->db->where('rfc', $rfc);
        $query = $this->db->get('contrato');
        
        if($query->num_rows() > 0)
        {
            $a = '';
            foreach($query->result() as $row)
            {
                $a .= '
                <option value="'.$row->contratoID.'">'.$row->denominado.' - '.$row->numero.'</option>';
            }
            
            return $a;
            
        }else{
            return null;
        }
    }
    
    function getFacturaProductosByContratoID($contratoID, $movimientoID)
    {
        $sql = "SELECT * FROM movimiento_detalle m
join articulos a using(id)
left join contrato_precio c on m.id = c.id and contratoID = ?
left join ubicacion using(ubicacion)
where movimientoID = ?;";
        
        $query = $this->db->query($sql, array($contratoID, $movimientoID));
        
        return $query;
    }
    
    function getFacturaReferencia($contratoID = 0, $movimientoID)
    {
        if($contratoID <> 0)
        {
            $query = $this->Catalogosweb_model->getContratoByContratoID($contratoID);
            $row = $query->row();

            $licitacion = $row->numero;
            $string = $row->referencia_factura;
            $nombre_corto = $row->denominado;
        }else{
            $licitacion = null;
            $string = null;
            $nombre_corto = null;
        }
        
        
        $query2 = $this->getMovimientoByMovimientoID($movimientoID);
        $row2 = $query2->row();
        
        $mes_actual = $this->getMesActual();
        $anio_actual = date('Y');
        
        $nombre_sucursal = $row2->sucursal_referencia;
        $direccion_sucursal = $row2->domicilio;
        $referencia_pedido = $row2->observaciones;
        $anio_pedido = $row2->anio;
        $mes_pedido = $this->getMesNombre($row2->mes);
        $sucursal_personalizado_nombre = $row2->nombreSucursalPersonalizado;
        $sucursal_personalizado_direccion = $row2->domicilioSucursalPersonalizado;
        
        $este = array('$licitacion', '$mes_actual', '$anio_actual', '$nombre_corto', '$nombre_sucursal', '$direccion_sucursal', '$referencia_pedido', '$anio_pedido', '$mes_pedido', '$sucursal_personalizado_nombre', '$sucursal_personalizado_direccion');
        $por = array($licitacion, $mes_actual, $anio_actual, $nombre_corto, $nombre_sucursal, $direccion_sucursal, $referencia_pedido, $anio_pedido, $mes_pedido, $sucursal_personalizado_nombre, $sucursal_personalizado_direccion);
        
        $string = str_replace($este, $por, $string);
        
        return $string;
    }
    
    function getMesActual()
    {
        $mes = date('m');
        return $this->getMesNombre($mes);
    }
    
    function getMesNombre($mes)
    {
        $mes = (int)$mes;
        
        $a = array(
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        );
        
        return $a[$mes];
    }
    
    function transferAplica($movimientoID, $inventarioID, $valor)
    {
        $query = $this->getInventarioByID($inventarioID);
        $inv = $query->row();
        
        $data = array('movimientoID' => $movimientoID, 'id' => $inv->id, 'piezas' => $valor, 'costo' => $inv->costo, 'lote' => $inv->lote, 'caducidad' => $inv->caducidad, 'ean' => $inv->ean, 'marca' => $inv->marca, 'ubicacion' => $inv->ubicacion, 'comercial' => $inv->comercial);
        $this->db->insert('movimiento_detalle', $data);
    }
    
    function getAreaLimit1()
    {
        $sql = "SELECT * FROM area a  where clvsucursal = ? limit 1;";
        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal')));
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->areaID;
        }else{
            return 0;
        }
    }
    
    function getAreaIDDropdown()
    {
        $sql = "SELECT * FROM area a where clvsucursal = ?;";
        $query = $this->db->query($sql, $this->session->userdata('clvsucursal'));
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            $a[$row->areaID] = $row->area;
        }
        
        return $a;
    }

    function getTitulosByTipoSubtipo($tipoMovimiento = 0, $subtipoMovimiento = 0)
    {
        $sql = "SELECT concat(tipoMovimientoDescripcion, ' - ', subtipoMovimientoDescripcion) as titulo FROM tipo_movimiento t
join subtipo_movimiento s using(tipoMovimiento) where tipoMovimiento = ? and subtipoMovimiento = ?;";
        
        $query = $this->db->query($sql, array($tipoMovimiento, $subtipoMovimiento));

        if($query->num_rows() == 0)
        {
            return null;
        }else
        {
            $row = $query->row();
            return $row->titulo;
        }

    }

    function getInventarioByIDandLoteandSucursal($id, $lote, $clvsucursal)
    {
        $this->db->where('id', $id);
        $this->db->where('lote', $lote);
        $this->db->where('clvsucursal', $clvsucursal);
        $query = $this->db->get('inventario');

        return $query;

    }

    function cambioDetalle($movimientoDetalle, $piezasNueva)
    {
        $this->db->trans_start();
        //echo $movimientoDetalle . '<br />';
        //echo $piezasNueva . '<br />';

        $query = $this->getDetalleByMovimientoDetalle($movimientoDetalle);


        $row = $query->row();

        //print_r($row);


        $inv = $this->getInventarioByIDandLoteandSucursal($row->id, $row->lote, $row->clvsucursal);

        if($inv->num_rows() > 0)
        {

            $i = $inv->row();
            
            switch ($row->tipoMovimiento) {
                case 1:
                    //echo "i es igual a 1" . '<br />';

                    $this->db->update('movimiento_detalle', array('piezas' => $piezasNueva), array('movimientoDetalle' => $movimientoDetalle));

                    $diferencia = $piezasNueva - $row->piezas;
                    //echo $diferencia . '<br />';

                    if($diferencia < 0)
                    {

                        $data = array(
                            'cantidad'          => ($i->cantidad - ($diferencia * -1)),
                            'tipoMovimiento'    => 3,
                            'subtipoMovimiento' => 11,
                            'usuario'           => $this->session->userdata('usuario'),
                            'movimientoID'      => $row->movimientoID
                        );

                        $this->db->set('ultimo_movimiento', 'now()', false);
                        $this->db->update('inventario', $data, array('inventarioID' => $i->inventarioID));

                        //print_r($data);

                    }elseif($diferencia > 0)
                    {

                        $data = array(
                            'cantidad'          => ($i->cantidad + $diferencia),
                            'tipoMovimiento'    => 3,
                            'subtipoMovimiento' => 11,
                            'usuario'           => $this->session->userdata('usuario'),
                            'movimientoID'      => $row->movimientoID
                        );

                        $this->db->set('ultimo_movimiento', 'now()', false);
                        $this->db->update('inventario', $data, array('inventarioID' => $i->inventarioID));

                    }else
                    {

                    }

                    break;
                case 2:


                    $this->db->update('movimiento_detalle', array('piezas' => $piezasNueva), array('movimientoDetalle' => $movimientoDetalle));

                    $diferencia = $piezasNueva - $row->piezas;
                    //echo $diferencia . '<br />';

                    if($diferencia < 0)
                    {

                        $data = array(
                            'cantidad'          => ($i->cantidad + ($diferencia * -1)),
                            'tipoMovimiento'    => 3,
                            'subtipoMovimiento' => 11,
                            'usuario'           => $this->session->userdata('usuario'),
                            'movimientoID'      => $row->movimientoID
                        );

                        $this->db->set('ultimo_movimiento', 'now()', false);
                        $this->db->update('inventario', $data, array('inventarioID' => $i->inventarioID));

                        //print_r($data);

                    }

                    break;
                case 3:
                    //echo "i es igual a 3";
                    break;
            }
        }


        $this->db->trans_complete();

        return $row->movimientoID;

    }

    function getFolioPaquete()
    {
        $sql = "SELECT max(replace(referencia, 'P-', '')* 1) as maximo FROM movimiento m where subtipoMovimiento = 22;";
        $query = $this->db->query($sql);
        $row = $query->row();
        return 'P-' . str_pad(($row->maximo + 1), 4, '0', STR_PAD_LEFT);
    }

    function getJSONByMovimientoID($movimientoID)
    {
        $sql = "SELECT movimientoID, movimientoDetalle, referencia, clvsucursal, clvsucursalReferencia, piezas, cvearticulo
FROM movimiento m
join movimiento_detalle d using(movimientoID)
join articulos a using(id)
where subtipoMovimiento = 2 and statusMovimiento = 1 and clvsucursal = ? and movimientoID = ?;";

        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal'), $movimientoID));

        return json_encode($query->result_array());
    }

    function getColectivosCuenta()
    {
        $sql = "SELECT count(*) as cuenta FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
where usuario = ?;";
        
        $query = $this->db->query($sql, array($this->session->userdata('usuario')));
        $row = $query->row();

        return $row->cuenta;
    }

    function getColectivos($limit, $offset = 0)
    {
        $sql = "SELECT c.*, descsucursal, nombreusuario, programa, etapa, nivelatencion as nivelatencionReferencia, referencia
FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
left join movimiento m using(movimientoID)
where c.usuario = ?
limit ? offset ?;";
        
        $query = $this->db->query($sql, array($this->session->userdata('usuario'), (int)$limit, (int)$offset));

        return $query;
    }

    function getColectivoByColectivoID($colectivoID)
    {
        $sql = "SELECT c.*, descsucursal, nombreusuario, programa, etapa, nivelatencion as nivelatencionReferencia, s.numjurisd, jurisdiccion, referencia
FROM colectivo c
join sucursales s using(clvsucursal)
join jurisdiccion j using(numjurisd)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
left join movimiento m using(movimientoID)
where colectivoID = ?;";
        
        $query = $this->db->query($sql, array($colectivoID));

        return $query;
    }

    function insertColectivo($data)
    {
        $this->db->insert('colectivo', $data);
        return $this->db->insert_id();
    }

    function updateColectivo($data, $colectivoID)
    {
        $this->db->update('colectivo', $data, array('colectivoID' => $colectivoID));
    }

    function getDetalleColectivo($colectivoID)
    {
        $sql = "SELECT d.*, d.id, cvearticulo, susa, descripcion, pres, statusColectivo, case when o.idprograma is null then 0 else 1 end as cubierto
FROM colectivo_detalle d
join colectivo c using(colectivoID)
join articulos a using(id)
join sucursales s using(clvsucursal)
left join articulos_cobertura o on a.id = o.id and s.nivelatencion = o.nivelatencion and c.idprograma = o.idprograma
where colectivoID = ?;";
        
        $query = $this->db->query($sql, array($colectivoID));

        return $query;
    }

    function insertDetalleColectivo($colectivoID, $cveArticulo, $piezas)
    {
        $query = $this->getArticuloByClave($cveArticulo);
            
        if($query->num_rows() > 0)
        {

            $row = $query->row();    

            $this->db->where('colectivoID', $colectivoID);
            $this->db->where('id', $row->id);
            $query = $this->db->get('colectivo_detalle');

            if($query->num_rows() == 0)
            {
                $data = array(
                    'colectivoID'   => $colectivoID,
                    'id'            => $row->id,
                    'piezas'        => $piezas
                    );
                
                $this->db->insert('colectivo_detalle', $data);
                return $this->db->insert_id();
            }else
            {
                return 0;
            }
            
            
        }
    }

    function deleteDetalle($colectivoDetalle)
    {
        $this->db->delete('colectivo_detalle', array('colectivoDetalle' => $colectivoDetalle));
    }

    function cierraColectivo($colectivoID)
    {
        $data = array(
            'statusColectivo'   => 1
        );
        $this->db->set('fechaCierre', 'now()', false);
        $this->db->update('colectivo', $data, array('colectivoID' => $colectivoID));
    }

    function headerColectivo($colectivoID)
    {
        $query = $this->getColectivoByColectivoID($colectivoID);
        $row = $query->row();

        
        $logo = array(
                                  'src' => base_url().'assets/img/logo.png',
                                  'width' => '120'
                        );
                        
        $tabla = '<table cellpadding="1">
            <tr>
                <td rowspan="5" width="100px">'.img($logo).'</td>
                <td rowspan="5" width="450px" align="center"><font size="8">'.COMPANIA.'<br />SUCURSAL: '.$row->clvsucursal.' - '.$row->descsucursal.'<br />JURISDICCION: '.$row->numjurisd.' - '.$row->jurisdiccion.'<br />COBERTURA: '.$row->programa.'<br />Observaciones: '.$row->observaciones .'</font><br />Referencia: '.barras($row->folio).'</td>
                <td width="75px">ID Movimiento: </td>
                <td width="95px" align="right">'.$row->colectivoID.'</td>
            </tr>
            <tr>
                <td width="75px">FECHA: </td>
                <td width="95px" align="right">'.$row->fecha.'</td>
            </tr>
            <tr>
                <td width="75px"># Sucursal: </td>
                <td width="95px" align="right">'.$row->clvsucursal.'</td>
            </tr>
            <tr>
                <td width="75px">FOLIO: </td>
                <td width="95px" align="right">'.$row->folio.'</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">ID: '.barras($row->movimientoID).'</td>
            </tr>
        </table>';
        
        return $tabla;
    }

    function detalleColectivo($colectivoID)/*HOJA DE PEDIDO hoja 1*/
    {
        $query = $this->getDetalleColectivo($colectivoID);

        
        $tabla = '
        <style>
        table
        {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        }
        th
        {
            font-weight: normal;
            border-bottom: 2px solid #000000;
        }
        td
        {
            border-bottom: 1px solid #000000;
        }
        </style>';
        
        $tabla.= '<table cellpadding="4">
         
        <thead>
        

              
          
            <tr>
                <th width="50px">Clave</th>
                <th width="210px">Nom. Generico</th>
                <th width="240px">Descripci&oacute;n</th>
                <th width="160px">Presentacion</th>
                <th width="50px" align="right">Requerido</th>
            </tr>
        </thead>
        <tbody>
        ';

        $piezas = 0;

        foreach($query->result() as $row)
        {

            
            
            $tabla.= '<tr>
                <td width="50px">'.$row->cvearticulo.'</td>
                <td width="210px">'.trim($row->susa).'</td>
                <td width="240px">'.$row->descripcion.'</td>
                <td width="160px">'.$row->pres.'</td>
                <td width="50px" align="right">'.number_format($row->piezas, 0).'</td>
            </tr>
            ';


            $piezas = $piezas + $row->piezas;

        }
            
        

        
        $tabla.= '</tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">Subtotales</td>
                <td align="right">'.number_format($piezas, 0).'</td>
            </tr>
            
           
        </tfoot>
        </table>';
        
     
        
        return $tabla;
    }

    function getColectivosAprobarCuenta()
    {
        $sql = "SELECT count(*) as cuenta FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
where statusColectivo = 1;";
        
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cuenta;
    }

    function getColectivosAprobar($limit, $offset = 0)
    {
        $sql = "SELECT c.*, descsucursal, nombreusuario, programa, etapa, nivelatencion as nivelatencionReferencia, referencia
FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
left join movimiento m using(movimientoID)
where statusColectivo = 1
limit ? offset ?;";
        
        $query = $this->db->query($sql, array((int)$limit, (int)$offset));

        return $query;
    }

    function aprobarPaquete($colectivoID)
    {
        $this->db->trans_start();
        $query = $this->getColectivoByColectivoID($colectivoID);
        $row = $query->row();

        $referencia = $this->getFolioPaquete();

        $data = array(
            'tipoMovimiento'        => 2,
            'subtipoMovimiento'     => 22,
            'orden'                 => 0,
            'referencia'            => $referencia,
            'fecha'                 => $row->fecha,
            'statusMovimiento'      => 0,
            'proveedorID'           => 0,
            'clvsucursal'           => ALMACEN,
            'clvsucursalReferencia' => $row->clvsucursal,
            'usuario'               => $this->session->userdata('usuario'),
            'observaciones'         => 'COLECTIVO ' . $row->folio,
            'remision'              => 0,
            'cobertura'             => $row->idprograma,
            'colectivo'             => $row->folio
        );
        
        $this->db->set('fechaAlta', 'now()', false);
        $this->db->insert('movimiento', $data);
        $movimientoID = $this->db->insert_id();

        $detalle = $this->getDetalleColectivo($colectivoID);

        foreach ($detalle->result() as $det) {
            $dataDetalle = array(
                'movimientoID'  => $movimientoID,
                'id'            => $det->id,
                'piezas'        => $det->piezas
            );

            $this->db->insert('movimiento_prepedido', $dataDetalle);
        }

        $dataColectivo = array('statusColectivo' => 2, 'movimientoID' => $movimientoID);
        $this->db->set('fechaGuia', 'now()', false);
        $this->db->update('colectivo', $dataColectivo, array('colectivoID' => $colectivoID));

        $dataPrepedido = array(
            'statusPrepedido' => 1,
        );
        
        $this->db->update('movimiento', $dataPrepedido, array('movimientoID' => $movimientoID));
        $this->db->trans_complete();
    }

    function getColectivosSurtirCuenta()
    {
        $sql = "SELECT count(*) as cuenta FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
where statusColectivo = 2;";
        
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->cuenta;
    }

    function getColectivosSurtir($limit, $offset = 0)
    {
        $sql = "SELECT c.*, descsucursal, nombreusuario, programa, etapa, nivelatencion as nivelatencionReferencia, referencia
FROM colectivo c
join sucursales s using(clvsucursal)
join usuarios u using(usuario)
join programa p using(idprograma)
join colectivo_status t using(statusColectivo)
left join movimiento m using(movimientoID)
where statusColectivo = 2
limit ? offset ?;";
        
        $query = $this->db->query($sql, array((int)$limit, (int)$offset));

        return $query;
    }

    function cancelaMovimiento($movimientoID)
    {
        $data = array('statusMovimiento' => 2);
        $this->db->set('fechaCancelacion', 'now()', false);
        $this->db->update('movimiento', $data, array('movimientoID' => $movimientoID));
    }

    function getDetalleAbrir($movimientoID)
    {
        $sql = "SELECT m.id, inventarioID, piezas, cantidad
FROM movimiento_detalle m
left join inventario i using(ubicacion, id, lote)
where m.movimientoID = ?;";
        $query = $this->db->query($sql, array($movimientoID));

        return $query;
    }

    function abrirMovimiento($movimientoID)
    {
        $this->db->trans_start();

        $query = $this->getMovimientoByMovimientoID($movimientoID);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $det = $this->getDetalleAbrir($movimientoID);

            foreach ($det->result() as $d)
            {

                if($row->tipoMovimiento == 1)
                {

                    $inv = $d->cantidad - $d->piezas;
                    $tipoMovimiento = 2;
                    $subtipoMovimiento = 25;

                }elseif($row->tipoMovimiento == 2)
                {

                    $inv = $d->cantidad + $d->piezas;
                    $tipoMovimiento = 1;
                    $subtipoMovimiento = 24;

                }else
                {

                }

                $data = array(
                    'cantidad'          => $inv,
                    'tipoMovimiento'    => $tipoMovimiento,
                    'subtipoMovimiento' => $subtipoMovimiento,
                    'receta'            => 0,
                    'usuario'           => $this->session->userdata('usuario'),
                    'movimientoID'      => $movimientoID
                );

                $this->db->set('ultimo_movimiento', 'now()', false);

                $this->db->update('inventario', $data, array('inventarioID' => $d->inventarioID));
            }


            $this->db->update('movimiento', array('statusMovimiento' => 0), array('movimientoID' => $movimientoID));

        }

        $this->db->trans_complete();
    }

}