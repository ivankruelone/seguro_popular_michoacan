<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurisdiccion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }

        $this->load->model('movimiento_model');
        $this->load->helper('utilities');

    }

    function index()
    {
        $this->load->library('pagination');
        $data['subtitulo'] = "Paquetes";
        
        $config['base_url'] = site_url('Jurisdiccion/index');
        $config['total_rows'] = $this->movimiento_model->getColectivosCuenta();
        $config['per_page'] = 50;
        $config['uri_segment'] = 3;
        
        $data['query'] = $this->movimiento_model->getColectivos($config['per_page'], $this->uri->rsegment(3));

        $this->pagination->initialize($config); 
        
        $this->load->view('main', $data);
    }

    function nuevo()
    {
        $data['subtitulo'] = "Nuevo Paquete";
        $data['sucursales'] = $this->util->getSucursalesColectivosCombo();
        $data['programa'] = $this->util->getProgramaCombo();
        $this->load->view('main', $data);
    }

    function nuevo_submit()
    {
        $folio = strtoupper($this->input->post('folio'));
        $fecha = $this->input->post('fecha');
        $clvsucursal = $this->input->post('clvsucursal');
        $idprograma = $this->input->post('idprograma');
        $observaciones = strtoupper($this->input->post('observaciones'));
        $usuario = $this->session->userdata('usuario');

        $data = array(
            'folio'         => $folio,
            'fecha'         => $fecha,
            'clvsucursal'   => $clvsucursal,
            'usuario'       => $usuario,
            'idprograma'    => $idprograma,
            'observaciones' => $observaciones
        );

        $colectivoID = $this->movimiento_model->insertColectivo($data);

        redirect('jurisdiccion/captura/'.$colectivoID);
    }

    function edita($colectivoID)
    {
        $data['subtitulo'] = "Nuevo Paquete";
        $data['sucursales'] = $this->util->getSucursalesColectivosCombo();
        $data['programa'] = $this->util->getProgramaCombo();
        $data['query'] = $this->movimiento_model->getColectivoByColectivoID($colectivoID);
        $this->load->view('main', $data);
    }

    function edita_submit()
    {
        $folio = strtoupper($this->input->post('folio'));
        $fecha = $this->input->post('fecha');
        $clvsucursal = $this->input->post('clvsucursal');
        $idprograma = $this->input->post('idprograma');
        $observaciones = strtoupper($this->input->post('observaciones'));
        $usuario = $this->session->userdata('usuario');

        $colectivoID = $this->input->post('colectivoID');

        $data = array(
            'folio'         => $folio,
            'fecha'         => $fecha,
            'clvsucursal'   => $clvsucursal,
            'usuario'       => $usuario,
            'idprograma'    => $idprograma,
            'observaciones' => $observaciones
        );

        $colectivoID = $this->movimiento_model->updateColectivo($data, $colectivoID);

        redirect('jurisdiccion/index');
    }

    function captura($colectivoID)
    {
        $data['subtitulo'] = "Captura de paquetes.";
        $data['query'] = $this->movimiento_model->getColectivoByColectivoID($colectivoID);
        $data['js'] = "jurisdiccion/captura_js";
        $this->load->view('main', $data);
    }

    function captura_submit()
    {
        $cveArticulo = $this->input->post('cvearticulo');
        $colectivoID = $this->input->post('colectivoID');
        $piezas = $this->input->post('piezas');
        
        echo $this->movimiento_model->insertDetalleColectivo($colectivoID, $cveArticulo, $piezas);
    }

    function detalle()
    {
        $colectivoID = $this->input->post('colectivoID');
        $data['query'] = $this->movimiento_model->getDetalleColectivo($colectivoID);
        $data['colectivoID'] = $colectivoID;
        $this->load->view('jurisdiccion/detalle', $data);
    }

    function elimina_detalle($colectivoDetalle)
    {
        $this->movimiento_model->deleteDetalle($colectivoDetalle);
    }

    function cierre($colectivoID)
    {
        $this->movimiento_model->cierraColectivo($colectivoID);
        redirect('jurisdiccion/index');
    }

    function imprime($colectivoID)
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');

        $data['header'] = $this->movimiento_model->headerColectivo($colectivoID);
        //$data['detalle1'] = $this->pedidos_model->pedido_embarque($id);
        $data['detalle'] = $this->movimiento_model->detalleColectivo($colectivoID);/*HOJA DE PEDIDO */
        $data['colectivoID'] = $colectivoID;
      
        $this->load->view('impresiones/colectivo', $data);
    }

    function aprobacion()
    {
        $this->load->library('pagination');
        $data['subtitulo'] = "Paquetes por aprobar";
        
        $config['base_url'] = site_url('Jurisdiccion/aprobacion');
        $config['total_rows'] = $this->movimiento_model->getColectivosAprobarCuenta();
        $config['per_page'] = 50;
        $config['uri_segment'] = 3;
        
        $data['query'] = $this->movimiento_model->getColectivosAprobar($config['per_page'], $this->uri->rsegment(3));

        $this->pagination->initialize($config); 
        $data['js'] = 'jurisdiccion/index_js';
        $this->load->view('main', $data);
    }

    function aprobar($colectivoID)
    {
    	$this->movimiento_model->aprobarPaquete($colectivoID);
    	redirect('jurisdiccion/aprobacion');
    }

    function surtido()
    {
        $this->load->library('pagination');
        $data['subtitulo'] = "Paquetes por surtir";
        
        $config['base_url'] = site_url('Jurisdiccion/surtido');
        $config['total_rows'] = $this->movimiento_model->getColectivosSurtirCuenta();
        $config['per_page'] = 50;
        $config['uri_segment'] = 3;
        
        $data['query'] = $this->movimiento_model->getColectivosSurtir($config['per_page'], $this->uri->rsegment(3));

        $this->pagination->initialize($config); 
        $data['js'] = 'jurisdiccion/index_js';
        $this->load->view('main', $data);
    }

}