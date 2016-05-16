<?php
require_once('../lib/nusoap.php'); 
require_once('../lib/crud.php'); 
 
$server = new nusoap_server;
 
$server->configureWSDL('ticket', 'urn:ticket');
 
$server->wsdl->schemaTargetNamespace = 'urn:ticket';
 
$server->register('busca',
			array('suc' => 'xsd:string', 'ticket' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:ticket',
			'urn:ticket#busca');
 
function busca($suc, $ticket){
 
        return encuentra($suc, $ticket);
}

function encuentra($suc, $ticket)
{
    $a = substr($ticket, 0, 1);
    
    if($a == 'V')
    {
        $where = "suc = ".(integer)$suc." and tiket = '".(string)$ticket."'";
    }else{
        $where = "suc = ".(integer)$suc." and tiket = ".(string)$ticket;
    }
    
    $db = new Database();
    $db->connect();
    $db->select('vtadc.venta_detalle', "codigo, descri, can, vta, des, importe, iva", $where);
    $res = $db->getResult();
    
    $db->select('vtadc.venta_detalle12', "codigo, descri, can, vta, des, importe, iva", $where);
    $res1 = $db->getResult();

    if(count($res) > 0){

        $json = json_encode($res);
        return $json;

    }elseif(count($res1) > 0){

        $json = json_encode($res1);
        return $json;

    }else{
        
        return "No hay resultados";
        
    }
    

}
 
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service($POST_DATA);                
exit();
?>