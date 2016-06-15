<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Workspace extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }

    }

    public function index()
    {
        $this->load->model('Catalogosweb_model');
        $data['query'] = $this->util->actualizacion();
        $data['query2'] = $this->Catalogosweb_model->getDomicilio();
        $data['query3'] = $this->util->getRecetasIncorrectas();
        $data['fhmysql'] = $this->util->getFHMysql();
        $this->load->view('main', $data);
    }
    
    function prueba()
    {
        $this->util->getMenuByUsuario();
    }

    function pruebamatch()
    {
    	$string = '01234585A';
    	if (preg_match('/[A-Z]/',$string))
    	{
    		echo "HAY COINCIDENCIA";
    	}else 
		{
			echo "NO HAY COINCIDENCIA";
		}
    }
}
