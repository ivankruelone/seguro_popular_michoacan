<?php
require_once ('../lib/crud_pdvoffice.php');

$xml = '<?xml version="1.0" encoding="utf-8"?><venta venta="113" sucursal="958" cliente="XAXX010101000" cajero="34055" fecha="2013-07-18" hora="00:00:00" cliente_tipo="1" estatus="1" cambio="2013-07-18 09:38:58" fecha_cancelacion="" cajero_cancelo="0" caja="00241D281BBE" vendedor="34055" fidelizacion_tipo="0" fidelizacion_tarjeta="0" credito_nomina="0" credito_empleado="" corte="0" cierre="0"><detalle detalle="278" venta="113" ean="656599041216" precio="36.00" cantidad="1" iva="0.00" descuento_tasa="0.00" descuento="0.00" total="36.00" iva_tasa="0.00" descuento_limitado="0" antibiotico="0" fisico="1"></detalle><detalle detalle="279" venta="113" ean="7501571200217" precio="13.50" cantidad="3" iva="0.00" descuento_tasa="0.00" descuento="0.00" total="40.50" iva_tasa="0.00" descuento_limitado="0" antibiotico="0" fisico="1"></detalle><detalle detalle="280" venta="113" ean="7501571200590" precio="18.00" cantidad="2" iva="0.00" descuento_tasa="0.00" descuento="0.00" total="36.00" iva_tasa="0.00" descuento_limitado="0" antibiotico="0" fisico="1"></detalle><pagos pago="115" venta="113" idformas_pago="3" monto="112.50" tipo_cambio="0.000000" dolares="0.00" entregado="150.00" corte="0" cierre="0"></pagos></venta>';
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

        $db->insert('detalle', $b,
            "id, detalle, venta, ean, precio, cantidad, iva, descuento_tasa, descuento, total, iva_tasa, descuento_limitado, antibiotico, fisico");
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


?>