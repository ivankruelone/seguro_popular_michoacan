<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Captura extends CI_Controller
{
    var $idInicial = 9776;
    var $idFinal = 1000000;

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }

        $this->load->model('captura_model');
        $this->load->helper('utilities');

    }
    
    public function recetas($fechaCon = null)
    {
        $this->captura_model->cleanProductosTemporal();
        $data['subtitulo'] = "Captura de recetas";
        $data['js'] = "captura/recetas_js";
        $data['categoria'] = $this->captura_model->getCveServicioCombo();
        $data['requerimiento'] = $this->captura_model->getRequerimientoCombo();
        $data['tipoReceta'] = $this->captura_model->getProgramaCombo();
        $data['sexo'] = $this->captura_model->getSexoCombo();
        $data['rango'] = $this->captura_model->getRango();
        $data['config'] = $this->captura_model->getConfig();
        $data['fechaCon'] = $fechaCon;
        $this->load->view('main', $data);
    }
    
    function verifica_folio()
    {
        $folioReceta = $this->input->post('folioReceta');
        echo $this->captura_model->getRecetaExist2(strtoupper($folioReceta));
    }
    
        function verifica_lote()
    {
        $lote = $this->input->post('lote');
        $cvearticulo = $this->input->post('cvearticulo');
        echo $this->captura_model->getlote($lote, $cvearticulo);
    }
    
    function actualizaLotes()
    {
        $cveArticulo = $this->input->post('cveArticulo');
        echo $this->captura_model->getLotesCombo($cveArticulo);
    }
    
    function busca_expediente()
    {
        $term = $this->input->get_post('term');
        echo $this->captura_model->getPadronByCvePacienteJson($term);
    }
    
    function busca_cveArticulo()
    {
        $term = $this->input->get_post('term');
        $idprograma = $this->input->get_post('idprograma');
        echo $this->captura_model->getArticuloByCveArticulo($term, $idprograma);
    }
    
    function verifica_expediente()
    {
        $expediente = $this->input->post('expediente');
        echo $this->captura_model->getPacienteFromCvePaciente($expediente);
    }
    
    function verifica_cveMedico()
    {
        $cveMedico = $this->input->post('cveMedico');
        echo $this->captura_model->getMedicoFromCveMedico($cveMedico);
    }
    
    function add_producto()
    {
        $cveArticulo = $this->input->post('cveArticulo');
        $req = $this->input->post('req');
        $sur = $this->input->post('sur');
        $precio = $this->input->post('precio');
        $lote = $this->input->post('lote');
        $fechacad = $this->input->post('fechacad');
        
        
        
        $this->captura_model->insertProducto($cveArticulo, $req, $sur, $precio, $lote, $fechacad);
    }
    
    function actualiza_tabla_productos()
    {
        $data['query'] = $this->captura_model->getTablaProductosTemporal2();
        $this->load->view('captura/tabla_productos', $data);
    }
    
    function actualiza_tabla_productos_ver()
    {
        $data['query'] = $this->captura_model->getTablaProductosTemporal2();
        $this->load->view('captura/tabla_productos_ver', $data);
    }

    function eliminar($serie)
    {
        $this->captura_model->deleteProducto($serie);
        $data['query'] = $this->captura_model->getTablaProductosTemporal2();
        $this->load->view('captura/tabla_productos', $data);
    }
    
    function guardalote($cvearticulo,$idlote,$fechacad)
    {
        $sql = "select * from lotes where lote = ? and cvearticulo = ? and status = 't'";
              
        $query = $this->db->query($sql, array(trim(strtoupper($idlote)),trim($cvearticulo)));
        
        if($query->num_rows() == 0)
        {
            $this->db->set('id', "nextval('lotes_seq')", false);
            $sql = array('lote' => trim(strtoupper($idlote)), 'cvearticulo' => $cvearticulo,'cantidad' => 0,'tiposurtido' => 2,
            'fechaingreso' => date('Y-m-d'), 'fechacaducidad' => $fechacad, 'status' => 't');
            $this->db->insert('lotes', $sql);
        }else{
            
            
            $updateData = array('fechacaducidad' => $fechacad);
            $where = array('lote' => trim(strtoupper($idlote)), 'cvearticulo' => $cvearticulo);
            
            $this->db->update('lotes', $updateData, $where);
            
        }        
        
    }
    
    function guardar()
    {
        
        $this->db->trans_start();
        $fechaConsulta = $this->input->post('fechaConsulta');
        $fechaSurtido = $this->input->post('fechaSurtido');
        $folioReceta = $this->input->post('folioReceta');
        $tipoReceta = $this->input->post('tipoReceta');
        $categoria = $this->input->post('categoria');
        $expediente = $this->input->post('expediente');
        $paterno = $this->input->post('paterno');
        $materno = $this->input->post('materno');
        $nombre = $this->input->post('nombre');
        $sexo = $this->input->post('sexo');
        $edad = $this->input->post('edad');
        $cveMedico = $this->input->post('cveMedico');
        $medico = $this->input->post('medico');
        $tipoReq = $this->input->post('tipoReq');
        $tipo = $this->input->post('tipo');
        $consecutivo_edicion = $this->input->post('consecutivo');
        
        $cie103 = $this->input->post('cie103');
        $cie104 = $this->input->post('cie104');

        if($sexo == null)
        {
            $sexo = 0;
        }
        
        if($tipo == 'captura')
        {
            
            $data = array(
                'clvsucursal' => $this->session->userdata('clvsucursal'),
                'cvemedico' => $cveMedico, 
                'cveservicio' => $categoria, 
                'cvepaciente' => $expediente, 
                'fecha' => $fechaSurtido,
                'nombre' => ($nombre), 
                'apaterno' => ($paterno), 
                'genero' => trim($sexo), 
                'edad' => ($edad), 
                'amaterno' => ($materno), 
                'nombremedico' => ($medico),
                'tiporequerimiento' => $tipoReq, 
                'folioreceta' => $folioReceta, 
                'fechaexp' => $fechaConsulta, 
                'idprograma' => $tipoReceta, 
                'usuario' => $this->session->userdata('usuario'),
                'cie103' => $cie103,
                'cie104' => $cie104,
                );
                
                $this->db->set('alta', 'now()', false);
                $this->db->insert('receta', $data);
                
                $consecutivo = $this->db->insert_id();
                
        }elseif($tipo == 'edita'){
            $data = array(
                'clvsucursal' => $this->session->userdata('clvsucursal'),
                'cvemedico' => $cveMedico, 
                'cveservicio' => $categoria, 
                'cvepaciente' => $expediente, 
                'fecha' => $fechaSurtido,
                'nombre' => ($nombre), 
                'apaterno' => ($paterno), 
                'genero' => trim($sexo), 
                'edad' => ($edad), 
                'amaterno' => ($materno), 
                'nombremedico' => ($medico),
                'tiporequerimiento' => $tipoReq, 
                'folioreceta' => $folioReceta, 
                'fechaexp' => $fechaConsulta, 
                'idprograma' => $tipoReceta, 
                'usuario' => $this->session->userdata('usuario'),
                'cie103' => $cie103,
                'cie104' => $cie104,
                );
                
                $this->db->set('cambio', 'now()', false);
                $this->db->update('receta', $data, array('consecutivo' => $consecutivo_edicion));
                
                $sql_borra_audita = "delete from receta_audita where consecutivo = ?";
                $this->db->query($sql_borra_audita, $consecutivo_edicion);
                
                $consecutivo = $consecutivo_edicion;
        }
        
        
            
            $sql_paciente = "insert into paciente (cvepaciente, nombre, apaterno, amaterno, genero, edad, idprograma) values (?, ?, ?, ?, ?, ?, ?) on duplicate key update nombre = values(nombre), apaterno = values(apaterno), amaterno = values(amaterno), genero = values(genero), edad = values(edad), idprograma = values(idprograma);";
            $this->db->query($sql_paciente, array((string)$expediente, (string)$nombre, (string)$paterno, (string)$materno, (int)$sexo, (int)$edad, (int)$tipoReceta));
            
            $sql_medico = "insert into medico (cvemedico, nombremedico) values (?, ?) on duplicate key update nombremedico = values(nombremedico);";
            $this->db->query($sql_medico, array($cveMedico, $medico));

        
        $productos = $this->captura_model->getTablaProductosTemporal2();
        
        foreach($productos->result() as $row)
        {
            $data2 = array(
                'consecutivo' => $consecutivo,
                'id' => $row->id,
                'lote' => $row->lote,
                'caducidad' => $row->caducidad,
                'canreq' => $row->req,
                'cansur' => $row->sur,
                'descontada' => 0,
                'precio' => $row->precioven,
                'costo' => $row->ultimo_costo,
                'servicio' => $row->servicio,
                'iva' => $row->tipoprod,
                );
            
            if($row->consecutivo_temporal == 0)
            {
               $this->db->set('altaDetalle', "now()", false);
               $this->db->insert('receta_detalle', $data2);
               
               
               if($row->cantidad == 'NADA')
               {
                    $cantidad  = ((int)0 - (int)$row->sur);
                    $data = array(
                        'id' => $row->id,
                        'lote' => $row->lote,
                        'caducidad' => $row->caducidad,
                        'cantidad' => $cantidad,
                        'tipoMovimiento' => 2,
                        'subtipoMovimiento' => 10,
                        'receta' => $consecutivo,
                        'usuario' => $this->session->userdata('usuario'),
                        'movimientoID' => 0,
                        'clvsucursal' => $this->session->userdata('clvsucursal')
                        );
                        
                    $this->db->set('ultimo_movimiento', 'now()', false);
                    $this->db->insert('inventario', $data);
               }else{
                    $cantidad  = ((int)$row->cantidad - (int)$row->sur);
                    $data = array(
                        'id' => $row->id,
                        'lote' => $row->lote,
                        'caducidad' => $row->caducidad,
                        'cantidad' => $cantidad,
                        'tipoMovimiento' => 2,
                        'subtipoMovimiento' => 10,
                        'receta' => $consecutivo,
                        'usuario' => $this->session->userdata('usuario'),
                        'movimientoID' => 0,
                        'clvsucursal' => $this->session->userdata('clvsucursal')
                        );
                        
                    $this->db->set('ultimo_movimiento', 'now()', false);
                    $this->db->update('inventario', $data, array('inventarioID' => $row->inventarioID));
               }
               
               
            }else{
                //$this->db->update('receta', $data, array('consecutivo' => $row->consecutivo_temporal));
            }
            

            
        }
        
        $this->captura_model->cleanProductosTemporal();
        
        
        
        $this->db->trans_complete();
        
        
        if ($this->db->trans_status() === TRUE)
        {
            $this->util->postReceta($consecutivo);
            $this->util->postInventarioReceta($consecutivo);// generate an error... or use the log_message() function to log your error
        } 
        
        echo $consecutivo;
        
        
    }

    public function rango()
    {
        $data['subtitulo'] = "Definir rango de captura";
        $data['js'] = "captura/rango_js";
        $data['rango'] = $this->captura_model->getRango();
        $data['requerimiento'] = $this->captura_model->getRequerimientoCombo();
        $this->load->view('main', $data);
    }

    function rango__agregar()
    {
        $fecha_inicial = $this->input->post('fecha_inicial');
        $fecha_final = $this->input->post('fecha_final');
        $fecha_surtido = $this->input->post('fecha_surtido');
        $tiporequerimiento = $this->input->post('tipoReq');
        $this->captura_model->guardaRango($fecha_inicial, $fecha_final, $fecha_surtido, $tiporequerimiento);
        redirect('captura/recetas');
    }
    
    function verifica_fecha_rango()
    {
        $fecha = $this->input->post('fecha');
        echo $this->captura_model->checkFechaRango($fecha);
    }

function valida_fecha()
    {
        $fecha = $this->input->post('fechacad');
        $fechacap = $this->input->post('fechacap');
        $fechalim1 = Date('Y-m-d',strtotime('+3 months', strtotime($fechacap)));
        $fechalim2 = Date('Y-m-d',strtotime('+120 months', strtotime($fechacap)));
        if ($fecha < $fechalim1 or $fecha > $fechalim2)
        {
            $res = 1;
        }
        else
        {
            $res = 1;
        }
        echo $res;
            
    }

    public function edicion()
    {
        $data['subtitulo'] = "Definir rango de captura";
        $data['js'] = "captura/edicion_js";
        $data['rango'] = $this->captura_model->getRango();
        $data['mensaje'] = $this->session->flashdata('mensaje');
        $this->load->view('main', $data);
    }
    
    function edicion_submit()
    {
        $folioReceta = $this->input->post('folioReceta');
        $query = $this->captura_model->getReceta($folioReceta);

        $validaRemision = $this->captura_model->validaRecetaRemisionada($folioReceta);

        if($validaRemision == 0)
        {
            if($query->num_rows() > 0)
            {
                $row = $query->row();
                
                if(trim($row->clvsucursal) == trim($this->session->userdata('clvsucursal')))
                {
                        $this->session->set_flashdata('folioReceta', $folioReceta);
                        redirect('captura/edita/');
                }else{
                    $this->session->set_flashdata('mensaje', "Este folio: ".$folioReceta.", esta capturado en la sucursal: " . $row->clvsucursal . ' - ' . $this->captura_model->getSucursalNombreByClvSucursal($row->clvsucursal));
                    redirect('captura/edicion');
                }
            }else{
                    $this->session->set_flashdata('mensaje', "Este folio: ".$folioReceta.", no existe.");
                    redirect('captura/edicion');
            }

        }else
        {
                    $this->session->set_flashdata('mensaje', "Este folio: ".$folioReceta.", ya esta remisionado.");
                    redirect('captura/edicion');
        }
        
        
    }
    
    function edita($consecutivo = null)
    {
        if($consecutivo == null)
        {
            $folioReceta = $this->session->flashdata('folioReceta');
            
        }else{
            $folioReceta = $this->captura_model->getFolioRecetaByConsecutivo($consecutivo);
        }
        

        $this->session->keep_flashdata('folioReceta');

        $this->captura_model->cleanProductosTemporal();
        $this->captura_model->fillProductosTemporal($folioReceta);
        $data['subtitulo'] = "Modifica receta";
        $data['js'] = "captura/recetas_js";
        $data['categoria'] = $this->captura_model->getCveServicioCombo();
        $data['requerimiento'] = $this->captura_model->getRequerimientoCombo();
        $data['tipoReceta'] = $this->captura_model->getProgramaCombo();
        $data['sexo'] = $this->captura_model->getSexoCombo();
        $data['query'] = $this->captura_model->getTablaProductosTemporal2();
        $data['rango'] = $this->captura_model->getRango();
        $data['config'] = $this->captura_model->getConfig();
        $data['receta'] = $this->captura_model->getRecetaCompleta($folioReceta);
        $this->load->view('main', $data);
    }
    
    function ver($consecutivo)
    {
        $this->captura_model->cleanProductosTemporal();
        $this->captura_model->fillProductosTemporalByConsecutivo($consecutivo);
        $data['subtitulo'] = "Modifica receta";
        $data['js'] = "captura/recetas_js";
        $data['categoria'] = $this->captura_model->getCveServicioCombo();
        $data['requerimiento'] = $this->captura_model->getRequerimientoCombo();
        $data['tipoReceta'] = $this->captura_model->getProgramaCombo();
        $data['sexo'] = $this->captura_model->getSexoCombo();
        $data['query'] = $this->captura_model->getTablaProductosTemporal2();
        $data['rango'] = $this->captura_model->getRango();
        $data['config'] = $this->captura_model->getConfig();
        $data['receta'] = $this->captura_model->getRecetaCompletaByConsecutivo($consecutivo);
        $this->load->view('main', $data);
    }

    function elimina_receta()
    {
        $folioReceta = $this->session->flashdata('folioReceta');
        
        $this->db->update('receta', array('status' => 'f'), array('folioreceta' => $folioReceta));
        
        redirect('captura/recetas');
    }
    
    function verificaCveArticulo()
    {
        $cveArticulo = $this->input->post('cveArticulo');
        $idprograma = $this->input->post('idprograma');
        echo $this->captura_model->checkCveArticulo($cveArticulo, $idprograma);
    }
    
    function procesaLotes()
    {
        $query = $this->db->get('temporal_lotes');
        foreach($query->result() as $row)
        {
            $data = array(
                'lote' => $row->lote,
                'cvearticulo' => $row->cvearticulo,
                'cantidad' => 0,
                'tiposurtido' => 2,
                'fechaingreso' => date('Y-m-d'),
                'fechacaducidad' => $row->fechacaducidad
                );
                
                $this->db->where('lote', $row->lote);
                $this->db->where('cvearticulo', $row->cvearticulo);
                $query2 = $this->db->get('lotes');
                
                if($query2->num_rows() == 0)
                {
                    $this->db->set('id', "nextval('lotes_seq')", false);
                    $this->db->insert('lotes', $data);
                }
        }
    }
    
    function liberar($consecutivo)
    {
        $this->util->postLiberareceta($consecutivo);
        redirect('workspace');
    }

    public function rapida($fechaCon = null)
    {
        $this->captura_model->cleanProductosRapida();
        $data['subtitulo'] = "Captura rapida de recetas";
        $data['js'] = "captura/rapida_js";
        $data['config'] = $this->captura_model->getConfig();
        $this->load->view('main', $data);
    }
    
    function folio_submit()
    {
        $folioReceta = $this->input->post('folioReceta');
        $folioReceta = $this->captura_model->cleanFolio($folioReceta);
        
        $exist = $this->captura_model->existReceta($folioReceta);
        if($exist == 1)
        {
            $this->session->set_flashdata('error', 'Este folio: <b>'.$folioReceta.'</b> ya existe, no se puede duplicar.');
            redirect('captura/rapida');
            
        }
        
        $data['subtitulo'] = "Captura rapida de recetas";
        $data['js'] = "captura/folio_submit_js";
        $data['config'] = $this->captura_model->getConfig();
        $data['folioReceta'] = $folioReceta;
        $this->load->view('main', $data);
        
    }
    
    function buscaArticuloScaner()
    {
        $ean = trim($this->input->post('ean'));
        
        $query = $this->captura_model->getArticuloScaner($ean);
        
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            
            $this->captura_model->saveRecetaDetalle($row->inventarioID, $ean);
            
            echo '1';
        }else{
            echo '0';
        }
    }
    
    function actualiza_tabla_productos_rapida()
    {
        $data['query'] = $this->captura_model->detalleRecetaRapida();
        $this->load->view('captura/tabla_productos_rapida', $data);
    }
    
    function eliminar_rapida($serie)
    {
        $this->captura_model->deleteSerieRapida($serie);
        $this->actualiza_tabla_productos_rapida();
    }
    
    function guardaRapida()
    {
        $folioReceta = $this->input->post('folioReceta');
        $resultado = $this->captura_model->guardaRapidaDB($folioReceta);
        if($resultado == true)
        {
            $this->session->set_flashdata('ok', 'Este folio: <b>'.$folioReceta.'</b> se guardo correctamente.');
            $this->captura_model->cleanProductosRapida();
            echo 1;
        }else{
            echo 0;
        }
    }

    function electronica()
    {
        $data['subtitulo'] = "Receta electronica";
        $data['js'] = "captura/electronica_js";
        $this->load->view('main', $data);
    }

    function electronica_valida()
    {
        $this->load->model('medico_model');
        $folioReceta = $this->input->post('folioReceta');
        $folioReceta = $this->captura_model->cleanFolio($folioReceta);


        $exist = $this->captura_model->existReceta($folioReceta);
        if($exist)
        {
            $this->session->set_flashdata('error', 'Este folio: <b>'.$folioReceta.'</b> ya existe, no se puede duplicar.');
            redirect('captura/electronica');
            
        }

        $activa = $this->captura_model->recetaActiva($folioReceta);
        if($activa == FALSE)
        {
            $this->session->set_flashdata('error', 'Este folio: <b>'.$folioReceta.'</b> no esta activo, el medico la cancelo.');
            redirect('captura/electronica');
            
        }


        $data['query'] = $this->medico_model->getReceta($folioReceta);
        $data['detalle'] = $this->medico_model->getRecetaDetalle($folioReceta);
        
        $data['categoria'] = $this->captura_model->getCveServicioCombo();
        $data['requerimiento'] = $this->captura_model->getRequerimientoCombo();
        $data['tipoReceta'] = $this->captura_model->getProgramaCombo();
        $data['sexo'] = $this->captura_model->getSexoCombo();

        $data['subtitulo'] = "Receta electronica";
        $data['js'] = "captura/electronica_valida_js";
        $data['config'] = $this->captura_model->getConfig();
        $data['folioReceta'] = $folioReceta;
        $this->load->view('main', $data);
        
    }

    function recetas__electronica_agregar()
    {
        $this->db->trans_start();

        $recetaID = $this->input->post('recetaID');
        $this->load->model('medico_model');
        $consecutivo = 0;

        $query = $this->medico_model->getReceta($recetaID);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            if($row->statusReceta == 1 && $row->surtida == 0)
            {

                $data = array(
                    'clvsucursal' => $this->session->userdata('clvsucursal'),
                    'cvemedico' => $row->cvemedico, 
                    'cveservicio' => $row->cveservicios, 
                    'cvepaciente' => $row->cvepaciente, 
                    'fecha' => date('Y-m-d'),
                    'nombre' => $row->nombre, 
                    'apaterno' => $row->apaterno, 
                    'genero' => $row->genero, 
                    'edad' => $row->edad, 
                    'amaterno' => $row->amaterno, 
                    'nombremedico' => $row->nombremedico,
                    'tiporequerimiento' => $row->tiporequerimiento, 
                    'folioreceta' => $row->recetaID, 
                    'fechaexp' => substr($row->fecha, 0, 10), 
                    'idprograma' => $row->idprograma,
                    'electronica'   => 1,
                    'usuario' => $this->session->userdata('usuario'),
                    'cie103' => $row->cie103,
                    'cie104' => $row->cie104
                );
                
                $this->db->set('alta', 'now()', false);
                $this->db->insert('receta', $data);

                //echo "<pre>";
                //print_r($data);
                //echo "</pre>";
                
                $consecutivo = $this->db->insert_id();
                //$consecutivo = 1;

                if($consecutivo > 0)
                {
                    $detalle = $this->medico_model->getRecetaDetalle($row->recetaID);

                    foreach($detalle->result() as $det)
                    {
                        $lote = $this->input->post('lote_'.$det->detalleID);
                        $sur = $this->input->post('sur_'.$det->detalleID);

                        if($sur > 0)
                        {

                            $inv = $this->captura_model->getInventarioByIDAndClvsucursalAndLote($det->id, $lote);


                            if($inv->num_rows() > 0)
                            {
                                $in = $inv->row();

                                //echo "Hay inventario<br />";
                                //echo $in->cantidad . "<br />";

                                $dataDetalle = array(
                                    'consecutivo'   => $consecutivo,
                                    'id'            => $in->id,
                                    'lote'          => $in->lote,
                                    'caducidad'     => $in->caducidad,
                                    'canreq'        => $det->req,
                                    'cansur'        => $sur,
                                    'descontada'    => 1,
                                    'precio'        => $in->precioven,
                                    'ubicacion'     => $in->ubicacion,
                                    'marca'         => $in->marca,
                                    'comercial'     => $in->comercial,
                                    'costo'         => $in->costo,
                                    'servicio'      => $in->servicio,
                                    'iva'           => $in->tipoprod
                                );

                                //echo "<pre>";
                                //print_r($dataDetalle);
                                //echo "</pre>";

                                $this->db->set('altaDetalle', 'now()', false);
                                $this->db->insert('receta_detalle', $dataDetalle);

                                $dataInv = array(
                                    'cantidad'          => ($in->cantidad - $sur),
                                    'tipoMovimiento'    => 2,
                                    'subtipoMovimiento' => 10,
                                    'receta'            => $consecutivo,
                                    'usuario'           => $this->session->userdata('usuario'),
                                    'movimientoID'      => 0
                                );

                                //echo "<pre>";
                                //print_r($dataInv);
                                //echo "</pre>";
                                $this->db->set('ultimo_movimiento', 'now()', false);
                                $this->db->update('inventario', $dataInv, array('inventarioID' => $in->inventarioID));

                            }else
                            {
                                //echo "no hay invetario<br />";

                                $art = $this->captura_model->getArticuloByID($det->id);

                                if($art->num_rows() > 0)
                                {

                                    $a = $art->row();

                                    $dataDetalle = array(
                                        'consecutivo'   => $consecutivo,
                                        'id'            => $a->id,
                                        'lote'          => 'SL',
                                        'caducidad'     => '9999-12-31',
                                        'canreq'        => $det->req,
                                        'cansur'        => $sur,
                                        'descontada'    => 1,
                                        'precio'        => $a->precioven,
                                        'ubicacion'     => $this->captura_model->getUbicacionLimit(),
                                        'marca'         => '',
                                        'comercial'     => '',
                                        'costo'         => $a->ultimo_costo
                                    );

                                    //echo "<pre>";
                                    //print_r($dataDetalle);
                                    //echo "</pre>";

                                    $this->db->set('ultimo_movimiento', 'now()', false);
                                    $this->db->insert('receta_detalle', $dataDetalle);

                                    $dataInv = array(
                                        'id'                => $a->id,
                                        'lote'              => 'SL',
                                        'caducidad'         => '9999-12-31',
                                        'cantidad'          => (0 - $sur),
                                        'tipoMovimiento'    => 2,
                                        'subtipoMovimiento' => 10,
                                        'receta'            => $consecutivo,
                                        'usuario'           => $this->session->userdata('usuario'),
                                        'movimientoID'      => 0,
                                        'ean'               => 0,
                                        'marca'             => '',
                                        'costo'             => $a->ultimo_costo,
                                        'clvsucursal'       => $this->session->userdata('clvsucursal'),
                                        'ubicacion'         => $this->captura_model->getUbicacionLimit(),
                                        'comercial'         => ''    
                                    );

                                    //echo "<pre>";
                                    //print_r($dataInv);
                                    //echo "</pre>";

                                    $this->db->set('ultimo_movimiento', 'now()', false);
                                    $this->db->insert('inventario', $dataInv);

                                }


                            }
                        }

                    }
                }

            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            redirect('captura/electronica');
        }else
        {
            $this->db->update('receta_electronica_control', array('surtida' => 1), array('recetaID' => $recetaID));

            redirect('captura/electronica');

        }

    }

    function busca_cie103()
    {
    	$term = $this->input->get_post('term');
    	echo $this->captura_model->getCIE103($term);

    }

    function busca_cie104()
    {
    	$term = $this->input->get_post('term');
    	echo $this->captura_model->getCIE104($term);

    }

    function reporte_por_periodo(){
        $this->load->model('reportes_model');
        $data['subtitulo'] = "";
        $data['js'] = "reportes/recetas_periodo_js";
        $data['programa'] = $this->reportes_model->getProgramasCombo();
        $data['requerimiento'] = $this->reportes_model->getRequerimientoCombo();
        $data['suministro'] = $this->reportes_model->getSuministroCombo();
        $this->load->view('main', $data);
    }

    function recetas_periodo_detalle()
    {
        $this->load->model('reportes_model');
        ini_set("memory_limit","1024M");

        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $idprograma = $this->input->post('idprograma');
        $tiporequerimiento = $this->input->post('tiporequerimiento');
        $cvesuministro = $this->input->post('cvesuministro');

        $data['query'] = $this->captura_model->recetas_periodo_detalle($fecha1, $fecha2, $idprograma, $tiporequerimiento, $cvesuministro);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['subtitulo'] = "Periodo " .$fecha1 . " al " . $fecha2;
        //$data['js'] = "reportes/recetas_periodo_detalle_js";
        $this->load->view('main', $data);
    }

}
