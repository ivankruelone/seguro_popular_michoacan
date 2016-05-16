<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Facturacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('facturacion_model');
        $this->load->helper('utilities');

    }

    function generar_remision()
    {
        $data['subtitulo'] = "Generar una remisión";
        $data['js'] = "facturacion/generar_remision_js";
        $data['sucursal'] = $this->util->getSucursalCombo();
        $this->load->view('main', $data);
    }

    function posibles_remisiones()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $clvsucursal = $this->input->post('clvsucursal');

        $data['subtitulo'] = "Generar una remisión";
        $data['js'] = "facturacion/generar_remision_js";
        $data['query'] = $this->facturacion_model->getPosiblesRemisiones($fecha1, $fecha2, $clvsucursal);
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $this->load->view('main', $data);
    }

    function remisionar($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma)
    {
        $validaPrecio = $this->facturacion_model->validaRemisionPrevia($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma);

        if($validaPrecio == 0)
        {
            $this->facturacion_model->generaRemision($perini, $perfin, $clvsucursal, $iva, $tiporequerimiento, $idprograma);
            redirect('facturacion/listado_remisiones/' . $clvsucursal);
        }else
        {
            redirect('facturacion/listado_remisiones/' . $clvsucursal);
        }


    }

    function ver_remisiones()
    {
        $data['subtitulo'] = "Ver remisiones generadas";
        //$data['js'] = "catalogosweb/productos_por_secuencia_js";
        $data['sucursal'] = $this->util->getSucursalCombo();
        $this->load->view('main', $data);
    }

    function listado_remisiones($clvsucursal = null)
    {
        if($clvsucursal == null)
        {
            $clvsucursal = $this->input->post('clvsucursal');
        }

        $data['subtitulo'] = "Ver remisiones generadas";
        //$data['js'] = "catalogosweb/productos_por_secuencia_js";
        $data['query'] = $this->facturacion_model->getListadoRemisiones($clvsucursal);
        $this->load->view('main', $data);
    }

    function imprimirRemision($remision, $clvsucursal)
    {
        ini_set("memory_limit","1024M");
        $data['cabeza'] = $this->facturacion_model->getRemisionCabeza($remision);
        $data['query'] = $this->facturacion_model->getRemisionDetalle($remision);
        $data['ext'] = $this->facturacion_model->getSucursalesExt($clvsucursal);
        $this->load->view('impresiones/remision', $data);
    }

    function panorama()
    {
        $data['subtitulo'] = "Panorama";
        $data['query'] = $this->facturacion_model->getPanorama();
        //$data['js'] = "catalogosweb/productos_por_secuencia_js";
        $this->load->view('main', $data);
    }
}