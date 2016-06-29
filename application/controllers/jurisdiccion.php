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
        
        $config['base_url'] = site_url('jurisdiccion/index');
        $config['total_rows'] = $this->movimiento_model->getColectivosCuenta();
        $config['per_page'] = 100;
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
        $cvemedico = strtoupper($this->input->post('cvemedico'));
        $nombremedico = strtoupper($this->input->post('nombremedico'));
        $usuario = $this->session->userdata('usuario');

        $data = array(
            'folio'         => $folio,
            'fecha'         => $fecha,
            'clvsucursal'   => $clvsucursal,
            'usuario'       => $usuario,
            'idprograma'    => $idprograma,
            'observaciones' => $observaciones,
            'cvemedico'     => $cvemedico,
            'nombremedico'  => $nombremedico
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
        $cvemedico = strtoupper($this->input->post('cvemedico'));
        $nombremedico = strtoupper($this->input->post('nombremedico'));

        $colectivoID = $this->input->post('colectivoID');

        $data = array(
            'folio'         => $folio,
            'fecha'         => $fecha,
            'clvsucursal'   => $clvsucursal,
            'usuario'       => $usuario,
            'idprograma'    => $idprograma,
            'observaciones' => $observaciones,
            'cvemedico'     => $cvemedico,
            'nombremedico'  => $nombremedico
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
        
        $config['base_url'] = site_url('jurisdiccion/aprobacion');
        $config['total_rows'] = $this->movimiento_model->getColectivosAprobarCuenta();
        $config['per_page'] = 100;
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
        
        $config['base_url'] = site_url('jurisdiccion/surtido');
        $config['total_rows'] = $this->movimiento_model->getColectivosSurtirCuenta();
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        
        $data['query'] = $this->movimiento_model->getColectivosSurtir($config['per_page'], $this->uri->rsegment(3));

        $this->pagination->initialize($config); 
        $data['js'] = 'jurisdiccion/index_js';
        $this->load->view('main', $data);
    }

    function imagen($colectivoID)
    {
        $data['subtitulo'] = "Sube una imagen";
        $data['colectivoID'] = $colectivoID;
        $data['query'] = $this->movimiento_model->getColectivoImagen($colectivoID);
        $data['colectivo'] = $this->movimiento_model->getColectivoByColectivoID($colectivoID);
        $data['js'] = 'jurisdiccion/imagen_js';
        $this->load->view('main', $data);
    }

    function imagen_submit()
    {
    	$this->load->helper('string');
    	$colectivoID = $this->input->post('colectivoID');
        $target_dir = "uploads/colectivo/";
        $temp = explode(".", $_FILES["uploadFile"]["name"]);
		$newfilename = random_string('unique') . '.' . end($temp);
        $target_dir = $target_dir . $newfilename;
        $uploadOk = 1;
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
                //echo "The file ". basename( $_FILES["uploadFile"]["name"]). " has been uploaded.";
                $this->movimiento_model->uploadColectivo($colectivoID, $target_dir);
            } else {
                //echo "Sorry, there was an error uploading your file.";
            }
        }
        
        redirect('jurisdiccion/imagen/' . $colectivoID);
    }

    function eliminar_imagen($colectivo_imagenID, $colectivoID)
    {
        $query = $this->movimiento_model->getColectivoImagenByColectivo_imagenID($colectivo_imagenID);
        $row = $query->row();
        unlink($row->rutaImagen);
        $this->movimiento_model->deleteColectivoImagen($colectivo_imagenID);
        redirect('jurisdiccion/imagen/' . $colectivoID);
    }

    function rechazar($colectivoID)
    {
        $this->movimiento_model->rechazarColectivo($colectivoID);
        redirect('jurisdiccion/aprobacion');
    }

    function reporte()
    {
        $data['subtitulo'] = "Paquetes surtidos por el almacen.";
        $data['query'] = $this->movimiento_model->getReportePaquetesEntregadoConcentrado();
        $this->load->view('main', $data);
    }

    function reporte_detalle($movimientoID)
    {
        $data['subtitulo'] = "Detalle de paquete surtido por el almacen.";
        $data['query'] = $this->movimiento_model->getReportePaqueteEntregadoDetalle($movimientoID);
        $data['query2'] = $this->movimiento_model->getReportePaquetesEntregadoConcentradoByMovimientoID($movimientoID);
        $this->load->view('main', $data);
    }

    function reporteExcel()
    {
        $this->movimiento_model->getReporteColectivosExcel();
        $filename = 'Reporte_de_colectivos_'.date('Ymd_his').'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    function firma()
    {
        $this->load->library('pagination');
        $data['subtitulo'] = "Paquetes en espera de firma";
        
        $config['base_url'] = site_url('jurisdiccion/surtido');
        $config['total_rows'] = $this->movimiento_model->getColectivosFirmaCuenta();
        $config['per_page'] = 100;
        $config['uri_segment'] = 3;
        
        $data['query'] = $this->movimiento_model->getColectivosFirma($config['per_page'], $this->uri->rsegment(3));

        $this->pagination->initialize($config); 
        $data['js'] = 'jurisdiccion/index_js';
        $this->load->view('main', $data);
    }

}