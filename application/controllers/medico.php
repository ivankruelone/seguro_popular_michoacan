<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medico extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('medico_model');
        $this->load->model('captura_model');
        $this->load->helper('utilities');
        date_default_timezone_set('America/Mexico_City');

    }

    function config()
    {
        $data['subtitulo'] = "Configuracion";
        $data['categoria'] = $this->captura_model->getCveServicioCombo();
        $data['query'] = $this->medico_model->getConfig();
        $this->load->view('main', $data);
    }

    function nueva__configuracion()
    {
        $cvemedico = $this->input->post('cvemedico');
        $nombremedico = $this->input->post('nombremedico');
        $cveservicios = $this->input->post('cveservicios');

        $this->medico_model->addConfig($cvemedico, $nombremedico, $cveservicios);
        redirect('medico/config');
    }

    function nueva()
    {
    	$query = $this->medico_model->getConfig();
    	if($query->num_rows() == 0)
    	{
    		redirect('medico/config');
    	}
        $data['subtitulo'] = "Nueva Receta";
        $data['js'] = "medico/nueva_js";
        $data['sexo'] = $this->captura_model->getSexoCombo();
        $data['programa'] = $this->captura_model->getProgramaCombo();
        $this->load->view('main', $data);
    }

    function nueva__receta()
    {
        $recetaID = $this->medico_model->save();
        redirect('medico/recetas');
    }

    function recetas()
    {
        $data['subtitulo'] = "Recetas expedidas";
        $data['js'] = "medico/recetas_js";
        $data['query'] = $this->medico_model->getRecetas();
        $this->load->view('main', $data);
    }

    function imprimeReceta($recetaID)
    {
        set_time_limit(0);
        ini_set('memory_limit','-1');

        $data['header'] = $this->medico_model->headerRecetaElectronica($recetaID);
        $data['detalle'] = $this->medico_model->detalleRecetaElectronica($recetaID);
        $data['fin'] = $this->medico_model->finRecetaElectronica($recetaID);
        $data['recetaID'] = $recetaID;

        $this->load->view('impresiones/recetaElectronica', $data);

    }

    function cancelaReceta($recetaID)
    {
    	$this->medico_model->cancela($recetaID);
    	redirect('medico/recetas');
    }

    function verificaCveArticulo()
    {
        $cveArticulo = $this->input->post('cveArticulo');
        $idprograma = $this->input->post('idprograma');

        echo $this->medico_model->validaArticulo($cveArticulo, $idprograma);
    }

}