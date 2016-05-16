<?php
require_once ('../lib/crud_pdvoffice.php');
$xml = utf8_decode('<?xml version="1.0" encoding="utf-8"?><entrada entrada="1" sucursal="4" proveedor="EFA081118FK9" folio="NE908" fecha="2013-10-29" observaciones="" numeroempleado="40001" alta="2013-10-30"><e sucursal="4" entrada="1" detalle="1" ean="7501349025073" precio="8.3100" cantidad="15" /><e sucursal="4" entrada="1" detalle="2" ean="780083140939" precio="40.1900" cantidad="5" /><e sucursal="4" entrada="1" detalle="3" ean="780083144814" precio="33.6900" cantidad="5" /><e sucursal="4" entrada="1" detalle="4" ean="785120753523" precio="26.6500" cantidad="9" /><e sucursal="4" entrada="1" detalle="5" ean="785118753597" precio="9.2100" cantidad="11" /><e sucursal="4" entrada="1" detalle="6" ean="7503000422733" precio="12.6000" cantidad="10" /><e sucursal="4" entrada="1" detalle="7" ean="7502009741296" precio="46.4500" cantidad="8" /><e sucursal="4" entrada="1" detalle="43" ean="7501672690245" precio="7.5000" cantidad="170" /><e sucursal="4" entrada="1" detalle="455" ean="7502001165533" precio="31.3800" cantidad="5" /></entrada>');

    $xmlDoc = simplexml_load_string($xml);
    
    //sucursal, entrada, proveedor, fecha, observaciones, numeroempleado, alta
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

    echo $id;

?>