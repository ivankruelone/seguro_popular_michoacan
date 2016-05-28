<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Compra extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }

        $this->load->model('inventario_model');
        $this->load->helper('utilities');

    }

    function prueba()
    {   $data['query'] = $this->inventario_model->getInventario();
        $data['subtitulo'] = "Define los pasillos de tus areas";
        $this->load->view('main', $data);
    }

}