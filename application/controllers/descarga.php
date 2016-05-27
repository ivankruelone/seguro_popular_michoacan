<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Descarga extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('Catalogosweb_model');
        $this->load->helper('utilities');
        $this->load->helper('download');

    }

    public function descargas()
    {
        
        $data['subtitulo'] = "Descargas";
        $this->load->view('main', $data);
    }

    public function getArticuloFile()
    {
        ini_set("memory_limit","1024M");
        $data = $this->Catalogosweb_model->getArticuloForStandAlone();
        $name = 'dataArticulo1erNivel.articulo';
        
        force_download($name, $data); 
    }

    public function getArticuloFile2()
    {
        ini_set("memory_limit","1024M");
        $data = $this->Catalogosweb_model->getArticuloForStandAlone2();
        $name = 'dataArticulo2doNivel.articulo';
        
        force_download($name, $data); 
    }

    public function getMedicoFile()
    {
        $data = $this->Catalogosweb_model->getMedicoForStandAlone();
        $name = 'dataMedico.medico';
        
        force_download($name, $data); 
    }

    public function getSucursalFile()
    {
        $data = $this->Catalogosweb_model->getSucursalForStandAlone();
        $name = 'dataSucursal.sucursal';
        
        force_download($name, $data); 
    }

    public function getUsuarioFile()
    {
        $data = $this->Catalogosweb_model->getUsuarioForStandAlone();
        $name = 'dataUsuario.usuario';
        
        force_download($name, $data); 
    }

}
