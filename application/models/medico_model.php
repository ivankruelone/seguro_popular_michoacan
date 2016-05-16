<?php
class Medico_model extends CI_Model {

    /**
     * Catalogos_model::__construct()
     * 
     * @return
     */
    function __construct()
    {
        parent::__construct();
    }

    function addConfig($cvemedico, $nombremedico, $cveservicios)
    {
    	$data = array(
    		'clvsucursal'	=> $this->session->userdata('clvsucursal'),
    		'cvemedico'		=> $cvemedico,
    		'cveservicios'	=> $cveservicios,
    		'nombremedico'	=> $nombremedico,
    		'usuario'		=> $this->session->userdata('usuario')
    	);

    	$this->db->where('clvsucursal', $this->session->userdata('clvsucursal'));
    	$this->db->where('usuario', $this->session->userdata('usuario'));
    	$query = $this->db->get('medico_sucursal');

    	if($query->num_rows() == 0)
    	{
    		$this->db->insert('medico_sucursal', $data);
    	}else{
    		$this->db->update('medico_sucursal', $data, array('clvsucursal' => $this->session->userdata('clvsucursal'), 'usuario' => $this->session->userdata('usuario')));
    	}
    }

    function getConfig()
    {
    	$this->db->where('clvsucursal', $this->session->userdata('clvsucursal'));
    	$this->db->where('usuario', $this->session->userdata('usuario'));
    	$query = $this->db->get('medico_sucursal');

    	return $query;
    }

    function save()
    {
    	$medico = $this->getConfig();

    	$med = $medico->row();

    	$expediente = $this->input->post('expediente');
    	$pat = $this->input->post('pat');
    	$mat = $this->input->post('mat');
    	$nombre = $this->input->post('nombre');
    	$sexo = $this->input->post('sexo');
    	$edad = $this->input->post('edad');
        $idprograma = $this->input->post('idprograma');
        $cie103 = $this->input->post('cie103');
        $cie104 = $this->input->post('cie104');

    	$data = array(
    		'clvsucursal'	=> $this->session->userdata('clvsucursal'),
    		'usuario'		=> $this->session->userdata('usuario'),
    		'cvemedico'		=> $med->cvemedico,
    		'nombremedico'	=> $med->nombremedico,
    		'cveservicios'	=> $med->cveservicios,
    		'cvepaciente'	=> $expediente,
    		'apaterno'		=> $pat,
    		'amaterno'		=> $mat,
    		'nombre'		=> $nombre,
    		'genero'		=> $sexo,
    		'edad'			=> $edad,
            'idprograma'    => $idprograma,
            'cie103'        => $cie103,
            'cie104'        => $cie104,
    	);

        $dataPaciente = array(
            'cvepaciente'   => $expediente,
            'apaterno'      => $pat,
            'amaterno'      => $mat,
            'nombre'        => $nombre,
            'genero'        => $sexo,
            'edad'          => $edad,
            'idprograma'    => $idprograma,
        );

    	$this->db->insert('receta_electronica_control', $data);

    	$recetaID = $this->db->insert_id();

    	if($recetaID > 0)
    	{

            $this->savePaciente($dataPaciente);

            //Inicia Producto 1
    		$cveArticulo1 = $this->input->post('cveArticulo1');
            $req1 = $this->input->post('req1');
            $dosis1 = $this->input->post('dosis1');

            $q1 = $this->getArticulo($cveArticulo1);

            if($q1->num_rows() > 0 && (int)$req1 > 0 && strlen($dosis1) > 2)
            {
                $row = $q1->row();

                $dataDetalle = array(
                    'recetaID'  => $recetaID,
                    'id'        => $row->id,
                    'req'       => $req1,
                    'dosis'     => strtoupper($dosis1)
                );

                $this->db->insert('receta_electronica_detalle', $dataDetalle);
            }
            //Fin Producto 1

            //Inicia Producto 2
            $cveArticulo2 = $this->input->post('cveArticulo2');
            $req2 = $this->input->post('req2');
            $dosis2 = $this->input->post('dosis2');

            $q2 = $this->getArticulo($cveArticulo2);

            if($q2->num_rows() > 0 && (int)$req2 > 0 && strlen($dosis2) > 2)
            {
                $row = $q2->row();

                $dataDetalle = array(
                    'recetaID'  => $recetaID,
                    'id'        => $row->id,
                    'req'       => $req2,
                    'dosis'     => strtoupper($dosis2)
                );

                $this->db->insert('receta_electronica_detalle', $dataDetalle);
            }
            //Fin Producto 2

            //Inicia Producto 3
            $cveArticulo3 = $this->input->post('cveArticulo3');
            $req3 = $this->input->post('req3');
            $dosis3 = $this->input->post('dosis3');

            $q3 = $this->getArticulo($cveArticulo3);

            if($q3->num_rows() > 0 && (int)$req3 > 0 && strlen($dosis3) > 2)
            {
                $row = $q3->row();

                $dataDetalle = array(
                    'recetaID'  => $recetaID,
                    'id'        => $row->id,
                    'req'       => $req3,
                    'dosis'     => strtoupper($dosis3)
                );

                $this->db->insert('receta_electronica_detalle', $dataDetalle);
            }
            //Fin Producto 3

            return $recetaID;

    	}else{
            return 0;
        }
    }

    function savePaciente($data)
    {

        $this->db->replace('paciente', $data);

    }

    function getArticulo($cveArticulo)
    {
        $this->db->where('cvearticulo', $cveArticulo);
        $query = $this->db->get('articulos');
        return $query;
    }

    function getRecetas()
    {
        $sql = "SELECT r.*, p.programa
FROM receta_electronica_control r
join receta_electronica_detalle d using(recetaID)
join programa p using(idprograma)
where statusReceta = 1 and surtida = 0 and clvsucursal = ? and usuario = ?
group by recetaID
order by fecha desc;";

        $query = $this->db->query($sql, array($this->session->userdata('clvsucursal'), $this->session->userdata('usuario')));

        return $query;
    }

    function getReceta($recetaID)
    {
        $sql = "SELECT r.*, p.programa, descsucursal, desservicios, concat(calle, ', ', colonia, ', ', municipio) as domicilio, numjurisd, jurisdiccion, desgenero
FROM receta_electronica_control r
join sucursales s using(clvsucursal)
join programa p using(idprograma)
join fservicios f using(cveservicios)
join jurisdiccion j using(numjurisd)
join genero g using(genero)
where recetaID = ?;";

        $query = $this->db->query($sql, array($recetaID));

        return $query;
    }

    function getRecetaDetalle($recetaID)
    {
        $sql = "SELECT r.*, cvearticulo, susa, descripcion, pres FROM receta_electronica_detalle r
join articulos a using(id)
where recetaID = ?;";

        $query = $this->db->query($sql, array($recetaID));

        return $query;
    }

    function getCIEDescripcionByCIE103($cie)
    {
        $this->db->where('cie', $cie);
        $query = $this->db->get('cie103');

        if($query->num_rows() == 0)
        {
            return null;
        }else
        {
            $row = $query->row();
            return $row->cieDescripcion;
        }
    }

    function getCIEDescripcionByCIE104($cie)
    {
        $this->db->where('cie', $cie);
        $query = $this->db->get('cie104');

        if($query->num_rows() == 0)
        {
            return null;
        }else
        {
            $row = $query->row();
            return $row->cieDescripcion;
        }
    }

    function headerRecetaElectronica($recetaID)
    {
        $query = $this->getReceta($recetaID);
        $row = $query->row();

        
        $logo = array(
                                  'src' => base_url().'assets/img/logo.png',
                                  'width' => '120'
                        );
                        
        
        $paciente = '<table style="width: 100%; ">
            <tr>
                <td colspan="8" width="700px"><b>DATOS DEL  PACIENTE<br /></b></td>
            </tr>
            <tr>
                <td>No. Afiliacion: </td>
                <td>'.$row->cvepaciente.'</td>
                <td>Nombre: </td>
                <td>'.$row->nombre.'</td>
                <td>Apellido Paterno: </td>
                <td>'.$row->apaterno.'</td>
                <td>Apellido Materno: </td>
                <td>'.$row->amaterno.'</td>
            </tr>
            <tr>
                <td>Edad: </td>
                <td>'.$row->edad.'</td>
                <td>Genero: </td>
                <td>'.$row->desgenero.'</td>
                <td>Cobertura: </td>
                <td colspan="3">'.$row->programa.'</td>
            </tr>
            ';

        if(CIE103 == 1)
        {
            $paciente .= '
            <tr>
                <td>CIE Primaria: </td>
                <td>'.$row->cie103.'</td>
                <td colspan="6">'.$this->getCIEDescripcionByCIE103($row->cie103).'</td>
            </tr>';
        }

        if(CIE104 == 1)
        {
            $paciente .= '
            <tr>
                <td>CIE Primaria: </td>
                <td>'.$row->cie104.'</td>
                <td colspan="6">'.$this->getCIEDescripcionByCIE104($row->cie104).'</td>
            </tr>';
        }

        $paciente .= '
        </table>';

        $tabla = '<table cellpadding="1">
            <tr>
                <td rowspan="5" width="100px">'.img($logo).'</td>
                <td rowspan="5" width="450px" align="center"><font size="8">'.COMPANIA.'<br />Unidad: '.$row->clvsucursal.' - '.$row->descsucursal.'<br />Jurisdiccion: '.$row->numjurisd.' - '.$row->jurisdiccion.'</font><br />Domicilio: '.trim($row->domicilio).'<br />Folio de receta: '.barras($row->recetaID).'</td>
                <td width="75px">RecetaID: </td>
                <td width="95px" align="right">'.$row->recetaID.'</td>
            </tr>
            <tr>
                <td width="75px">Fecha Consulta: </td>
                <td width="95px" align="right">'.$row->fecha.'</td>
            </tr>
            <tr>
                <td width="75px">Clave de Medico: </td>
                <td width="95px" align="right">'.$row->cvemedico.'</td>
            </tr>
            <tr>
                <td width="75px">Nombre: </td>
                <td width="95px" align="right">'.$row->nombremedico.'</td>
            </tr>
            <tr>
                <td width="75px">Servicio: </td>
                <td width="95px" align="right">'.$row->desservicios.'</td>
            </tr>
            <tr>
                <td>'.$paciente.'</td>
            </tr>
        </table>';
        
        return $tabla;
    }

    function detalleRecetaElectronica($recetaID)
    {
        $query = $this->getRecetaDetalle($recetaID);
        
        $tabla = '
        <style>
        table
        {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
        }
        th
        {
            font-weight: bold;
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
                <th width="20px">#</th>
                <th width="60px">Clave</th>
                <th width="185px">Nom. Generico</th>
                <th width="210px">Descripci&oacute;n</th>
                <th width="185px">Presentacion</th>
                <th width="60px" align="right">Requeridas</th>
            </tr>
        </thead>
        <tbody>
        ';

        $piezas = 0;
        $n = 1;

        foreach($query->result() as $row)
        {

            
            
            $tabla.= '<tr>
                <td rowspan="2" width="20px" style="font-size: xx-large; ">'.$n.'</td>
                <td width="60px"><b>'.$row->cvearticulo.'</b></td>
                <td width="185px">'.$row->susa.'</td>
                <td width="210px">'.$row->descripcion.'</td>
                <td width="185px">'.$row->pres.'</td>
                <td width="60px" align="right">'.number_format($row->req, 0).'</td>
            </tr>
            <tr>   
                <td colspan="5"><b>'.$row->dosis.'</b><br /><br /><br /></td>
            </tr>
            ';


            $piezas = $piezas + $row->req;
            $n++;

        }
            
        

        
        $tabla.= '</tbody>
        <tfoot>
            <tr>
                <td colspan="5" align="right"><b>Subtotales</b></td>
                <td align="right"><b>'.number_format($piezas, 0).'</b></td>
            </tr>
            
        </tfoot>
        </table>
        <br />';
        
     
        
        return $tabla;
    }
    
    function finRecetaElectronica($recetaID){
      $query = $this->getReceta($recetaID);
      $row = $query->row();  
      
      $tabla ='<br /><br /><br /><br /><br /><br />
      <table style="width: 100%; ">
            <tr>
                <th width="150px" align="center"><strong></strong></th>
                <th width="190px" align="center"><strong><b>FIRMA DEL M&Eacute;DICO</b></strong></th>
                <th width="130px" align="center"><strong></strong></th>
                <th width="190px" align="center"><strong><b>FIRMA DEL PACIENTE</b></strong></th>
                <th width="150px" align="center"><strong></strong></th>
                <th width="150px" align="center"><strong></strong></th>
            </tr>
            <br /><br /><br />
      
            <tr>
                <th width="150px" align="center"><strong></strong></th>
                <th width="190px" align="center"><strong><b>_________________________________________</b></strong></th>
                <th width="130px" align="center"><strong></strong></th>
                <th width="190px" align="center"><strong><b>_________________________________________</b></strong></th>
                <th width="150px" align="center"><strong></strong></th>
                <th width="150px" align="center"><strong></strong></th>
                
            </tr>
            <tbody>';
      
      $tabla.= '<tr>
                <td width="150px" align="center"></td>
                <td width="190px" align="center"><br />C&Eacute;DULA:'.$row->cvemedico.'<br />'.$row->nombremedico.'</td>
                <td width="130px" align="center"></td>
                <td width="190px" align="center">CLAVE DEL PACIENTE:'.$row->cvepaciente.'<br />'.$row->nombre.' '.$row->apaterno.' '.$row->amaterno.'</td>
                <td width="150px" align="center"></td>
                <td width="150px" align="center"></td>
            </tr>
            </tbody>
            </table>';
        return $tabla;
        
    }

    function cancela($recetaID)
    {
        $this->db->update('receta_electronica_control', array('statusReceta' => 0), array('recetaID' => $recetaID, 'surtida' => 0));
    }

    function validaArticulo($cveArticulo, $idprograma)
    {
        $sql = "SELECT descripcion, susa, pres, case when ventaxuni = 1 then 'AMPULEO' else '' end as ampuleo
from articulos a
join articulos_cobertura c on a.id = c.id and c.idprograma = ? and c.nivelatencion = ?
where cvearticulo = ? and activo = 1;";
        
        $query = $this->db->query($sql, array($idprograma, $this->session->userdata('nivelAtencion'), (string)$cveArticulo));

        if($query->num_rows() == 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

}