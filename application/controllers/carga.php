<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Carga extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!Current_User::user()) {
            redirect('welcome');
        }
        
        $this->load->model('captura_model');
        $this->load->helper('utilities');

    }

    function recetas()
    {
        $data['subtitulo'] = "Subida de archivos";
        $data['query'] = $this->captura_model->getSubidas();
        $data['js'] = "carga/carga_recetas_js";
        $this->load->view('main', $data);
    }

    function getFileContent($path)
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");
        $this->load->helper('file');
        $string = read_file($path);

        $arreglo = json_decode($string);
        
        $subida = $this->captura_model->getSubida();
        
        foreach($arreglo->Table1 as $r)
        {
            $data = array(
                'suc' => $r->suc,
                'receta' => $r->receta,
                'detalle' => $r->detalle,
                'folio' => $r->folio,
                'fecha' => $r->fecha,
                'fechasurtido' => $r->fechaSurtido,
                'cvepaciente' => $r->cvePaciente,
                'nombre' => ($r->nombre),
                'paterno' => ($r->paterno),
                'materno' => ($r->materno),
                'edad' => $r->edad,
                'sexo' => $r->sexo,
                'cvemedico' => $r->cveMedico,
                'nombremedico' => ($r->nombreMedico),
                'programa' => $r->programa,
                'requerimiento' => $r->requerimiento,
                'clave' => $r->clave,
                'req' => $r->req,
                'sur' => $r->sur,
                'precio' => $r->precio,
                'subida' => $subida
                );
                
            $this->db->insert('temporal_receta', $data);
            
        }

    }
    
    function recetas_submit()
    {
        $target_dir = "uploads/";
        $target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"]);
        $uploadOk = 1;
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
                //echo "The file ". basename( $_FILES["uploadFile"]["name"]). " has been uploaded.";
                $this->getFileContent($target_dir);
                
            } else {
                //echo "Sorry, there was an error uploading your file.";
            }
        }
        
        redirect('carga/recetas');

    }

    function subida_detalle($subida)
    {
        $data['subtitulo'] = "Detalle de subida de archivo";
        $data['query'] = $this->captura_model->getSubidaBySubida($subida);
        $this->load->view('main', $data);
    }

    function subida_cargar($subida)
    {
    	$this->captura_model->cargaSubidaRecetas($subida);
        $this->captura_model->descuentaInventario($subida);
        redirect('carga/recetas');
    }

}