<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cliente extends CI_Controller
{
    var $valuacion = 0;

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('reportes_model');
        $this->load->helper('utilities');
        $this->valuacion = $this->session->userdata('valuacion');
        date_default_timezone_set('America/Mexico_City');

    }

    function inventario()
    {
        $data['subtitulo'] = "Inventario de las sucursales";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInventarioGroupBySucursal();
        $this->load->view('main', $data);
    }

    function inventario_detalle($clvsucursal)
    {
        $data['subtitulo'] = "Inventario a detalle.";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInvetarioDetalleBySucursal($clvsucursal);
        $this->load->view('main', $data);
    }

    function inventario_total()
    {
        $data['subtitulo'] = "Inventario total por clave";
        $data['valuacion'] = $this->valuacion;
        $data['query'] = $this->reportes_model->getInventarioTotalByClave();
        $this->load->view('main', $data);
    }

}
    