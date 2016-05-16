<?php
class Admin_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */
    function __construct()
    {
        parent::__construct();
    }
    
    function getUsuario()
    {
        $sql = "SELECT usuario, clvusuario, password, nombreusuario, case when estaactivo = 0 then 'INACTIVO' else 'ACTIVO' end as estaactivo, descsucursal, puesto, estaactivo as activo, nivelUsuario, u.clvsucursal, case when u.valuacion = 0 then 'NO' else 'SI' end as valuacionDescripcion, u.valuacion
FROM usuarios u
join sucursales s using(clvsucursal)
join puesto p using(clvpuesto)
join nivelUsuario n using(nivelUsuarioID)
order by estaactivo asc, puesto asc, clvsucursal asc;";
        $query = $this->db->query($sql);
        return $query;
    }
    
    function getUsuarioByUsuario()
    {
        $sql = "SELECT usuario, clvusuario, password, nombreusuario, case when estaactivo = 0 then 'INACTIVO' else 'ACTIVO' end as estaactivo, descsucursal, puesto, last_login
FROM usuarios u
join sucursales s using(clvsucursal)
join puesto p using(clvpuesto)
where usuario = ?;";
        $query = $this->db->query($sql, $this->session->userdata('usuario'));
        return $query;
    }
    
    function getPermisosByUsuario($usuario)
    {
        $sql = "SELECT menu, submenu, s.submenuID, opcion FROM submenu s
join menu m using(menuID)
left join usuarios_submenu u on s.submenuID = u.submenuID and usuario = ?
order by menuID, s.submenuID;";

        $query = $this->db->query($sql, $usuario);
        
        return $query;
    }
    
    function savePermiso($usuario, $submenu)
    {
        $this->db->where('usuario', $usuario);
        $this->db->where('submenuID', $submenu);
        $query = $this->db->get('usuarios_submenu');
        
        if($query->num_rows() == 0)
        {
            $data = array(
                'usuario' => $usuario,
                'submenuID' => $submenu,
                'opcion' => 1
                );
            
            $this->db->insert('usuarios_submenu', $data);
        }else{
            $data = array(
                'usuario' => $usuario,
                'submenuID' => $submenu
                );
            $this->db->delete('usuarios_submenu', $data);
        }
    }

    function update_avatar($avatar)
    {
        $update = array(
                'avatar' => $avatar
        );
        $this->db->where('usuario', $this->session->userdata('usuario'));
        $this->db->update('usuarios', $update);
        $this->session->set_userdata($update);
        return "<img src=\"".base_url()."assets/avatars/".$avatar."\" />";
    }
    
    function checkOldPassword($oldP)
    {
        $this->db->where('usuario', $this->session->userdata('usuario'));
        $this->db->where('password', $oldP);
        
        $query = $this->db->get('usuarios');
        return $query->num_rows();
    }
    
    function saveNewPassword($password)
    {
        $data = array('password' => $password);
        $this->db->update('usuarios', $data, array('usuario' => $this->session->userdata('usuario')));
    }

    function getPuestoCombo()
    {
        $query = $this->db->get('puesto');
        $a = array();
        foreach($query->result() as $row)
        {
            $a[$row->clvpuesto] = ($row->puesto);   
        }
        return $a;
    }

    function getSucursalesCombo()
    {

        
        $this->db->order_by('clvsucursal');
        $query = $this->db->get('sucursales');
        
        $a = array('0' => 'TODAS');
        foreach($query->result() as $row)
        {
            $a[$row->clvsucursal] = $row->clvsucursal. ' - ' . trim($row->descsucursal);
        }
        
        return $a;
    }

    function getJurisOptions($nivelUsuario)
    {
        $a = null;

        if($nivelUsuario == 1 || $nivelUsuario == 2 || $nivelUsuario == 3)
        {
            $this->db->where('jurisdiccionActiva', 1);
            $this->db->order_by('numjurisd');
            $query = $this->db->get('jurisdiccion');
            foreach ($query->result() as $row) {
                $a .= '<option value="'.$row->numjurisd.'">'.$row->jurisdiccion.'</option>';
            }
        }else
        {
            $a .= '<option value="0">TODAS</option>';
        }

        return $a;
    }

    function getSucursalesOptions($nivelUsuario, $numjurisd)
    {
        $a = null;

        if($nivelUsuario == 3 || $nivelUsuario == 4 || $nivelUsuario == 6 || $nivelUsuario == 8)
        {
            $sql = "SELECT clvsucursal, descsucursal
            FROM sucursales
            WHERE clvsucursal = 0;";

            $query = $this->db->query($sql);
        }elseif($nivelUsuario == 5 || $nivelUsuario == 7)
        {
            $sql = "SELECT clvsucursal, descsucursal
            FROM sucursales
            WHERE clvsucursal = ?;";

            $query = $this->db->query($sql, array(ALMACEN));
        }else
        {
            $sql = "SELECT clvsucursal, descsucursal
            FROM sucursales
            WHERE activa = 1 and tiposucursal = 1 and numjurisd = ?;";

            $query = $this->db->query($sql, array($numjurisd));
        }


        foreach ($query->result() as $row) {
            $a .= '<option value="'.$row->clvsucursal.'">'.$row->clvsucursal . ' - ' .$row->descsucursal .'</option>';
        }

        return $a;
    }

    function getPuestoOptions($nivelUsuario)
    {
        $this->db->where('nivelUsuarioIDR', $nivelUsuario);
        $query = $this->db->get('puesto');

        $a = null;

        foreach ($query->result() as $row) {
            $a .= '<option value="'.$row->clvpuesto.'">'.$row->puesto .'</option>';
        }

        return $a;
    }

    function getJurisCombo()
    {

        $this->db->where('jurisdiccionActiva', 1);
        $this->db->order_by('numjurisd');
        $query = $this->db->get('jurisdiccion');
        
        $a = array('0' => 'TODAS');
        foreach($query->result() as $row)
        {
            $a[$row->numjurisd] = $row->numjurisd. ' - ' . trim($row->jurisdiccion);
        }
        
        return $a;
    }

    function getNivelUsuario()
    {

        
        $this->db->order_by('nivelUsuarioID');
        $query = $this->db->get('nivelUsuario');
        

        foreach($query->result() as $row)
        {
            $a[$row->nivelUsuarioID] = $row->nivelUsuario;
        }
        
        return $a;
    } 

    function getValuacionUsuario()
    {
        $a = array('0' => 'No');
        $a['1'] = 'Si';
        return $a;

    }

    function insertPermisosByUsuario($usuario)
    {
        $sql = "INSERT IGNORE INTO usuarios_submenu (SELECT usuario, submenuID, 1 FROM usuarios u, puesto_permisos p where u.clvpuesto = p.clvpuesto and u.usuario = ? and estaactivo = 1);";
        $this->db->query($sql, array($usuario));
    }

    function updateValuacionByUsuario($usuario)
    {
        $sql = "UPDATE usuarios u, puesto p set u.valuacion = p.valuacion where u.clvpuesto = p.clvpuesto and u.usuario = ?;";
        $this->db->query($sql, array($usuario));
    }

    function insertaUsuario($clvusuario, $password, $nombreusuario, $clvsucursal, $clvpuesto, $clvnivel)
    {
        $this->db->where('clvusuario', $clvusuario);
        $query = $this->db->get('usuarios');
        
        if($query->num_rows() == 0)
        {
            $data = array('clvusuario' => $clvusuario, 'password' => $password, 'nombreusuario' => $nombreusuario, 'clvsucursal' => $clvsucursal, 'clvpuesto' => $clvpuesto, 'estaactivo' => True, 'nivelUsuarioID' => $clvnivel, 'valuacion' => 0);
            $this->db->insert('usuarios', $data);
            $usuario = $this->db->insert_id();
            $this->updateValuacionByUsuario($usuario);
            $this->insertPermisosByUsuario($usuario);
        }else{
            
        }
    }

    function actualizaSucursal($clvsucursal, $numjurisd, $nombreSucursalPersonalizado, $domicilioSucursalPersonalizado, $nivelAtencion, $diaped, $director, $administrador)
    {
        $data = array('clvsucursal' => $clvsucursal, 'nombreSucursalPersonalizado' => $nombreSucursalPersonalizado, 'domicilioSucursalPersonalizado' => $domicilioSucursalPersonalizado, 'director' => $director, 'administrador' => $administrador);
        $this->db->replace('sucursales_ext', $data);

        $dataSucursales = array(
            'numjurisd'     => $numjurisd,
            'diaped'        => $diaped,
            'nivelAtencion' => $nivelAtencion
        );

        $this->db->update('sucursales', $dataSucursales, array('clvsucursal' => $clvsucursal));
    }

    function getServiciosByClvSucursal($clvsucursal)
    {
        $sql = "SELECT f.*, case when s.cveservicios is null then 0 else 1 end as activo FROM fservicios f
left join sucursales_servicios s on f.cveservicios = s.cveservicios and s.clvsucursal = ?;";

        $query = $this->db->query($sql, array($clvsucursal));

        return $query;
    }

    function guardaSucursalServicio($clvsucursal, $cveservicios)
    {
        $this->db->where('clvsucursal', $clvsucursal);
        $this->db->where('cveservicios', $cveservicios);
        $query = $this->db->get('sucursales_servicios');

        if($query->num_rows() == 0)
        {
            $data = array('clvsucursal' => $clvsucursal, 'cveservicios' => $cveservicios);
            $this->db->insert('sucursales_servicios', $data);
        }else
        {
            $this->db->delete('sucursales_servicios', array('clvsucursal' => $clvsucursal, 'cveservicios' => $cveservicios));
        }
    }

    function getCountNivelServicios()
    {
        $sql = "SELECT * FROM temporal_nivel_atencion t join programa p where p.activo = 1;";

        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    function getPrograma()
    {
        $this->db->where('activo', 1);
        $query = $this->db->get('programa');

        return $query;
    }

    function getNivelAtencion()
    {
        $query = $this->db->get('temporal_nivel_atencion');

        return $query;
    }

    function guardaArticuloCobertura($id, $idprograma, $nivelatencion)
    {
        $this->db->where('id', $id);
        $this->db->where('idprograma', $idprograma);
        $this->db->where('nivelatencion', $nivelatencion);
        $query = $this->db->get('articulos_cobertura');

        if($query->num_rows() == 0)
        {
            $data = array('id' => $id, 'idprograma' => $idprograma, 'nivelatencion' => $nivelatencion);
            $this->db->insert('articulos_cobertura', $data);
        }else
        {
            $this->db->delete('articulos_cobertura', array('id' => $id, 'idprograma' => $idprograma, 'nivelatencion' => $nivelatencion));
        }
    }

    function getCoberturasByArticulosCrossing($id)
    {
        $sql = "SELECT t.*, p.*, case when id is null then 'FALSE' else 'TRUE' end as checked
FROM temporal_nivel_atencion t
join programa p
left join articulos_cobertura c on t.nivelatencion = c.nivelatencion and p.idprograma = c.idprograma and id = ?
where p.activo = 1
order by t.nivelatencion, p.idprograma;";
        
        $query = $this->db->query($sql, array($id));

        return $query;
    }

    function addBufferByClvSucursal($clvsucursal)
    {
        $sql = "INSERT IGNORE INTO buffer (clvsucursal, id, buffer) (SELECT clvsucursal, id, 0 FROM articulos a
join sucursales s
where s.activa = 1 and a.activo = 1 and s.clvsucursal = ?);";

        $this->db->query($sql, array($clvsucursal));
    }

    function guardaBuffer($id, $clvsucursal, $buffer)
    {
        $data = array('id' => $id, 'clvsucursal' => $clvsucursal, 'buffer' => $buffer);
        $this->db->replace('buffer', $data);
    }

    function getCountArticuloBuffer($clvsucursal, $tipoprod)
    {
        $sql = "SELECT count(*) as cuenta FROM articulos a
join buffer b on a.id = b.id and b.clvsucursal = ?
where a.activo = 1 and tipoprod = ?;";
        $query = $this->db->query($sql, array($clvsucursal, $tipoprod));
        $row = $query->row();
        
        return $row->cuenta;
    }

    function getArticulosLimitBuffer($tipoprod, $clvsucursal, $limit, $offset = 0)
    {
        $sql = "SELECT a.id, cvearticulo, susa, descripcion, pres, buffer
FROM articulos a
join buffer b on a.id = b.id and b.clvsucursal = ?
where a.activo = 1 and tipoprod = ?
order by tipoprod, cvearticulo * 1
limit ? offset ?;";
        
        return $this->db->query($sql, array($clvsucursal, $tipoprod, $limit, (int)$offset));
    }

    function getPuesto()
    {
        $sql = "SELECT clvpuesto, puesto, nivelUsuario, case when valuacion = 0 then 'NO' else 'SI' end as valuacion
FROM puesto p
join nivelUsuario n on p.nivelUsuarioIDR = n.nivelUsuarioID
order by nivelUsuarioID, clvpuesto;";

        $query = $this->db->query($sql);

        return $query;
    }

    function getPerfilByClvPuesto($clvpuesto)
    {
        $sql = "SELECT clvpuesto, puesto, nivelUsuarioIDR, valuacion
FROM puesto p
where clvpuesto = ?;";

        $query = $this->db->query($sql, array($clvpuesto));

        return $query;
    }

    function insertPuesto($puesto, $nivelUsuarioID, $valuacion)
    {
        $this->db->where('nivelUsuarioIDR', $nivelUsuarioID);
        $this->db->where('puesto', $puesto);
        $query = $this->db->get('puesto');

        if($query->num_rows() == 0)
        {
            $data = array('puesto' => strtoupper($puesto), 'nivelUsuarioIDR' => $nivelUsuarioID, 'valuacion' => $valuacion);
            $this->db->insert('puesto', $data);
        }

    }

    function updateValuacionByClvPuesto($clvpuesto)
    {
        $sql = "UPDATE usuarios SET valuacion = (SELECT valuacion from puesto where clvpuesto = ?) where estaactivo = 1;";
        $this->db->query($sql, array($clvpuesto));
    }

    function updatePuesto($clvpuesto, $puesto, $nivelUsuarioID, $valuacion)
    {
        $data = array('puesto' => strtoupper($puesto), 'nivelUsuarioIDR' => $nivelUsuarioID, 'valuacion' => $valuacion);
        $this->db->update('puesto', $data, array('clvpuesto' => $clvpuesto));
    }

    function getPermisosByClvPuesto($clvpuesto)
    {
        $sql = "SELECT menu, submenu, s.submenuID, opcion FROM submenu s
join menu m using(menuID)
left join puesto_permisos u on s.submenuID = u.submenuID and clvpuesto = ?
order by menuID, s.submenuID;";

        $query = $this->db->query($sql, $clvpuesto);
        
        return $query;
    }
    
    function savePermisoPerfil($clvpuesto, $submenu)
    {
        $this->db->where('clvpuesto', $clvpuesto);
        $this->db->where('submenuID', $submenu);
        $query = $this->db->get('puesto_permisos');
        
        if($query->num_rows() == 0)
        {
            $data = array(
                'clvpuesto' => $clvpuesto,
                'submenuID' => $submenu,
                'opcion' => 1
                );
            
            $this->db->insert('puesto_permisos', $data);
        }else{
            $data = array(
                'clvpuesto' => $clvpuesto,
                'submenuID' => $submenu
                );
            $this->db->delete('puesto_permisos', $data);
        }
    }

    function savePermisoByClvPuestoBulk($clvpuesto)
    {
        $sql_elimina = "DELETE FROM usuarios_submenu where usuario in(select usuario from usuarios where clvpuesto = ? and estaactivo = 1);";

        $this->db->query($sql_elimina, array($clvpuesto));

        $sql_inserta = "INSERT IGNORE INTO usuarios_submenu (SELECT usuario, submenuID, 1 FROM usuarios u, puesto_permisos p where u.clvpuesto = p.clvpuesto and p.clvpuesto = ? and estaactivo = 1);";
        $this->db->query($sql_inserta, array($clvpuesto));
    }

}