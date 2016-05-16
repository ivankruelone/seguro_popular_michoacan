<?php
require_once('../lib/nusoap.php'); 
 
$server = new nusoap_server;
$server->soap_defencoding = 'UTF-8';
 
$server->configureWSDL('compras', 'urn:compras');
 
$server->wsdl->schemaTargetNamespace = 'urn:compras';

$server->register('pedido',
			array('pedido' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:compras',
			'urn:compras#pedido');

$server->register('catalogo',
			array('catalogo' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:compras',
			'urn:compras#catalogo');

function pedido($pedido){
 
        return get_pedido($pedido);
}

function catalogo($catalogo){
 
        return get_catalogo($catalogo);
}

function get_pedido($pedido)
{
    require_once('../lib/crud_compras.php'); 

    $xmlDoc = simplexml_load_string($pedido);
    
    $a = array((integer)$xmlDoc->attributes()->estado, (integer)$xmlDoc->attributes()->id);
    
    $db = new Database();
    $db->connect();
    $db->insert('pedidos_almacenes_control', $a, 'estado, id_pedido', 'estado = values(estado)');
    $res = $db->getResult();
    
    if($res == 0){
    
        foreach($xmlDoc->producto as $item)
        {
            $b = array((integer)$xmlDoc->attributes()->estado, (integer)$xmlDoc->attributes()->id, (string)$item->attributes()->clave, (integer)$item->attributes()->pedido);
        
            $db = new Database();
            $db->connect();
            $db->insert('pedidos_almacenes_detalle', $b, 'estado, id_pedido, clave, pedido');

        }
        
        return 1;
    }else{
        return 0;
    }

}

function get_catalogo($catalogo)
{
    require_once('../lib/crud_compras.php'); 

    $xmlDoc = simplexml_load_string($catalogo);
    
    $db = new Database();
    $db->connect();
    $db->delete('catalogo_seguros_envio', 'estado = '.(integer)$xmlDoc->attributes()->estado);

        foreach($xmlDoc->producto as $item)
        {
            $b = array((integer)$xmlDoc->attributes()->estado, (string)$item->attributes()->clave, (string)$item->attributes()->descripcion, (string)$item->attributes()->presentacion, (integer)$item->attributes()->inv);
        
            $db = new Database();
            $db->connect();
            $db->insert('catalogo_seguros_envio', $b, 'estado, clave, descripcion, presentacion, inventario');

        }
        
        return 1;

}

$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service(utf8_encode($POST_DATA));                
exit();
?>

