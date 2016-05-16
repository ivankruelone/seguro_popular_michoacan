<?php
require_once('../lib/nusoap.php'); 
require_once('../lib/crud.php'); 
 
$server = new nusoap_server;
$server->soap_defencoding = 'UTF-8';
 
$server->configureWSDL('gente', 'urn:gente');
 
$server->wsdl->schemaTargetNamespace = 'urn:gente';
 
$server->register('productos',
			array('mac' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#productos');
 
$server->register('sucursales',
			array('mac' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#sucursales');

$server->register('tiposalida',
			array('mac' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#tiposalida');

$server->register('receta',
			array('receta' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#receta');

$server->register('corte',
			array('corte' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#corte');

$server->register('invini',
			array('invini' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#invini');

$server->register('entrada',
			array('entrada' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#entrada');

$server->register('salida',
			array('salida' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#salida');

$server->register('ajuste',
			array('ajuste' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#ajuste');

$server->register('missing',
			array('sucursal' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:gente',
			'urn:gente#missing');

function productos($mac){
 
        return get_productos($mac);
}

function sucursales($mac){
 
        return get_sucursales();
}

function tiposalida($mac){
 
        return get_tiposalida();
}

function missing($sucursal){
 
        return get_missing($sucursal);
}

function receta($receta){
 
        return get_receta($receta);
}

function corte($corte){
 
        return get_corte($corte);
}

function invini($invini){
 
        return get_invini($invini);
}

function entrada($entrada){
 
        return get_entrada($entrada);
}

function salida($salida){
 
        return get_salida($salida);
}

function ajuste($ajuste){
 
        return get_ajuste($ajuste);
}

function get_productos($fecha)
{
    $db = new Database();
    $db->connect();
    $db->select('catalogo.cat_farmacias_gente', '*', "cambio > '$fecha' or (id = 1 or id = 2)");
    $res = $db->getResult();
    
    //id, ean, descripcion, precio, clave, cambio

    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $root = $xmlDoc->appendChild(
    $xmlDoc->createElement("catalogo"));
    $xmlDoc->formatOutput = true;

    
    foreach($res as $row){

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("producto"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("id"))->appendChild(
        $xmlDoc->createTextNode($row['id']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("clave"))->appendChild(
        $xmlDoc->createTextNode($row['clave']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("ean"))->appendChild(
        $xmlDoc->createTextNode($row['ean']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("descripcion"))->appendChild(
        $xmlDoc->createTextNode($row['descripcion']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("precio"))->appendChild(
        $xmlDoc->createTextNode($row['precio']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("cambio"))->appendChild(
        $xmlDoc->createTextNode($row['cambio']));
    }
    
    return $xmlDoc->saveXML();
}

function get_sucursales()
{
    $db = new Database();
    $db->connect();
    $db->select('catalogo.sucursal', 'suc, trim(nombre) as nombre', '(suc >= 18700 and suc < 19000) or (suc = 18000 or suc = 17000 or suc = 14000)');
    $res = $db->getResult();
    
    //id, ean, descripcion, precio, clave, cambio

    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $root = $xmlDoc->appendChild(
    $xmlDoc->createElement("sucursales"));
    $xmlDoc->formatOutput = true;

    
    foreach($res as $row){

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("sucursal"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("suc"))->appendChild(
        $xmlDoc->createTextNode($row['suc']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("nombre"))->appendChild(
        $xmlDoc->createTextNode($row['nombre']));

    }
    
    return $xmlDoc->saveXML();
}

function get_missing($sucursal)
{
    $sql = "SELECT (t1.id_venta + 1) as inicio,
       (SELECT MIN(t3.id_venta) -1 FROM gente_venta_c t3 WHERE t3.id_venta > t1.id_venta and sucursal = $sucursal) as fin
FROM gente_venta_c t1
WHERE NOT EXISTS (SELECT t2.id_venta FROM gente_venta_c t2 WHERE t2.id_venta = t1.id_venta + 1 and sucursal = $sucursal)
HAVING fin IS NOT NULL";
    $db = new Database();
    $db->connect();
    $db->select_directo($sql);
    $res = $db->getResult();
    
    //id, ean, descripcion, precio, clave, cambio
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?><missing>';


    
    foreach($res as $row){
        $xml .= '<miss inicio="'.$row['inicio'].'" fin="'.$row['fin'].'" />';

    }
    
    $xml .= '</missing>';
    
    return $xml;
}

function get_tiposalida()
{
    $db = new Database();
    $db->connect();
    $db->select('desarrollo.gente_tipo_salida');
    $res = $db->getResult();
    
    //id, ean, descripcion, precio, clave, cambio

    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $root = $xmlDoc->appendChild(
    $xmlDoc->createElement("tipos"));
    $xmlDoc->formatOutput = true;

    
    foreach($res as $row){

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("tipo"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("id"))->appendChild(
        $xmlDoc->createTextNode($row['id']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("tipo"))->appendChild(
        $xmlDoc->createTextNode($row['tipo']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("salida"))->appendChild(
        $xmlDoc->createTextNode($row['salida']));

    }
    
    return $xmlDoc->saveXML();
}

function get_receta($receta)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($receta);
    
    $a = array((integer)$xmlDoc->attributes()->suc, (integer)$xmlDoc->attributes()->id, (string)$xmlDoc->attributes()->folio, (string)$xmlDoc->attributes()->fecha, (integer)$xmlDoc->attributes()->enviada, (integer)$xmlDoc->attributes()->corte, (integer)$xmlDoc->attributes()->usuario_id);
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_venta_c', $a, "sucursal, id_venta, folio, fecha, enviada, corte_id, usuario_id");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->producto as $item)
        {
            $b = array((integer)$xmlDoc->attributes()->id, (integer)$item->id, (integer)$item->producto_id, (double)$item->precio, (integer)$item->piezas, (integer)$res);
        
            $db = new Database();
            $db->connect();
            $db->insert('gente_venta_d', $b, 'id_venta_d, id_venta_c, producto_id, precio, cantidad, c_id');
            $db->update('gente_inventario',array('inv'=>(integer)$item->inv,'ultima'=>date('Y-m-d H:i:s')),array('sucursal = ' . (integer)$xmlDoc->attributes()->suc. ' and producto_id = ' . (integer)$item->producto_id)); 

        }
        
        return $res;
    }else{
        return 0;
    }

}

function get_corte($corte)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($corte);
    
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (double)$xmlDoc->attributes()->fondo,
        (double)$xmlDoc->attributes()->retiro,
        (double)$xmlDoc->attributes()->ventas,
        (double)$xmlDoc->attributes()->total,
        (double)$xmlDoc->attributes()->dinero,
        (double)$xmlDoc->attributes()->faltante,
        (double)$xmlDoc->attributes()->sobrante,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_cortes', $a, "sucursal, corte_id, fondo, retiro, ventas, total, dinero, faltante, sobrante, fecha, usuario_id");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->r as $r)
        {
            $db->update('gente_venta_c',array('corte_id'=>(integer)$xmlDoc->attributes()->id),array('sucursal = ' . (integer)$xmlDoc->attributes()->suc. ' and id_venta = ' . (integer)$r->id)); 
        }
        
        return $res;
    }else{
        return 0;
    }

}

function get_invini($invini)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($invini);
    
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_inv_c', $a, "sucursal, inv_id, fecha, usuario_id");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->d as $d)
        {
         //sucursal, producto_id, inv, id, ultima   
            $b = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$d->id,
                (integer)$d->can,
                date('Y-m-d H:i:s')
                );
         //sucursal, producto_id, inv, c_id, id
            $c = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$d->id,
                (integer)$d->can,
                (integer)$xmlDoc->attributes()->id
                );
            
            $db->insert('gente_inventario', $b, "sucursal, producto_id, inv, ultima", "inv = values(inv), ultima = values(ultima)");  
            $db->insert('gente_inventario_d', $c, "sucursal, producto_id, inv, c_id", "inv = values(inv)");  
            
        }
        
        foreach($xmlDoc->h as $h)
        {
         //sucursal, producto_id, inv, c_id, id
            $d = array(
                (integer)$xmlDoc->attributes()->suc,
                (integer)$h->id,
                (integer)$h->can,
                (integer)$xmlDoc->attributes()->id
                );
            
            $db->insert('gente_inventario_h', $d, "sucursal, producto_id, inv, c_id", "inv = values(inv)");  
            
        }
        
        return $res;
    }else{
        return 0;
    }

}

function get_entrada($entrada)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($entrada);
    
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id,
        (string)$xmlDoc->attributes()->fecha_cierre,
        (string)$xmlDoc->attributes()->folio,
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_entrada_c', $a, "sucursal, entrada_id, fecha, usuario_id, fecha_cierre, folio");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->d as $d)
        {
         //c_id, entrada_c_id, producto_id, can
            $b = array(
                (integer)$res,
                (integer)$d->id,
                (integer)$d->p_id,
                (integer)$d->can
                );
            
            $db->insert('gente_entrada_d', $b, "c_id, entrada_c_id, producto_id, can");  
            $db->update('gente_inventario',array('inv'=>(integer)$d->inv,'ultima'=>date('Y-m-d H:i:s')),array('sucursal = ' . (integer)$xmlDoc->attributes()->suc. ' and producto_id = ' . (integer)$d->p_id)); 
            
        }
        
        return $res;
    }else{
        return 0;
    }

}

function get_salida($salida)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($salida);
    
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id,
        (integer)$xmlDoc->attributes()->tipo,
        (string)$xmlDoc->attributes()->folio,
        (integer)$xmlDoc->attributes()->destino,
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_salida_c', $a, "sucursal, salida_id, fecha, usuario_id, tipo, folio, destino");  
    $res = $db->getResult();  
    
    if($res > 0){
    
        foreach($xmlDoc->d as $d)
        {
         //c_id, entrada_c_id, producto_id, can
            $b = array(
                (integer)$res,
                (integer)$d->id,
                (integer)$d->p_id,
                (integer)$d->can
                );
            
            $db->insert('gente_salida_d', $b, "c_id, salida_c_id, producto_id, can");  
            $db->update('gente_inventario',array('inv'=>(integer)$d->inv,'ultima'=>date('Y-m-d H:i:s')),array('sucursal = ' . (integer)$xmlDoc->attributes()->suc. ' and producto_id = ' . (integer)$d->p_id)); 
            
        }
        
        return $res;
    }else{
        return 0;
    }

}

function get_ajuste($ajuste)
{
    require_once('../lib/crud.php'); 

    $xmlDoc = simplexml_load_string($ajuste);
    
    $a = array(
        (integer)$xmlDoc->attributes()->suc,
        (integer)$xmlDoc->attributes()->id,
        (string)$xmlDoc->attributes()->fecha,
        (integer)$xmlDoc->attributes()->usuario_id,
        (integer)$xmlDoc->attributes()->vieja,
        (integer)$xmlDoc->attributes()->nueva,
        (integer)$xmlDoc->attributes()->producto_id,
        );
    
    $db = new Database();
    $db->connect();
    $db->insert('gente_ajuste', $a, "sucursal, id_ajuste, fecha, usuario_id, vieja, nueva, producto_id");  
    $res = $db->getResult();  
    
    if($res > 0){
        $db->update('gente_inventario',array('inv'=>(integer)$xmlDoc->attributes()->nueva,'ultima'=>date('Y-m-d H:i:s')),array('sucursal = ' . (integer)$xmlDoc->attributes()->suc. ' and producto_id = ' . (integer)$xmlDoc->attributes()->producto_id)); 
        return $res;
    }else{
        return 0;
    }

}

$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service(utf8_encode($POST_DATA));                
exit();
?>