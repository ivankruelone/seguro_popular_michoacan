<?php
class Captura_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */
    function __construct()
    {
        parent::__construct();
    }
    
    function hola()
    {
        return null;
    }
    
    function getFolioRecetaByConsecutivo($consecutivo)
    {
        $this->db->where('consecutivo', $consecutivo);
        $query = $this->db->get('receta');
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->folioreceta;
        }else{
            return null;
        }
    }

    function validaRecetaRemisionada($folioreceta)
    {
        $sql = "SELECT consecutivoDetalle FROM receta_detalle d
join receta r using(consecutivo)
where folioreceta = ? and remision > 0;";
        
        $query = $this->db->query($sql, array((string)$folioreceta));

        return $query->num_rows();
    }
    
    function getCveServicioCombo()
    {
        $sql = "SELECT * FROM fservicios f
join sucursales_servicios s using(cveservicios)
where clvsucursal = ?;";

        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal')));

        if($query->num_rows() == 0)
        {
            $query = $this->db->get('fservicios');
        }
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            
            $a[$row->cveservicios] = ($row->desservicios);
            
        }
        
        return $a;
    }
    
    function getProgramaCombo()
    {
        $this->db->where('activo', 1);
        $query = $this->db->get('programa');
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            
            $a[$row->idprograma] = ($row->programa);
            
        }
        
        return $a;
    }
    
    function getRequerimientoCombo()
    {
        $query = $this->db->get('temporal_requerimiento');
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            
            $a[$row->tiporequerimiento] = ($row->requerimiento);
            
        }
        
        return $a;
    }
    function getSexoCombo()
    {
        $this->db->where('genero <>', 0);
        $query = $this->db->get('genero');
        
        $a = array();
        
        foreach($query->result() as $row)
        {
            
            $a[$row->genero] = ($row->desgenero);
            
        }
        
        return $a;
    }



    function getlote($lote, $cvearticulo)
    {
        $sql = "select lote, fechacaducidad from lotes where lote = ? and cvearticulo = ? and status = 't'";
                
        $query = $this->db->query($sql, array(trim(strtoupper($lote)),$cvearticulo) );
        echo $this->db->last_query();
        if($query->num_rows() == 0)
        {
            return 0;
        }else{
            $row = $query->row();
            return $query->num_rows().'|'.$row->lote.'|'.$row->fechacaducidad;
        }        
        return $query->num_rows();
    }

    function getRecetaExist($folioReceta)
    {
        $sql = "select folioreceta, cvecentrosalud from receta where folioreceta = ? and status = 't'";
                
        $query = $this->db->query($sql, trim($folioReceta));
        
        return $query->num_rows();
    }
    
    function validaFecha($fecha)
    {
        $sql = "SELECT EXTRACT(DAY FROM TIMESTAMP ?)";
                
        $query = $this->db->query($sql, trim($fecha));
        
        return $query->num_rows();
    }
    
    function getRecetaExist2($folioReceta)
    {
        $sql = "select trim(folioreceta) as folioreceta, trim(clvsucursal) as cvecentrosalud, trim(descsucursal) as descsucursal, fechaexp from receta r join sucursales s using(clvsucursal) where folioreceta = ?";
                
        $query = $this->db->query($sql, trim($folioReceta));
        
        if($query->num_rows() == 0)
        {
            return 0;
        }else{
            $row = $query->row();
            return $query->num_rows().'|'.$row->folioreceta.'|'.$row->cvecentrosalud.'|'.($row->descsucursal).'|'.$row->fechaexp;
        }
        
        return $query->num_rows();
    }

    function getReceta($folioReceta)
    {
        $sql = "select folioreceta, clvsucursal from receta where folioreceta = ?";
                
        $query = $this->db->query($sql, trim($folioReceta));
        
        return $query;
    }

    function getRecetaCompleta($folioReceta)
    {
        $sql = "select * from receta where folioreceta = ? limit 1";
                
        $query = $this->db->query($sql, trim($folioReceta));
        
        return $query->row();
    }

    function getRecetaCompletaByConsecutivo($consecutivo)
    {
        $sql = "select * from receta where consecutivo = ? limit 1";
                
        $query = $this->db->query($sql, trim($consecutivo));
        
        return $query->row();
    }

    function getRecetaProdcutos($folioReceta)
    {
        //$sql = "select r.cvearticulo, cantidadrequerida, cantidadsurtida, idlote, fechacaducidad, consecutivo from receta r join lotes l on r.cvearticulo = l.cvearticulo and r.idlote = l.lote where folioreceta = ? and r.status = 't'";
        $sql = "SELECT d.*, cvearticulo
FROM receta_detalle d
join articulos a using(id)
join receta r using(consecutivo)
where folioreceta = ?;";         
        $query = $this->db->query($sql, trim($folioReceta));
        return $query;
    }

    function getRecetaProdcutosByConsecutivo($consecutivo)
    {
        //$sql = "select r.cvearticulo, cantidadrequerida, cantidadsurtida, idlote, fechacaducidad, consecutivo from receta r join lotes l on r.cvearticulo = l.cvearticulo and r.idlote = l.lote where folioreceta = ? and r.status = 't'";
        $sql = "SELECT d.*, cvearticulo
FROM receta_detalle d
join articulos a using(id)
join receta r using(consecutivo)
where r.consecutivo = ?;";         
        $query = $this->db->query($sql, trim($consecutivo));
        return $query;
    }

    function getTipoReceta()
    {
        $a = array(0 => 'Normal', 1 => 'Electronica');
        return $a;
    }
    
    function getPadronByCvePacienteJson($term)
    {
        $this->db->select('trim(cvepaciente) as cvepaciente, trim(nombre) as nombre, trim(apaterno) as apaterno, trim(amaterno) as amaterno');
        $this->db->where('cvepaciente', $term);
        $this->db->group_by('cvepaciente, nombre, apaterno, amaterno');
        $query = $this->db->get('receta');
        
        $a = array();
        
        if($query->num_rows() > 1)
        {
            $retorno = '[';
            
            foreach($query->result() as $row){
                //$b = array('paciente' => $row->cvepaciente, 'nombre' => $row->nombre, 'paterno' => $row->apaterno, 'materno' => $row->amaterno, 'value' => $row->nombre.' '.$row->apaterno.' '.$row->amaterno);
                
                $value = $row->nombre.' '.$row->apaterno.' '.$row->amaterno;
                
                $retorno .= '{"paciente":"'.utf8_encode($row->cvepaciente).'","nombre":"'.utf8_encode($row->nombre).'","paterno":"'.utf8_encode($row->apaterno).'","materno":"'.utf8_encode($row->amaterno).'","value":"'.utf8_encode($row->cvepaciente).'","desc":"'.utf8_encode($value).'"},';
            }
            
            $retorno = substr($retorno, 0, -1);
            $retorno .= ']';
            
            return $retorno;
            
        }elseif($query->num_rows() == 1){
            
                $row = $query->row();
                $retorno = '[{"paciente":"'.utf8_encode($row->cvepaciente).'","nombre":"'.utf8_encode($row->nombre).'","paterno":"'.utf8_encode($row->apaterno).'","materno":"'.utf8_encode($row->amaterno).'","value":"'.utf8_encode($row->cvepaciente).'","desc":"'.utf8_encode($value).'"}]';
                return $retorno;
            
        }else{
                $retorno = '[{"paciente":"","nombre":"","paterno":"","materno":"","value":"NO ENCONTRADO","desc":""}]';
                return $retorno;
        }
    }

    function getLoteQuery($cveArticulo)
    {
        $sql = "SELECT inventarioID, lote, caducidad, cantidad, case when caducidad = '0000-00-00' then '9999-12-31' else caducidad end as valida
        FROM inventario i
        join articulos a using(id)
        where cvearticulo = ? and i.clvsucursal = ? and cantidad > 0
        having valida >= date(now())
        order by case when caducidad = '0000-00-00' then '9999-12-31' else caducidad end, cantidad
        ;";
        $query = $this->db->query($sql, array((string)$cveArticulo, $this->session->userdata('clvsucursal')));

        if($query->num_rows() > 0)
        {
            return $query; 
        }
        else
        {
            $sql2 = "SELECT ifnull(inventarioID, 0) as inventarioID, lote, caducidad, cantidad, case when caducidad = '0000-00-00' then '9999-12-31' else caducidad end as valida
            FROM inventario i
            join articulos a using(id)
            where cvearticulo = ? and i.clvsucursal = ? and lote = 'SL'
            having valida >= date(now())
            order by case when caducidad = '0000-00-00' then '9999-12-31' else caducidad end, cantidad
            ;";
            $query2 = $this->db->query($sql2, array((string)$cveArticulo, $this->session->userdata('clvsucursal')));

            return $query2;
        }
        
             
    }

    function getLoteDrop($cveArticulo)
    {
        $query = $this->getLoteQuery($cveArticulo);

        if($query->num_rows() == 0)
        {

            $a['SL'] = 'SIN LOTE Y CADUCIDAD';

        }else
        {
            foreach($query->result() as $row)
            {
                $a[trim($row->lote)] = $row->lote.' - '.$row->caducidad.' ('.$row->cantidad.')';
            }
            
        }

        return $a;
    }
    
    function getLotesCombo($cveArticulo)
    {
        
        $query = $this->getLoteQuery($cveArticulo);
        
        //$a = '<option value="S/L|9999-12-31">SIN LOTE Y CADUCIDAD</option>';
        $a = null;
        
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $a .= '<option value="'.trim($row->inventarioID).'">'.$row->lote.' - '.$row->caducidad.' ('.$row->cantidad.')</option>';
            }
        }else{
            $a = '<option value="0">SIN LOTE Y CADUCIDAD</option>';
        }
        
        
        
        
        return $a;
    }
    
    function getArticuloByCveArticulo($term, $idprograma)
    {
        $term = strtoupper($term);
        $sql = "select trim(descripcion) as descripcion, trim(cvearticulo) as cvearticulo, trim(susa) as susa, trim(pres) as pres, case when tipoPresentacion = '1' then 'PAQUETE' else 'PIEZA' end as ampuleo 
        from articulos a
        join articulos_cobertura c on a.id = c.id and c.idprograma = ? and c.nivelatencion = ?
        where (cvearticulo like '%$term%' or susa like '%$term%' or descripcion like '%$term%') and activo = 1 limit 20;";
        
        $query = $this->db->query($sql, array($idprograma, $this->session->userdata('nivelAtencion')));

        $a = array();
        
        if($query->num_rows() > 1)
        {
            $retorno = '[';
            
            foreach($query->result() as $row){
                
                
                $retorno .= '{"descripcion":"'.utf8_encode($row->descripcion).'","susa":"'.utf8_encode($row->susa).'","pres":"'.utf8_encode($row->pres).'","ampuleo":"'.($row->ampuleo).'","cveArticulo":"'.utf8_encode($row->cvearticulo).'","value":"'.utf8_encode($row->cvearticulo.'|'.$row->descripcion.'|'.$row->susa.'|'.$row->pres).'"},';
            }
            
            $retorno = substr($retorno, 0, -1);
            $retorno .= ']';
            
            return $retorno;
            
        }elseif($query->num_rows() == 1){
            
                $row = $query->row();
                $retorno = '[{"descripcion":"'.utf8_encode($row->descripcion).'","susa":"'.utf8_encode($row->susa).'","pres":"'.utf8_encode($row->pres).'","ampuleo":"'.($row->ampuleo).'","cveArticulo":"'.utf8_encode($row->cvearticulo).'","value":"'.utf8_encode($row->cvearticulo.'|'.$row->descripcion.'|'.$row->susa.'|'.$row->pres).'"}]';
                return $retorno;
            
        }else{
                $retorno = '[{"descripcion":"","susa":"","cveArticulo":"","value":"NO ENCONTRADO O FUERA DE COBERTURA"}]';
                return $retorno;
        }
    }

    function getPacienteFromCvePaciente($expediente)
    {
        $this->db->where('cvepaciente', trim($expediente));
        $this->db->select('trim(cvepaciente) as cvepaciente, trim(nombre) as nombre, trim(apaterno) as apaterno, trim(amaterno) as amaterno, genero, edad, idprograma');
        $this->db->limit(1);
        $query = $this->db->get('paciente');
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            
            $retorno = ($row->nombre.'|'.$row->apaterno.'|'.$row->amaterno.'|'.$row->genero.'|'.$row->edad.'|'.$row->idprograma);
            return $retorno;
        }else{
            return null;
        }
    }
    
    function getMedicoFromCveMedico($cveMedico)
    {
        $this->db->where('cvemedico', trim($cveMedico));
        $this->db->select('trim(nombremedico) as nombremedico');
        $this->db->limit(1);
        $query = $this->db->get('medico');
        
        if($query->num_rows() == 0)
        {
            return null;
        }else{
            $row = $query->row();
            return ($row->nombremedico);
        }
        
    }
    
    function insertProducto($cveArticulo, $req, $sur, $precio, $lote,  $fechacad)
    {
        $sql = "SELECT cvearticulo, precioven, ifnull(lote, 'SL') as lote, ifnull(caducidad, '9999-12-31') as caducidad, a.id, ifnull(inventarioID, 0) as inventarioID
FROM articulos a
left join inventario i on a.id = i.id and clvsucursal = ? and inventarioID = ?
where cvearticulo = ?;";

        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal'), $lote, (string)$cveArticulo));
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $data = array('cvearticulo' => $row->cvearticulo, 'req' => $req, 'sur' => $sur, 'usuario' => $this->session->userdata('aleatorio'), 'precio' => $row->precioven, 'idlote'=>$row->lote, 'fechacaducidad'=>$row->caducidad, 'id' => $row->id, 'inventarioID' => $row->inventarioID);
            $this->db->insert('productos_temporal', $data);
        }
        
    }
    
    function deleteProducto($serie)
    {
        $this->db->trans_start();
        $this->db->where('serie', $serie);
        $query = $this->db->get('productos_temporal');
        
        $row = $query->row();
        
        if($row->consecutivo_temporal > 0)
        {
            $query2 = $this->getInventarioByIDAndLote($row->id, $row->idlote);
            
            if($query2->num_rows()  > 0)
            {
                $row2 = $query2->row();
                
                    $cantidad  = ((int)$row2->cantidad + (int)$row->sur);
                    $data = array(
                        'id' => $row2->id,
                        'lote' => $row2->lote,
                        'caducidad' => $row2->caducidad,
                        'cantidad' => $cantidad,
                        'tipoMovimiento' => 1,
                        'subtipoMovimiento' => 14,
                        'receta' => $row->consecutivo,
                        'usuario' => $this->session->userdata('usuario'),
                        'movimientoID' => 0,
                        );
                        
                    $this->db->set('ultimo_movimiento', 'now()', false);
                    $this->db->update('inventario', $data, array('inventarioID' => $row2->inventarioID));
                
            }
            
            $this->db->delete('receta_detalle', array('consecutivoDetalle' => $row->consecutivo_temporal));
            
        }
        
        $this->db->delete('productos_temporal', array('serie' => $serie));
        $this->db->trans_complete();
    }
    
    function getInventario($cvearticulo, $lote)
    {   
        $this->db->select('i.*');
        $this->db->from('inventario i');
        $this->db->join('articulos a', 'i.id = a.id');
        $this->db->where('a.cvearticulo', $cvearticulo);
        $this->db->where('lote', $lote);
        $query = $this->db->get();
        
        return $query;
    }
    
    function getTablaProductosTemporal($fechaSurtido)
    {
        $sql = "SELECT trim(cvearticulo) as cvearticulo, trim(descripcion) as descripcion, req, sur, idlote, fechacaducidad, case when '$fechaSurtido' >= '2014-09-01' then preciosinser else preciosinser end as precioven, case when '$fechaSurtido' >= '2014-09-01' then preciosinser * sur else preciosinser * sur end as total, trim(tipoprod) as tipoprod, trim(pres) as pres, consecutivo_temporal, serie 
        from productos_temporal t 
        join articulos using(cvearticulo) where usuario = ?;";
        $query = $this->db->query($sql, $this->session->userdata('aleatorio'));
        return $query;
    }

    function getInventarioByIDAndClvsucursalAndLote($id, $lote)
    {
        $sql = "SELECT i.*, a.precioven, a.servicio, a.tipoprod
        FROM inventario i
        join articulos a using(id)
        where id = ? and clvsucursal = ? and lote = ?;";

        $query = $this->db->query($sql, array($id, $this->session->userdata('clvsucursal'), $lote));

        return $query;

    }
    
    function getTablaProductosTemporal2()
    {
        $sql = "SELECT consecutivo_temporal, inventarioID, serie, a.id, cvearticulo, susa, descripcion, pres, a.precioven, a.ultimo_costo, a.servicio, a.tipoprod, case when ventaxuni = '1' then 'SI' else 'NO' end as ampuleo, ifnull(lote, 'SL') as lote, ifnull(caducidad, '9999-12-31') as caducidad, req, sur, ifnull(cantidad, 'NADA') as cantidad
FROM productos_temporal p
join articulos a using(cvearticulo)
left join inventario i using(inventarioID)
where p.usuario = ?;";
        $query = $this->db->query($sql, array($this->session->userdata('aleatorio')));
        return $query;
    }

    function getConsecutivo()
    {
        $this->db->select_max('consecutivo');
        $query = $this->db->get('receta');
        
        $row = $query->row();
        return $row->consecutivo + 1;
    }
    
    function cleanProductosTemporal()
    {
        $this->db->delete('productos_temporal', array('usuario' => $this->session->userdata('aleatorio')));
    }
    
    function fillProductosTemporal($folioReceta)
    {
        $productos = $this->getRecetaProdcutos($folioReceta);
        
        foreach($productos->result() as $row)
        {
            //usuario, cvearticulo, req, sur, consecutivo_temporal, serie, precio, fechacaducidad, idlote, consecutivo, id, inventarioID, fechaCaptura
            $data = array('usuario' => $this->session->userdata('aleatorio'), 'cvearticulo' => $row->cvearticulo, 'req' => $row->canreq, 'sur' => $row->cansur, 'idlote' => $row->lote, 'fechacaducidad' => $row->caducidad, 'consecutivo_temporal' => $row->consecutivoDetalle, 'precio' => $row->precio, 'consecutivo' => $row->consecutivo, 'id' => $row->id, 'inventarioID' => $this->getInventarioID($row->id, $row->lote));
            $this->db->insert('productos_temporal', $data);
        }
    }
    
    function getInventarioByIDAndLote($id, $lote)
    {
        $sql = "SELECT * FROM inventario i where clvsucursal = ? and id = ? and lote = ?;";

        $query = $this->db->query($sql, array((int)$this->session->userdata('clvsucursal'), (int) $id, (string)$lote));

        return $query;
    }

    function getInventarioID($id, $lote)
    {
        $sql = "SELECT inventarioID FROM inventario i where clvsucursal = ? and id = ? and lote = ?;";

        $query = $this->db->query($sql, array((int)$this->session->userdata('clvsucursal'), (int) $id, (string)$lote));

        if($query->num_rows() == 0)
        {
            return 0;
        }else
        {
            $row = $query->row();
            return $row->inventarioID;
        }
    }

    function fillProductosTemporalByConsecutivo($consecutivo)
    {
        $productos = $this->getRecetaProdcutosByConsecutivo($consecutivo);
        
        foreach($productos->result() as $row)
        {
            $data = array('usuario' => $this->session->userdata('aleatorio'), 'cvearticulo' => $row->cvearticulo, 'req' => $row->canreq, 'sur' => $row->cansur, 'idlote' => $row->lote, 'fechacaducidad' => $row->caducidad, 'consecutivo_temporal' => $row->consecutivoDetalle, 'precio' => $row->precio, 'consecutivo' => $row->consecutivo, 'id' => $row->id, 'inventarioID' => $this->getInventarioID($row->id, $row->lote));
            $this->db->insert('productos_temporal', $data);
        }
    }

    function getRango()
    {
        $this->db->where('usuario', $this->session->userdata('aleatorio'));
        $query = $this->db->get('temporal_rango_fechas');
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            $a = array('fecha_inicial' => $row->fecha_inicial, 'fecha_final' => $row->fecha_final, 'fecha_surtido' => $row->fecha_surtido, 'tiporequerimiento' => $row->tiporequerimiento);
        }else{
            $a = array('fecha_inicial' => null, 'fecha_final' => null, 'fecha_surtido' => null, 'tiporequerimiento' => null);
        }
        
        return $a;
    }
    
    function guardaRango($fecha_inicial, $fecha_final, $fecha_surtido, $tiporequerimiento)
    {
        $data = array('fecha_inicial' => $fecha_inicial, 'fecha_final' => $fecha_final, 'fecha_surtido' => $fecha_surtido, 'tiporequerimiento' => $tiporequerimiento, 'usuario' => $this->session->userdata('aleatorio'));
        
        $this->db->where('usuario', $this->session->userdata('aleatorio'));
        $query = $this->db->get('temporal_rango_fechas');
        
        if($query->num_rows() == 0)
        {
            $this->db->insert('temporal_rango_fechas', $data);
        }else{
            $this->db->update('temporal_rango_fechas', $data, array('usuario' => $this->session->userdata('aleatorio')));
        }
    }
    
    function checkFechaRango($fecha)
    {
        $this->db->where('usuario', $this->session->userdata('aleatorio'));
        $query = $this->db->get('temporal_rango_fechas');
        
        if($query->num_rows() == 0)
        {
            return 0;
        }else{
            $row = $query->row();
            $sql = "select * FROM fecha where ? between ? and ?;";
            $query2 = $this->db->query($sql, array($fecha, $row->fecha_inicial, $row->fecha_final));
            return $query2->num_rows();
        }
        
        
    }

    function checkFechaRemision($fecha)
    {
        $sql = "SELECT * FROM remision r where clvsucursal = ? and remisionStatus = 1 and ? between perini and perfin;";

        $query = $this->db->query($sql, array((int)$this->session->userdata('clvsucursal'), (string)$fecha));

        return $query->num_rows();
    }
    
    
    function getSucursalNombreBySession()
    {
        $this->db->select('trim(descsucursal) as descsucursal');
        $this->db->where('clvsucursal', $this->session->userdata('clvsucursal'));
        $query = $this->db->get('sucursales');
        
        if($query->num_rows() == 0)
        {
            return null;
        }else{
            $row = $query->row();
            return utf8_encode($row->descsucursal);
        }
    }
    
    function getSucursalNombreByClvSucursal($clvsucursal)
    {
        $this->db->select('trim(descsucursal) as descsucursal');
        $this->db->where('clvsucursal', $clvsucursal);
        $query = $this->db->get('sucursales');
        
        if($query->num_rows() == 0)
        {
            return null;
        }else{
            $row = $query->row();
            return utf8_encode($row->descsucursal);
        }
    }

    function getConfig()
    {
        $this->db->where('config', 1);
        $query = $this->db->get('temporal_config');
        
        if($query->num_rows() == 0)
        {
            $a = array('dias_diferencia' => 0);
        }else{
            $row = $query->row();
            $a = array('dias_diferencia' => $row->dias_receta);
        }
        
        return $a;
    }
    
    function checkCveArticulo($cveArticulo, $idprograma)
    {
        $sql = "SELECT descripcion, susa, pres, case when ventaxuni = 1 then 'AMPULEO' else '' end as ampuleo
from articulos a
join articulos_cobertura c on a.id = c.id and c.idprograma = ? and c.nivelatencion = ?
where cvearticulo = ? and activo = 1;";

        $query = $this->db->query($sql, array($idprograma, $this->session->userdata('nivelAtencion'), (string)$cveArticulo));

        if($query->num_rows() == 0)
        {
            return '0|NO ENCONTRADO O FUERA DE COBERTURA|NO ENCONTRADO O FUERA DE COBERTURA|NO ENCONTRADO O FUERA DE COBERTURA| ';
        }else{
            $row = $query->row();
            return $query->num_rows().'|'.trim(utf8_encode($row->descripcion)).'|'.trim(utf8_encode($row->susa)).'|'.trim(utf8_encode($row->pres)).'|'.$row->ampuleo;
        }
        
    }
    
    function cleanFolio($folioReceta)
    {
        $in = array("#", "'");
        $out = array("", "-");
        
        $folioReceta = str_replace($in, $out, $folioReceta);
        
        return $folioReceta;
    }
    
    function existReceta($folioReceta)
    {
        $this->db->where('folioreceta', $folioReceta);
        $query = $this->db->get('receta');
        
        $numRows = $query->num_rows();
        
        if($numRows > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    function recetaActiva($recetaID)
    {
        $this->db->where('recetaID', $recetaID);
        $this->db->where('statusReceta', 1);
        $query = $this->db->get('receta_electronica_control');

        if($query->num_rows() == 0)
        {
            return FALSE;
        }else
        {
            return TRUE;
        }
    }
    
    function getArticuloScaner($ean)
    {
        $sql = "SELECT * FROM inventario i where ean = ? and DATEDIFF(caducidad, now()) > 30 and ean > 0 and cantidad > 0 order by caducidad asc limit 1";
        $query = $this->db->query($sql, (double)$ean);
        
        
        return $query;
    }
    
    function saveRecetaDetalle($inventarioID, $ean)
    {
        $sql = "insert into receta_detalle_temporal (aleatorio, inventarioID, sur, ean) values(?, ?, ?, ?) on duplicate key update sur = sur + 1, ean = values(ean);";
        $this->db->query($sql, array((double)$this->session->userdata('aleatorio'), (double)$inventarioID, 1, (double)$ean));
    }
    
    function detalleRecetaRapida()
    {
        $sql = "SELECT * FROM receta_detalle_temporal r
join inventario i using(inventarioID)
join articulos a using(id)
where aleatorio = ?;";

        $query = $this->db->query($sql, $this->session->userdata('aleatorio'));
        
        return $query;
    }
    
    function cleanProductosRapida()
    {
        $this->db->delete('receta_detalle_temporal', array('aleatorio' => $this->session->userdata('aleatorio')));
    }
    
    function deleteSerieRapida($serie)
    {
        $this->db->delete('receta_detalle_temporal', array('serie' => $serie));
    }
    
    function checkInventory($inventarioID, $cantidadMinima)
    {
        $sql = "SELECT * FROM inventario i where inventarioID = ? and cantidad >= ?;";
        $query = $this->db->query($sql, array((integer)$inventarioID, (integer)$cantidadMinima));
        
        return $query;
    }
    
    function checkEANSL($ean)
    {
        $sql = "SELECT * FROM inventario i where ean = ? and lote = 'SL';";
        $query = $this->db->query($sql, (double)$ean);
        return $query;
    }
    
    function guardaRapidaDB($folioReceta)
    {
        $this->db->trans_start();
        
        $exist = $this->existReceta($folioReceta);
        
        if($exist == false)
        {
            
            $insert = array(
                'folioreceta' => $folioReceta, 
                'usuario' => $this->session->userdata('usuario'), 
                'clvsucursal' => $this->session->userdata('clvsucursal'), 
                'completa' => 0
            );
            $this->db->set('alta', 'now()', false);
            $this->db->set('fecha', 'date(now())', false);
            $this->db->insert('receta', $insert);
            
            $consecutivo = $this->db->insert_id();
            
            $query = $this->detalleRecetaRapida();
            
            foreach($query->result() as $row)
            {
                $i = $row->sur;
                
                do{
                    
                    $checkInventoryQuery = $this->getArticuloScaner($row->ean);
                    
                    $checkInventory = $checkInventoryQuery->num_rows();
                    
                    if($checkInventory > 0)
                    {
                        $inv = $checkInventoryQuery->row();
                        
                        if((int)$inv->cantidad >= (int)$row->sur)
                        {
                            $detalle = array(
                                'consecutivo' => $consecutivo, 
                                'id' => $inv->id,
                                'lote' => $inv->lote, 
                                'caducidad' => $inv->caducidad, 
                                'canreq' => $row->sur, 
                                'cansur' => $row->sur, 
                                'descontada' => 0, 
                                'precio' => 0, 
                                'ubicacion' => $inv->ubicacion, 
                                'marca' => $inv->marca, 
                                'comercial' => $inv->comercial, 
                                'costo' => $inv->costo
                            );
                            $this->db->set('altaDetalle', 'now()', false);
                            $this->db->insert('receta_detalle', $detalle);
                            
                            $invData = array(
                                'cantidad' => ($inv->cantidad - $row->sur),  
                                'tipoMovimiento' => 2, 
                                'subtipoMovimiento' => 10, 
                                'receta' => $consecutivo, 
                                'usuario' => $this->session->userdata('usuario'), 
                                'movimientoID' => 0, 
                                'clvsucursal' => $this->session->userdata('clvsucursal')
                            );
                            
                            $this->db->set('ultimo_movimiento', 'now()', false);
                            $this->db->update('inventario', $invData, array('inventarioID' => $inv->inventarioID));
                            
                            $i = $i - $row->sur;
                        }else{
                            $detalle = array(
                                'consecutivo' => $consecutivo, 
                                'id' => $inv->id, 
                                'lote' => $inv->lote, 
                                'caducidad' => $inv->caducidad, 
                                'canreq' => $inv->cantidad, 
                                'cansur' => $inv->cantidad, 
                                'descontada' => 0, 
                                'precio' => 0, 
                                'ubicacion' => $inv->ubicacion, 
                                'marca' => $inv->marca, 
                                'comercial' => $inv->comercial, 
                                'costo' => $inv->costo
                            );
                            $this->db->set('altaDetalle', 'now()', false);
                            $this->db->insert('receta_detalle', $detalle);
                            
                            $invData = array(
                                'cantidad' => ($inv->cantidad - $inv->cantidad), 
                                'tipoMovimiento' => 2, 
                                'subtipoMovimiento' => 10, 
                                'receta' => $consecutivo, 
                                'usuario' => $this->session->userdata('usuario'), 
                                'movimientoID' => 0, 
                                'clvsucursal' => $this->session->userdata('clvsucursal')
                            );
                            $this->db->set('ultimo_movimiento', 'now()', false);
                            $this->db->update('inventario', $invData, array('inventarioID' => $inv->inventarioID));
                            
                            $i = $i - $inv->cantidad;
                        }
                        
                        
                    }else{
                        
                        $checkSLQuery = $this->checkEANSL($row->ean);
                        
                        if($checkSLQuery->num_rows() > 0)
                        {
                            
                        }else{
                            
                        }
                        
                    }
                    
                }while($i > 0);
                
            }
            
            $this->db->trans_complete();
        
            if ($this->db->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
        
        
        
    }

    function getArticuloByID($id)
    {

        $this->db->where('id', $id);
        $query = $this->db->get('articulos');

        return $query;
    }

    function getUbicacionLimit()
    {
        $sql = "SELECT ubicacion FROM ubicacion u where clvsucursal = ? limit 1;";

        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal')));

        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->ubicacion;
        }else
        {
            return 0;
        }
    }

    function getCIE103($term)
    {
        $sql = "SELECT *, concat(cie, ' | ', cieDescripcion) as value FROM cie103 c where cie like '%$term%' or cieDescripcion like '%$term%';";

        $query = $this->db->query($sql);

        return json_encode($query->result());
    }
    
    function getCIE104($term)
    {
        $sql = "SELECT *, concat(cie, ' | ', cieDescripcion) as value FROM cie104 c where cie like '%$term%' or cieDescripcion like '%$term%';";

        $query = $this->db->query($sql);

        return json_encode($query->result());
    }

    function recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento, $cvesuministro)
    {
        
        if($idprograma == 1000){
            $pro = null;
        }else{
            $pro = "and r.idprograma = $idprograma";
        }
        
        if($tiporequerimiento == 1000){
            $req = null;
        }else{
            $req = "and r.tiporequerimiento = $tiporequerimiento";
        }

        if($cvesuministro == 1000){
           $sumis = null;
        }else{
            $sumis = "and x.tipoprod = $cvesuministro";
        }
        
        $s = "SELECT descsucursal, preciosinser, tipoprod, programa, requerimiento, folioreceta, apaterno, amaterno, nombre, canreq,
             cvepaciente, cie103, cie104, cveservicio, x.cvearticulo, concat(x.susa,' ',x.descripcion,' ', x.pres) as descripcion, cansur, nombremedico, cvemedico, fecha, fechaexp, x.precioven, x.servicio
            from receta r
            join sucursales s on r.clvsucursal=s.clvsucursal
            join programa p on r.idprograma = p.idprograma
            join temporal_requerimiento q on r.tiporequerimiento = q.tiporequerimiento
            join receta_detalle d on d.consecutivo = r.consecutivo
            join articulos x on d.id=x.id
            where fecha between ? and ?  and r.clvsucursal = ? $pro  $req $sumis
            order by r.fecha, r.folioreceta";
        $query = $this->db->query($s, array($fecha1, $fecha2, (int)$this->session->userdata('clvsucursal')));
        $this->reportes_model->insertaQuery($this->db->last_query(), 'REPORTE DE RECETA DEL PERIODO DEL ' . $fecha1 . ' AL ' . $fecha2);
        return $query;
        
    }

    function getSubida()
    {
        $data = array('fecha' => date('Y-m-d H:i:s'));
        $this->db->insert('temporal_subida', $data);
        return $this->db->insert_id();
    }

    function getSubidas()
    {
        $sql = "SELECT subida, suc, descsucursal, min(fechasurtido) as minimo, max(fechasurtido) as maximo, sum(req) as sumreq, sum(sur) as sumsur
from temporal_receta r 
join temporal_subida s using(subida) 
join sucursales u on suc = u.clvsucursal
where estatusSubida = 0
group by subida, suc, descsucursal
order by subida desc;";
        $query = $this->db->query($sql);
        return $query;
    }

    function getSubidaBySubida($subida)
    {
        $sql = "SELECT suc, descsucursal, folio, fechasurtido, cvepaciente, concat(nombre, ' ', paterno, ' ', materno) as nombrepaciente, p.programa, q.requerimiento, cvemedico, nombremedico, r.clave, concat(susa, ' ', descripcion, ' ', pres) as descri, req, sur, ifnull(c.idprograma, 'FC') as cobertura
from temporal_receta r
left join articulos a on a.cvearticulo = r.clave
left join sucursales s on suc = clvsucursal
left join articulos_cobertura c on a.id = c.id and s.nivelatencion = c.nivelatencion and r.programa = c.idprograma
left join programa p on p.idprograma = r.programa
left join temporal_requerimiento q on tiporequerimiento = r.requerimiento
where subida = ?;";
        $query = $this->db->query($sql, $subida);
        return $query;
    }

    function validaRecetaCargaExist($clvsucursal, $folioreceta)
    {
        $this->db->where('clvsucursal', (int)$clvsucursal);
        $this->db->where('folioreceta', (string)$folioreceta);
        $query = $this->db->get('receta');

        return $query->num_rows();
    }

    function getDetalleSubida($subida, $receta)
    {
        $sql = "SELECT t.*, a.id, a.precioven, ultimo_costo, servicio, tipoprod
FROM temporal_receta t
join articulos a on t.clave = a.cvearticulo
where subida = ? and receta = ?;";

        $query = $this->db->query($sql, array((int)$subida, (int)$receta));

        return $query;
    }

    function cargaSubidaRecetas($subida)
    {
        $this->db->trans_start();
        $sql = "SELECT * FROM temporal_receta t where subida = ? group by folio, receta;";
        $query = $this->db->query($sql, array($subida));

        foreach ($query->result() as $row) {
            
            if($this->validaRecetaCargaExist($row->suc, $row->folio) == 0)
            {
                $data = array(
                    'folioreceta'       => $row->folio,
                    'fechaexp'          => $row->fecha,
                    'fecha'             => $row->fechasurtido,
                    'idprograma'        => $row->programa,
                    'tiporequerimiento' => $row->requerimiento,
                    'cveservicio'       => 1,
                    'cvepaciente'       => $row->cvepaciente,
                    'nombre'            => $row->nombre,
                    'apaterno'          => $row->paterno,
                    'amaterno'          => $row->materno,
                    'genero'            => $row->sexo,
                    'edad'              => $row->edad,
                    'cvemedico'         => $row->cvemedico,
                    'nombremedico'      => $row->nombremedico,
                    'usuario'           => $this->session->userdata('usuario'),
                    'clvsucursal'       => $row->suc,
                    'subida'            => $row->subida
                );

                $this->db->set('alta', 'now()', false);
                $this->db->insert('receta', $data);

                $consecutivo = $this->db->insert_id();

                $query2 = $this->getDetalleSubida($row->subida, $row->receta);

                foreach ($query2->result() as $row2) {

                    if($this->validaCargaRecetasDetalle($consecutivo, $row2->id, 'SL', $row2->sur) == 0)
                    {
                        $data2 = array(
                            'consecutivo'   => $consecutivo,
                            'id'            => $row2->id,
                            'lote'          => 'SL',
                            'caducidad'     => '9999-12-31',
                            'canreq'        => $row2->req,
                            'cansur'        => $row2->sur,
                            'precio'        => $row2->precioven,
                            'ubicacion'     => 0,
                            'marca'         => '',
                            'comercial'     => '',
                            'costo'         => $row2->ultimo_costo,
                            'servicio'      => $row2->servicio,
                            'iva'           => $row2->tipoprod
                        );

                        $this->db->set('altaDetalle', 'now()', false);
                        $this->db->insert('receta_detalle', $data2);
                    }
                    
                }


            }

        }

        $this->db->update('temporal_subida', array('estatusSubida' => 1), array('subida' => $subida));

        $this->db->trans_complete();
    }

    function validaCargaRecetasDetalle($consecutivo, $id, $lote, $cansur)
    {
        $this->db->where('consecutivo', $consecutivo);
        $this->db->where('id', $id);
        $this->db->where('lote', $lote);
        $this->db->where('cansur', $cansur);
        $query = $this->db->get('receta_detalle');

        return $query->num_rows();
    }

    function getInventory($clvsucursal, $id, $sur)
    {
        $sql = "SELECT * FROM inventario i where clvsucursal = ? and id = ? and caducidad > date(now()) and cantidad >= ? order by caducidad asc limit 1;";
        $query = $this->db->query($sql, array((int)$clvsucursal, (int)$id, (int)$sur));

        return $query;
    }

    function getInventorySL($clvsucursal, $id)
    {
        $sql = "SELECT * FROM inventario i where clvsucursal = ? and id = ? and lote = 'SL' order by caducidad asc limit 1;";
        $query = $this->db->query($sql, array((int)$clvsucursal, (int)$id));

        return $query;
    }

    function descuentaInventario($subida)
    {
        $this->db->trans_start();

        $sql = "SELECT d.*, clvsucursal FROM receta r join receta_detalle d using(consecutivo) where subida = ? and descontada = 0;";
        $query = $this->db->query($sql, array($subida));

        foreach ($query->result() as $row)
        {
            
            $inv = $this->getInventory($row->clvsucursal, $row->id, $row->cansur);

            if($inv->num_rows > 0)
            {
                $i = $inv->row();

                $data = array(
                    'cantidad'          => ($i->cantidad - $row->cansur),
                    'tipoMovimiento'    => 2,
                    'subtipoMovimiento' => 10,
                    'receta'            => $row->consecutivo,
                    'usuario'           => $this->session->userdata('usuario'),
                    'movimientoID'      => 0
                );

                $this->db->set('ultimo_movimiento', 'now()', false);
                $this->db->update('inventario', $data, array('inventarioID' => $i->inventarioID));
                
                $dataReceta = array(
                    'lote'          => $i->lote,
                    'caducidad'     => $i->caducidad,
                    'descontada'    => 1,
                    'ubicacion'     => $i->ubicacion,
                    'marca'         => $i->marca,
                    'comercial'     => $i->comercial
                );

                $this->db->update('receta_detalle', $dataReceta, array('consecutivoDetalle' => $row->consecutivoDetalle));


            }else
            {
                $invSL = $this->getInventorySL($row->clvsucursal, $row->id);

                if($invSL->num_rows() > 0)
                {
                    $i = $invSL->row();

                    $data = array(
                        'cantidad'          => ($i->cantidad - $row->cansur),
                        'tipoMovimiento'    => 2,
                        'subtipoMovimiento' => 10,
                        'receta'            => $row->consecutivo,
                        'usuario'           => $this->session->userdata('usuario'),
                        'movimientoID'      => 0
                    );

                    $this->db->set('ultimo_movimiento', 'now()', false);
                    $this->db->update('inventario', $data, array('inventarioID' => $i->inventarioID));
                    
                    $dataReceta = array(
                        'lote'          => $i->lote,
                        'caducidad'     => $i->caducidad,
                        'descontada'    => 1,
                        'ubicacion'     => $i->ubicacion,
                        'marca'         => $i->marca,
                        'comercial'     => $i->comercial
                    );

                    $this->db->update('receta_detalle', $dataReceta, array('consecutivoDetalle' => $row->consecutivoDetalle));
                }else
                {
                    $data = array(
                        'id'                => $row->id,
                        'lote'              => 'SL',
                        'caducidad'         => '9999-12-31',
                        'cantidad'          => (0 - $row->cansur),
                        'tipoMovimiento'    => 2,
                        'subtipoMovimiento' => 10,
                        'receta'            => $row->consecutivo,
                        'usuario'           => $this->session->userdata('usuario'),
                        'movimientoID'      => 0,
                        'ean'               => 0,
                        'marca'             => '',
                        'comercial'         => '',
                        'costo'             => 0,
                        'clvsucursal'       => $row->clvsucursal,
                        'ubicacion'         => 0
                    );

                    $this->db->set('ultimo_movimiento', 'now()', false);
                    $this->db->insert('inventario', $data);
                    
                    $dataReceta = array(
                        'lote'          => 'SL',
                        'caducidad'     => '9999-12-31',
                        'descontada'    => 1,
                        'ubicacion'     => 0,
                        'marca'         => '',
                        'comercial'     => ''
                    );

                    $this->db->update('receta_detalle', $dataReceta, array('consecutivoDetalle' => $row->consecutivoDetalle));
                }
            }

        }

        $this->db->trans_complete();
    }

    function validaBorrado($consecutivo)
    {
        $sql = "SELECT * FROM receta_detalle r where consecutivo = ? and remision > 0;";
        $query = $this->db->query($sql, array($consecutivo));

        return $query->num_rows();
    }

    function getProductosEliminar()
    {
        $sql = "SELECT * FROM productos_temporal p where usuario = ?;";
        $query = $this->db->query($sql, array($this->session->userdata('aleatorio')));

        return $query;
    }

    function deleteRecetaCompleta($consecutivo)
    {
        
        if($this->validaBorrado($consecutivo) == 0)
        {
            $this->db->trans_start();
            $query = $this->getProductosEliminar();

            foreach ($query->result() as $row) {
                $this->deleteProducto($row->serie);
            }

            $this->db->delete('receta', array('consecutivo' => $consecutivo));
            $this->db->trans_complete();

            return 'Eliminada correctamente.';
        }else
        {
            return 'Esta receta tiene productos remisionados';
        }
        
    }

    function getInventarioFix1($clvsucursal, $id, $inventarioID)
    {
        $sql = "SELECT * FROM inventario i where clvsucursal = ? and id = ? and inventarioID <> ?  and cantidad > 0 order by caducidad limit 1;";
        $query = $this->db->query($sql, array($clvsucursal, $id, $inventarioID));
        return $query;
    }

    function getInventarioFix2($clvsucursal, $id)
    {
        $sql = "SELECT * FROM inventario i where clvsucursal = ? and id = ? and lote = 'SL' limit 1;";
        $query = $this->db->query($sql, array($clvsucursal, $id));
        return $query;
    }

    function ajustaInventarioExcedenteSurtido($inventarioID, $consecutivo)
    {
        $this->load->model('Inventario_model');
        $sql = "SELECT * FROM inventario i where inventarioID = ? and lote <> 'SL' and cantidad < 0;";

        $query = $this->db->query($sql, array($inventarioID));

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $query2 = $this->getInventarioFix1($row->clvsucursal, $row->id, $row->inventarioID);

            if($query2->num_rows() > 0)
            {
                $row2 = $query2->row();

                if($row2->cantidad >= ($row->cantidad * -1))
                {

                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    //echo "<h1>Cambio 1</h1>";
                    //echo "<pre>";
                    //print_r($row);
                    //print_r($dataInv1);
                    //echo "</pre>";

                    $dataInv2 = array('cantidad' => $row->cantidad + $row2->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv2, array('inventarioID' => $row2->inventarioID));

                    //echo "<h1>Cambio 2</h1>";
                    //echo "<pre>";
                    //print_r($row2);
                    //print_r($dataInv2);
                    //echo "</pre>";

                }else
                {
                    $dataInv1 = array('cantidad' => $row->cantidad + $row2->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    //$this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    //echo "<h1>Cambio 1</h1>";
                    //echo "<pre>";
                    //print_r($row);
                    //print_r($dataInv1);
                    //echo "</pre>";

                    $dataInv2 = array('cantidad' => $row2->cantidad + $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    //$this->db->update('inventario', $dataInv2, array('inventarioID' => $row2->inventarioID));

                    //echo "<h1>Cambio 2</h1>";
                    //echo "<pre>";
                    //print_r($row2);
                    //print_r($dataInv2);
                    //echo "</pre>";
                }
            }else
            {
                $query3 = $this->getInventarioFix2($row->clvsucursal, $row->id);

                if($query3->num_rows() > 0)
                {
                    $row3 = $query3->row();

                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    //echo "<h1>Cambio 1</h1>";
                    //echo "<pre>";
                    //print_r($row);
                    //print_r($dataInv1);
                    //echo "</pre>";

                    $dataInv2 = array('cantidad' => $row3->cantidad + $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv2, array('inventarioID' => $row3->inventarioID));

                    //echo "<h1>Cambio 2</h1>";
                    //echo "<pre>";
                    //print_r($row3);
                    //print_r($dataInv2);
                    //echo "</pre>";
                }else
                {
                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    //echo "<h1>Cambio 1</h1>";
                    //echo "<pre>";
                    //print_r($row);
                    //print_r($dataInv1);
                    //echo "</pre>";

                    $dataInsert = array('id' => $row->id, 'lote' => 'SL', 'caducidad' => '9999-12-31', 'cantidad' => $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ean' => 0, 'marca' => '', 'costo' => 0, 'clvsucursal' => $row->clvsucursal, 'ubicacion' => $this->Inventario_model->getUbicacionLibreByClvsucursal($row->clvsucursal), 'comercial' => '', 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->insert('inventario', $dataInsert);

                    //echo "<h1>Cambio 2</h1>";
                    //echo "<pre>";
                    //print_r($dataInsert);
                    //echo "</pre>";
                }
            }


        }
    }

    function ajustaInventarioExcedenteSurtidoPrueba($inventarioID, $consecutivo)
    {
        $this->load->model('Inventario_model');
        $sql = "SELECT * FROM inventario i where inventarioID = ? and lote <> 'SL' and cantidad < 0;";

        $query = $this->db->query($sql, array($inventarioID));

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $query2 = $this->getInventarioFix1($row->clvsucursal, $row->id, $row->inventarioID);

            if($query2->num_rows() > 0)
            {
                $row2 = $query2->row();

                if($row2->cantidad >= ($row->cantidad * -1))
                {

                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    echo "<h1>Cambio 1</h1>";
                    echo "<pre>";
                    print_r($row);
                    print_r($dataInv1);
                    echo "</pre>";

                    $dataInv2 = array('cantidad' => $row->cantidad + $row2->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv2, array('inventarioID' => $row2->inventarioID));

                    echo "<h1>Cambio 2</h1>";
                    echo "<pre>";
                    print_r($row2);
                    print_r($dataInv2);
                    echo "</pre>";

                }else
                {
                    $dataInv1 = array('cantidad' => $row->cantidad + $row2->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    echo "<h1>Cambio 1</h1>";
                    echo "<pre>";
                    print_r($row);
                    print_r($dataInv1);
                    echo "</pre>";

                    $dataInv2 = array('cantidad' => $row2->cantidad + $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv2, array('inventarioID' => $row2->inventarioID));

                    echo "<h1>Cambio 2</h1>";
                    echo "<pre>";
                    print_r($row2);
                    print_r($dataInv2);
                    echo "</pre>";

                    $this->ajustaInventarioExcedenteSurtidoPrueba($row2->inventarioID, $consecutivo);
                }
            }else
            {
                $query3 = $this->getInventarioFix2($row->clvsucursal, $row->id);

                if($query3->num_rows() > 0)
                {
                    $row3 = $query3->row();

                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    echo "<h1>Cambio 1</h1>";
                    echo "<pre>";
                    print_r($row);
                    print_r($dataInv1);
                    echo "</pre>";

                    $dataInv2 = array('cantidad' => $row3->cantidad + $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv2, array('inventarioID' => $row3->inventarioID));

                    echo "<h1>Cambio 2</h1>";
                    echo "<pre>";
                    print_r($row3);
                    print_r($dataInv2);
                    echo "</pre>";
                }else
                {
                    $dataInv1 = array('cantidad' => 0, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->update('inventario', $dataInv1, array('inventarioID' => $row->inventarioID));

                    echo "<h1>Cambio 1</h1>";
                    echo "<pre>";
                    print_r($row);
                    print_r($dataInv1);
                    echo "</pre>";

                    $dataInsert = array('id' => $row->id, 'lote' => 'SL', 'caducidad' => '9999-12-31', 'cantidad' => $row->cantidad, 'tipoMovimiento' => 3, 'subtipoMovimiento' => 11, 'receta' => $consecutivo, 'usuario' => $this->session->userdata('usuario'), 'movimientoID' => 0, 'ean' => 0, 'marca' => '', 'costo' => 0, 'clvsucursal' => $row->clvsucursal, 'ubicacion' => $this->Inventario_model->getUbicacionLibreByClvsucursal($row->clvsucursal), 'comercial' => '', 'ultimo_movimiento' => FECHAYHORA);
                    $this->db->insert('inventario', $dataInsert);

                    echo "<h1>Cambio 2</h1>";
                    echo "<pre>";
                    print_r($dataInsert);
                    echo "</pre>";
                }
            }


        }else
        {
            //echo "aqui";
        }
    }

    function getColectivosValidadosValuados()
    {
    	$sql = "SELECT colectivoID, movimientoID, c.clvsucursal, descsucursal, folio, programa, sum(subtotal) as subtotal
FROM movimiento_prepedido_valuado p
join movimiento m using(movimientoID)
join colectivo c using(movimientoID)
join sucursales s on c.clvsucursal = s.clvsucursal
join programa o using(idprograma)
group by movimientoID;";
    }

}