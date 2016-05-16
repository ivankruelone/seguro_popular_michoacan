<?php
require_once('../lib/nusoap.php'); 
 
$server = new nusoap_server;
$server->soap_defencoding = 'UTF-8';
 
$server->configureWSDL('pdv', 'urn:pdv');
 
$server->wsdl->schemaTargetNamespace = 'urn:pdv';
 
$server->register('getEmpleado',
			array('caja' => 'xsd:string', 'nomina' => 'xsd:integer'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getEmpleado');

$server->register('getClienteFidelizacion',
			array('tipo' => 'xsd:integer', 'tarjeta' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getClienteFidelizacion');

$server->register('getTCP',
			array('caja' => 'xsd:string', 'tarjeta' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getTCP');

$server->register('getCatalogo',
			array('caja' => 'xsd:string', 'tipo' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getCatalogo');

$server->register('getMissing',
			array('sucursal' => 'xsd:integer'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getMissing');

$server->register('getMissing2',
			array('sucursal' => 'xsd:integer', 'cuenta' => 'xsd:integer'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#getMissing2');

$server->register('putVenta',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putVenta');

$server->register('putNegado',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putNegado');

$server->register('putInventario',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putInventario');

$server->register('putPedido',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putPedido');

$server->register('putEntrada',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putEntrada');

$server->register('putTarjeta',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putTarjeta');

$server->register('putAjuste',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putAjuste');

$server->register('putDevolucionCliente',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putDevolucionCliente');

$server->register('putTraspasoSalida',
			array('xml' => 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putTraspasoSalida');

$server->register('putAsistencia',
			array('cia' => 'cia', 'sucursal' => 'xsd:integer', 'numeroempleado' => 'xsd:integer', 'tipo' => 'xsd:integer'),
			array('return' => 'xsd:string'),
			'urn:pdv',
			'urn:pdv#putAsistencia');

function getEmpleado($caja, $nomina){
 
        return _getEmpleado($nomina);
}

function getClienteFidelizacion($tipo, $tarjeta){
 
        return _getClienteFidelizacion($tipo, $tarjeta);
}

function getTCP($caja, $tarjeta){
 
        return _getTCP($tarjeta);
}

function getCatalogo($caja, $tipo){
 
        return _getCatalogo($tipo);
}

function getMissing($sucursal){
    
    return _getMissing($sucursal);
}

function getMissing2($sucursal, $cuenta){
    
    return _getMissing2($sucursal, $cuenta);
}

function putVenta($xml){
 
        return _putVenta($xml);
}

function putNegado($xml){
 
        return _putNegado($xml);
}

function putInventario($xml){
 
        return _putInventario($xml);
}

function putPedido($xml){
 
        return _putPedido($xml);
}

function putEntrada($xml){
 
        return _putEntrada($xml);
}

function putTarjeta($xml){
 
        return _putTarjeta($xml);
}

function putAjuste($xml){
 
        return _putAjuste($xml);
}

function putDevolucionCliente($xml){
 
        return _putDevolucionCliente($xml);
}

function putTraspasoSalida($xml){
 
        return _putTraspasoSalida($xml);
}

function putAsistencia($cia, $sucursal, $numeroempleado, $tipo){
 
        return _putAsistencia($cia, $sucursal, $numeroempleado, $tipo);
}

function _getEmpleado($nomina)
{
    require_once('../lib/crud.php'); 
    $db = new Database();
    $db->connect();
    $db->select('catalogo.cat_empleado', 'tipo, completo', "nomina = '$nomina' and tipo = 1");
    $res = $db->getResult();
    
    if(count($res) == 0){
        return "NO EXISTE";
    }else{
        if($res['tipo'] == 2){
            return "BAJA";
        }else{
            return trim($res['completo']);
        }
    }
    
    
}

function _getClienteFidelizacion($tipo, $tarjeta)
{
    require_once ('../lib/crud_pdvoffice.php');
    $db = new Database();
    $db->connect();
    $db->select('fidelizacion_tarjetas', '*', "tarjeta = '" . $tarjeta . "' and fidelizacion_tipo = " . $tipo);
    $res = $db->getResult();
    
    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $root = $xmlDoc->appendChild(
    $xmlDoc->createElement("clientes"));
    $xmlDoc->formatOutput = true;

    
    if(count($res) > 0){

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("cliente"));
        
        //tarjeta, fidelizacion_tipo, nombre_afiliado, apellido_paterno, apellido_materno, 
        //direccion, telefono, activacion, vencimiento, id_tarjeta, email

        $tutTag->appendChild(
        $xmlDoc->createAttribute("encontrado"))->appendChild(
        $xmlDoc->createTextNode('si'));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("tarjeta"))->appendChild(
        $xmlDoc->createTextNode($res['tarjeta']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("fidelizacion_tipo"))->appendChild(
        $xmlDoc->createTextNode($res['fidelizacion_tipo']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("nombre_afiliado"))->appendChild(
        $xmlDoc->createTextNode($res['nombre_afiliado']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("apellido_paterno"))->appendChild(
        $xmlDoc->createTextNode($res['apellido_paterno']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("apellido_materno"))->appendChild(
        $xmlDoc->createTextNode($res['apellido_materno']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("direccion"))->appendChild(
        $xmlDoc->createTextNode($res['direccion']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("telefono"))->appendChild(
        $xmlDoc->createTextNode($res['telefono']));
        
        $tutTag->appendChild(
        $xmlDoc->createAttribute("activacion"))->appendChild(
        $xmlDoc->createTextNode($res['activacion']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("vencimiento"))->appendChild(
        $xmlDoc->createTextNode($res['vencimiento']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("id_tarjeta"))->appendChild(
        $xmlDoc->createTextNode($res['id_tarjeta']));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("email"))->appendChild(
        $xmlDoc->createTextNode($res['email']));

        return $xmlDoc->saveXML();
        
    }else{

        $tutTag = $root->appendChild(
        $xmlDoc->createElement("cliente"));

        $tutTag->appendChild(
        $xmlDoc->createAttribute("encontrado"))->appendChild(
        $xmlDoc->createTextNode('no'));

        return $xmlDoc->saveXML();
        
    }

}


function _getTCP($tarjeta)
{
    require_once('../lib/crud.php'); 
    $db = new Database();
    $db->connect();
    $db->select('vtadc.tarjetas', 'nombre, DATEDIFF(vigencia, now()) as vigencia', "codigo = '$tarjeta' and tipo = 1");
    $res = $db->getResult();
    
    if(count($res) == 0){
        return "NO EXISTE";
    }else{
        if($res['vigencia'] <= 0){
            return "VENCIDA";
        }else{
            return trim(utf8_encode($res['nombre']));
        }
    }
    
    
}

function _getCatalogo($tipo)
{
    require_once('../lib/crud.php'); 
    
    switch ($tipo) {
    case "G":
        $catalogo = "catalogo.catalogo_productos_gen";
        break;
    case "D":
        $catalogo = "catalogo.catalogo_productos_ddr";
        break;
    case "F":
        $catalogo = "";
        break;
    }
    
    $db = new Database();
    $db->connect();
    $db->select($catalogo, '*');
    $res = $db->getResult();
    
    return json_encode($res);
    
    
}

function _getMissing($sucursal)
{
    require_once ('../lib/crud_pdvoffice.php');

    $sql = "SELECT (t1.venta + 1) as inicio,
       (SELECT MIN(t3.venta) -1 FROM ventas t3 WHERE t3.venta > t1.venta and t3.sucursal = $sucursal and t1.sucursal = $sucursal) as fin
FROM ventas t1
WHERE NOT EXISTS (SELECT t2.venta FROM ventas t2 WHERE t2.venta = t1.venta + 1 and t2.sucursal = $sucursal and t1.sucursal = $sucursal)
HAVING fin IS NOT NULL
union
select 0, 0";
    
    $sql = "select ids
from referencia
where ids not in(select venta from ventas where sucursal = $sucursal) 
and ids <= (select max(venta) from ventas where sucursal = $sucursal)
union
select 0";

    $db = new Database();
    $db->connect();
    $db->select_directo($sql);
    $res = $db->getResult();
    
    //id, ean, descripcion, precio, clave, cambio
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?><missing>';


    
    foreach($res as $row){
        $xml .= '<miss inicio="'.(string)(integer)$row['ids'].'" fin="'.(string)(integer)$row['ids'].'"/>';
    }
    
    $xml .= '</missing>';
    
    return $xml;
}

function _getMissing2($sucursal, $cuenta)
{
    require_once ('../lib/crud_pdvoffice.php');

    $sql = "SELECT (t1.venta + 1) as inicio,
       (SELECT MIN(t3.venta) -1 FROM ventas t3 WHERE t3.venta > t1.venta and t3.sucursal = $sucursal and t1.sucursal = $sucursal) as fin
FROM ventas t1
WHERE NOT EXISTS (SELECT t2.venta FROM ventas t2 WHERE t2.venta = t1.venta + 1 and t2.sucursal = $sucursal and t1.sucursal = $sucursal)
HAVING fin IS NOT NULL
union
select 0, 0";
    
    $sql = "select ids
from referencia
where ids not in(select venta from ventas where sucursal = $sucursal) 
and ids <= (select max(venta) from ventas where sucursal = $sucursal)
union
select 0";

    $sql_cuenta = "select count(*) as cuenta from ventas where sucursal = $sucursal;";

    $db = new Database();
    $db->connect();
    
    $db->select_directo($sql_cuenta);
    $cuentaBackOffice = $db->getResult();
    
    if($cuenta == $cuentaBackOffice['cuenta'])
    {
        return "COMPLETAS";
    }else{
        
    
        $db->select_directo($sql);
        $res = $db->getResult();
        
        //id, ean, descripcion, precio, clave, cambio
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?><missing>';
    
    
        
        foreach($res as $row){
            $xml .= '<miss inicio="'.(string)(integer)$row['ids'].'" fin="'.(string)(integer)$row['ids'].'"/>';
        }
        
        $xml .= '</missing>';
        
        return $xml;
    }

}

function _putVenta($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $a = array(
        (integer)$xmlDoc->attributes()->venta,
        (integer)$xmlDoc->attributes()->sucursal,
        (string )$xmlDoc->attributes()->cliente,
        (integer)$xmlDoc->attributes()->cajero,
        (string )$xmlDoc->attributes()->fecha,
        (string )$xmlDoc->attributes()->hora,
        (integer)$xmlDoc->attributes()->cliente_tipo,
        (integer)$xmlDoc->attributes()->estatus,
        (string )$xmlDoc->attributes()->cambio,
        ((string )$xmlDoc->attributes()->fecha_cancelacion == null) ?
            "0000-00-00 00:00:00" : (string )$xmlDoc->attributes()->fecha_cancelacion,
        (integer)$xmlDoc->attributes()->cajero_cancelo,
        (string )$xmlDoc->attributes()->caja,
        (integer)$xmlDoc->attributes()->vendedor,
        (integer)$xmlDoc->attributes()->fidelizacion_tipo,
        (integer)$xmlDoc->attributes()->fidelizacion_tarjeta,
        (integer)$xmlDoc->attributes()->credito_nomina,
        (string )$xmlDoc->attributes()->credito_empleado,
        (integer)$xmlDoc->attributes()->corte,
        (integer)$xmlDoc->attributes()->cierre);
    
    $db = new Database();
    $db->connect();
    
    $where = "venta = " . (integer)$xmlDoc->attributes()->venta . " and sucursal = " . (integer)
        $xmlDoc->attributes()->sucursal . " and fecha = '" . (string )$xmlDoc->
        attributes()->fecha . "'";
    
    $id = $db->select('ventas', 'id', $where);
    $id = $db->getResult();
    
    if (count($id) > 0) {
    
        $id = $id['id'];
    
        $db->insert('ventas', $a,
            "venta, sucursal, cliente, cajero, fecha, hora, cliente_tipo, estatus, cambio, fecha_cancelacion, cajero_cancelo, caja, vendedor, fidelizacion_tipo, fidelizacion_tarjeta, credito_nomina, credito_empleado, corte, cierre",
            "cliente=values(cliente), estatus=values(estatus), cambio=values(cambio), fecha_cancelacion=values(fecha_cancelacion), cajero_cancelo=values(cajero_cancelo), corte=values(corte), cierre=values(cierre)");
        $db->delete('detalle', 'id = ' . $id);
    
        foreach ($xmlDoc->detalle as $item) {
    
            $b = array(
                $id,
                (integer)$item->attributes()->venta,
                (integer)$item->attributes()->detalle,
                (string)$item->attributes()->ean,
                (float)$item->attributes()->precio,
                (integer)$item->attributes()->cantidad,
                (float)$item->attributes()->iva,
                (float)$item->attributes()->descuento_tasa,
                (float)$item->attributes()->descuento,
                (float)$item->attributes()->total,
                (float)$item->attributes()->iva_tasa,
                (integer)$item->attributes()->descuento_limitado,
                (integer)$item->attributes()->antibiotico,
                (integer)$item->attributes()->fisico
                );
    
            $db->insert('detalle', $b,
                "id, detalle, venta, ean, precio, cantidad, iva, descuento_tasa, descuento, total, iva_tasa, descuento_limitado, antibiotico, fisico");
        }

        $db->delete('pagos', 'id = ' . $id);
    
        foreach ($xmlDoc->pagos as $item2) {
    
            $c = array(
                $id,
                (integer)$item2->attributes()->pago,
                (integer)$item2->attributes()->venta,
                (string)$item2->attributes()->caja,
                (integer)$item2->attributes()->idformas_pago,
                (float)$item2->attributes()->monto,
                (float)$item2->attributes()->tipo_cambio,
                (float)$item2->attributes()->dolares,
                (float)$item2->attributes()->entregado,
                (integer)$item2->attributes()->corte,
                (integer)$item2->attributes()->cierre
                );
    
            $db->insert('pagos', $c,
                "id, pago, venta, caja, idformas_pago, monto, tipo_cambio, dolares, entregado, corte, cierre");
        }
    
    
    } else {
    
        $db->insert('ventas', $a,
            "venta, sucursal, cliente, cajero, fecha, hora, cliente_tipo, estatus, cambio, fecha_cancelacion, cajero_cancelo, caja, vendedor, fidelizacion_tipo, fidelizacion_tarjeta, credito_nomina, credito_empleado, corte, cierre",
            "cliente=values(cliente), estatus=values(estatus), cambio=values(cambio), fecha_cancelacion=values(fecha_cancelacion), cajero_cancelo=values(cajero_cancelo), corte=values(corte), cierre=values(cierre)");
        $id = $db->getResult();
        foreach ($xmlDoc->detalle as $item) {
    
            $b = array(
                $id,
                (integer)$item->attributes()->venta,
                (integer)$item->attributes()->detalle,
                (string)$item->attributes()->ean,
                (float)$item->attributes()->precio,
                (integer)$item->attributes()->cantidad,
                (float)$item->attributes()->iva,
                (float)$item->attributes()->descuento_tasa,
                (float)$item->attributes()->descuento,
                (float)$item->attributes()->total,
                (float)$item->attributes()->iva_tasa,
                (integer)$item->attributes()->descuento_limitado,
                (integer)$item->attributes()->antibiotico,
                (integer)$item->attributes()->fisico
                );
            
            $inv = array(
                (integer)$xmlDoc->attributes()->sucursal,
                (string)$item->attributes()->ean,
                (integer)$item->attributes()->inventario,
                date('Y-m-d H:i:s')
                );
    
            $db->insert('detalle', $b,
                "id, detalle, venta, ean, precio, cantidad, iva, descuento_tasa, descuento, total, iva_tasa, descuento_limitado, antibiotico, fisico");
                
            $db->insert('inventario', $inv, "sucursal, ean, cantidad, fecha", "cantidad = values(cantidad), fecha = values(fecha)");
        }


        foreach ($xmlDoc->pagos as $item2) {
    
            $c = array(
                $id,
                (integer)$item2->attributes()->pago,
                (integer)$item2->attributes()->venta,
                (string)$item2->attributes()->caja,
                (integer)$item2->attributes()->idformas_pago,
                (float)$item2->attributes()->monto,
                (float)$item2->attributes()->tipo_cambio,
                (float)$item2->attributes()->dolares,
                (float)$item2->attributes()->entregado,
                (integer)$item2->attributes()->corte,
                (integer)$item2->attributes()->cierre
                );
    
            $db->insert('pagos', $c,
                "id, pago, venta, caja, idformas_pago, monto, tipo_cambio, dolares, entregado, corte, cierre");
        }
    }
    
    return $id;
}

function _putNegado($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $a = array(
        (integer)$xmlDoc->attributes()->sucursal,
        (string )$xmlDoc->attributes()->ean,
        (integer)$xmlDoc->attributes()->cantidad
        );
    
    $db = new Database();
    $db->connect();
    
    $db->insert('negados', $a, "sucursal, ean, cantidad");
    $id = $db->getResult();
    
    return $id;
}

function _putInventario($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $sucursal = (integer)$xmlDoc->attributes()->sucursal;
    
    $db = new Database();
    $db->connect();
    
    $db->delete("inventario", "sucursal = " . $sucursal);
    
    $values = null;
    
    foreach ($xmlDoc->p as $p) {
        
        $values .= "(" . $sucursal . ", '" . (string)$p->attributes()->ean . "', " . (integer)$p->attributes()->can . ", now()),";
    
    }
    
    $values = substr($values, 0, -1);
    
    $db->insert_bulk("inventario", $values, "sucursal, ean, cantidad, fecha");
    
    
}

function _putPedido($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $idpedido = (integer)$xmlDoc->attributes()->idpedido;
    $sucursal = (integer)$xmlDoc->attributes()->sucursal;
    $tipo =     (integer)$xmlDoc->attributes()->tipo;
    $fecha = (string)$xmlDoc->attributes()->fecha;
    
    $db = new Database();
    $db->connect();
    
    $values = null;
    
    //sucursal, idpedido, tipo, secuencia, cantidad, fecha, recepcion
    foreach ($xmlDoc->p as $p) {
        
        $values .= "(" . $sucursal . ", " . $idpedido . ", " . $tipo . ", '" . (string)$p->attributes()->sec . "', " . (integer)$p->attributes()->can . ", '" . $fecha . "', now()),";
    
    }
    
    $values = substr($values, 0, -1);
    
    $db->insert_bulk("pedidos", $values, "sucursal, idpedido, tipo, secuencia, cantidad, fecha, recepcion", "cantidad=values(cantidad), fecha=values(fecha), recepcion=values(recepcion)");
    
    
}

function _putEntrada($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $a = array(
        (integer)$xmlDoc->attributes()->sucursal,
        (integer)$xmlDoc->attributes()->entrada,
        (string)$xmlDoc->attributes()->proveedor,
        (string)$xmlDoc->attributes()->fecha,
        (string)$xmlDoc->attributes()->observaciones,
        (integer)$xmlDoc->attributes()->numeroempleado,
        (string)$xmlDoc->attributes()->alta
        );
    
    $db = new Database();
    $db->connect();
    
    $db->insert('entradas_control', $a, "sucursal, entrada, proveedor, fecha, observaciones, numeroempleado, alta", "proveedor = values(proveedor), fecha = values(fecha), observaciones = values(observaciones), numeroempleado = values(numeroempleado), alta = values(alta)");
    $id = $db->getResult();
    
    $values = null;
    
    //sucursal="4" entrada="1" detalle="1" ean="7501349025073" precio="8.3100" cantidad="15"
    foreach ($xmlDoc->e as $e) {
        
        $values .= "(" . (integer)$e->attributes()->sucursal . ", " . (integer)$e->attributes()->entrada . ", " . (integer)$e->attributes()->detalle . ", '" . (string)$e->attributes()->ean . "', " . (double)$e->attributes()->precio . ", " . (integer)$e->attributes()->cantidad . "),";
    
    }
    
    $values = substr($values, 0, -1);
    
    $db->insert_bulk("entradas_detalle", $values, "sucursal, entrada, detalle, ean, precio, cantidad", "ean=values(ean), precio=values(precio), cantidad=values(cantidad)");

    return $id;
    
    
}

function _putTarjeta($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string(($xml));
    //tarjeta, fidelizacion_tipo, nombre_afiliado, apellido_paterno, apellido_materno, 
    //direccion, telefono, activacion, vencimiento, id_tarjeta, email
    
    $a = array(
        (string)$xmlDoc->attributes()->tarjeta,
        (integer)$xmlDoc->attributes()->fidelizacion_tipo,
        utf8_decode((string)$xmlDoc->attributes()->nombre_afiliado),
        utf8_decode((string)$xmlDoc->attributes()->apellido_paterno),
        utf8_decode((string)$xmlDoc->attributes()->apellido_materno),
        utf8_decode((string)$xmlDoc->attributes()->direccion),
        (string)$xmlDoc->attributes()->telefono,
        (string)$xmlDoc->attributes()->activacion,
        (string)$xmlDoc->attributes()->vencimiento,
        utf8_decode((string)$xmlDoc->attributes()->email),
        );
    
    $db = new Database();
    $db->connect();
    
    $db->insert('fidelizacion_tarjetas', $a, "tarjeta, fidelizacion_tipo, nombre_afiliado, apellido_paterno, apellido_materno, direccion, telefono, activacion, vencimiento, email");
    $id = $db->getResult();
    
    return $id;
}

function _putAjuste($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    //sucursal, ean, anterior, nueva, responsable, fecha
    $a = array(
        (integer)$xmlDoc->attributes()->sucursal,
        (string )$xmlDoc->attributes()->ean,
        (integer)$xmlDoc->attributes()->anterior,
        (integer)$xmlDoc->attributes()->nueva,
        (integer)$xmlDoc->attributes()->responsable,
        (string)$xmlDoc->attributes()->fecha,
        );
    
    $db = new Database();
    $db->connect();
    
    $db->insert('ajuste', $a, "sucursal, ean, anterior, nueva, responsable, fecha");
    $id = $db->getResult();
    
    return $id;
}

function _putDevolucionCliente($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    $a = array(
        (integer)$xmlDoc->attributes()->devolucion,
        (integer)$xmlDoc->attributes()->sucursal,
        (integer)$xmlDoc->attributes()->venta,
        (string)$xmlDoc->attributes()->ean,
        (integer)$xmlDoc->attributes()->cantidad,
        (string)$xmlDoc->attributes()->motivo,
        (double)$xmlDoc->attributes()->precio,
        (string)$xmlDoc->attributes()->fecha,
        );
    
    $db = new Database();
    $db->connect();
    //devolucion, sucursal, venta, ean, cantidad, motivo, precio, fecha
    $db->insert('venta_devolucion', $a, "devolucion, sucursal, venta, ean, cantidad, motivo, precio, fecha");
    $id = $db->getResult();
    
    return $id;
}

function _putTraspasoSalida($xml)
{
    require_once ('../lib/crud_pdvoffice.php');
    
    $xmlDoc = simplexml_load_string($xml);
    
    //traspaso, tipo_traspaso, sucursal_origen, sucursal_destino, fecha, observaciones, numeroempleado, referencia, idTraspaso
    
    $date = new DateTime((string )$xmlDoc->attributes()->fecha);
    
    $a = array(
        (integer)$xmlDoc->attributes()->traspaso,
        (integer)$xmlDoc->attributes()->tipo_traspaso,
        (integer)$xmlDoc->attributes()->sucursal_origen,
        (integer)$xmlDoc->attributes()->sucursal_destino,
        $date->format('Y-m-d H:i:s'),
        (string )$xmlDoc->attributes()->observaciones,
        (integer)$xmlDoc->attributes()->numeroempleado,
        (integer)$xmlDoc->attributes()->referencia,
        );
    
    $db = new Database();
    $db->connect();
    
    $where = "traspaso = " . (integer)$xmlDoc->attributes()->traspaso . " and tipo_traspaso = " . (integer)
        $xmlDoc->attributes()->tipo_traspaso . " and sucursal_origen = '" . (integer)$xmlDoc->
        attributes()->sucursal_origen . "' and sucursal_destino = '" . (integer)$xmlDoc->
        attributes()->sucursal_destino . "'";
    
    $id = $db->select('traspasos_control', 'idTraspaso', $where);
    $id = $db->getResult();
    
    if (count($id) > 0) {
    
        $id = $id['idTraspaso'];
    
        $db->insert('traspasos_control', $a,
            "traspaso, tipo_traspaso, sucursal_origen, sucursal_destino, fecha, observaciones, numeroempleado, referencia",
            "fecha=values(fecha), observaciones=values(observaciones), numeroempleado=values(numeroempleado), referencia=values(referencia)");
        $db->delete('traspasos_detalle', 'idTraspaso = ' . $id);
    
        foreach ($xmlDoc->detalle as $item) {
    
            $b = array(
                $id,
                (string)$item->attributes()->ean,
                (integer)$item->attributes()->cantidad,
                (float)$item->attributes()->costo
                );
    
            $db->insert('traspasos_detalle', $b,
                "idTraspaso, ean, cantidad, costo");
        }
    
    
    } else {
    
        $db->insert('traspasos_control', $a,
            "traspaso, tipo_traspaso, sucursal_origen, sucursal_destino, fecha, observaciones, numeroempleado, referencia",
            "fecha=values(fecha), observaciones=values(observaciones), numeroempleado=values(numeroempleado)");
        $id = $db->getResult();
        foreach ($xmlDoc->detalle as $item) {
    
            $b = array(
                $id,
                (string)$item->attributes()->ean,
                (integer)$item->attributes()->cantidad,
                (float)$item->attributes()->costo
                );
    
            $db->insert('traspasos_detalle', $b,
                "idTraspaso, ean, cantidad, costo");
        }
    
    }
    
    return $id;

}

function _putAsistencia($cia, $sucursal, $numeroempleado, $tipo)
{
    //numeroempleado, tipo, sucursal, cia
    $a = array(
        'numeroempleado' => $numeroempleado, 'tipo' => $tipo, 'sucursal' => $sucursal, 'cia' => $cia
        );
        
    require_once ('../lib/crud_pdvoffice.php');
    $db = new Database();
    $db->connect();
    $db->insert('asistencia', $a,
            "numeroempleado, tipo, sucursal, cia");

}

$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service(utf8_encode($POST_DATA));                
exit();
?>
