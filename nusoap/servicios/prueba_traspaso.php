<?php
require_once ('../lib/crud_pdvoffice.php');

$xml = '<?xml version="1.0" ?><traspaso traspaso="1" tipo_traspaso="2" sucursal_origen="101" sucursal_destino="5" fecha="01/05/2014 09:34:03 p. m." observaciones="" numeroempleado="34055"><detalle ean="7501537102104" cantidad="10" costo="10">1</detalle><detalle ean="7501537102357" cantidad="15" costo="15">2</detalle></traspaso>';
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
    (integer)$xmlDoc->attributes()->numeroempleado
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
        "traspaso, tipo_traspaso, sucursal_origen, sucursal_destino, fecha, observaciones, numeroempleado",
        "fecha=values(fecha), observaciones=values(observaciones), numeroempleado=values(numeroempleado)");
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
        "traspaso, tipo_traspaso, sucursal_origen, sucursal_destino, fecha, observaciones, numeroempleado",
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


?>