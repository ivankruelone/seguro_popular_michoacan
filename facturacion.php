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
        $data['js'] = "facturacion/listado_remisiones_js";
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

    function eliminar_remision($remision, $clvsucursal)
    {
        $this->facturacion_model->cancelaRemision($remision);
        redirect('facturacion/listado_remisiones/'.$clvsucursal);
    }

    function remisiones()
    {
        $data['subtitulo'] = "Ver remisiones generadas";
        $data['js'] = "facturacion/listado_remisiones_js";
        $data['query'] = $this->facturacion_model->getRemisionesAll();
        $this->load->view('main', $data);
    }

    function firmadas()
    {
        $data['subtitulo'] = "Ver remisiones firmadas";
        $data['js'] = "facturacion/listado_remisiones_js";
        $data['query'] = $this->facturacion_model->getRemisionesFirmadasAll();
        $this->load->view('main', $data);
    }

    function valida_firma($remision)
    {
        $data['subtitulo'] = "Validar las firmas";
        $data['js'] = "facturacion/listado_remisiones_js";
        $data['query'] = $this->facturacion_model->getRemisionByRemision($remision);
        $this->load->view('main', $data);
    }

    function guarda_validacion()
    {
        $remision = $this->input->post('remision');
        $observaciones = strtoupper($this->input->post('observaciones'));

        $this->facturacion_model->verificaFirma($remision, $observaciones);
        redirect('facturacion/firmadas');
    }

    function facturar($remision)
    {
        $data['subtitulo'] = "Vista previa de la factura";
        $data['remision'] = $remision;
        $data['js'] = "facturacion/facturar_js";
        $data['clientes'] = $this->facturacion_model->getClientesByRemisionCombo($remision);
        $this->load->view('main', $data);
    }

    function getFacturaVistaPrevia()
    {
    	$contratoID = $this->input->post('contratoID');
    	$remision = $this->input->post('remision');
    	$data['remision'] = $remision;
    	$data['query'] = $this->facturacion_model->getFacturaProductosByRemision($remision, 1);
        $data['referencia'] = $this->facturacion_model->getFacturaReferencia($contratoID, $remision, 1);
    	$this->load->view('facturacion/facturaVistaPrevia', $data);
    }

    function facturar_submit()
    {
    	$remision = $this->input->post('remision');
    	$contratoID = $this->input->post('contratoID');

    	$f1 = $this->facturacion_model->getFacturaRemota($contratoID, $remision, 1);
    	$f2 = $this->facturacion_model->getFacturaRemota($contratoID, $remision, 2);


        if($f1 === TRUE && $f2 === TRUE)
        {
            $this->facturacion_model->setFacturada($remision);
        }
    	redirect('facturacion/firmadas');
    }

    function descargaXML($remision_facturaID)
    {
        $query = $this->facturacion_model->getFactura($remision_facturaID);
        $row = $query->row();
        
        $this->load->helper('download');
        $data = file_get_contents($row->xml); // Read the file's contents
        $name = 'factura_'.$row->numfac.'.xml';
        
        force_download($name, $data); 
    }

    function descargaPDF($remision_facturaID)
    {
        $query = $this->facturacion_model->getFactura($remision_facturaID);
        $row = $query->row();
        
        $this->load->helper('download');
        $data = file_get_contents($row->pdf); // Read the file's contents
        $name = 'factura_'.$row->numfac.'.pdf';
        
        force_download($name, $data); 
    }

    function paquetes()
    {
        $data['subtitulo'] = "Paquetes";
        $data['query'] = $this->facturacion_model->getPaquetes();
        $this->load->view('main', $data);
    }

    function recetas()
    {
        $data['subtitulo'] = "Recetas";
        $data['query'] = $this->facturacion_model->getRecetas();
        $this->load->view('main', $data);
    }

    function facturadas()
    {
        $data['subtitulo'] = "Ver remisiones facturadas";
        $data['js'] = "facturacion/listado_remisiones_js";
        $data['query'] = $this->facturacion_model->getRemisionesFacturadasAll();
        $this->load->view('main', $data);
    }

}