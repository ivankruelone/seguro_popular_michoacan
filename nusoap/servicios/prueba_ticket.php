<?php
$suc = 501;
$ticket = 'V00510100037304';

require_once('../lib/crud.php');
$db = new Database();
$db->connect();
$db->select('vtadc.venta_detalle', "codigo, descri, can, vta, des, importe, iva", "suc = ".(integer)$suc." and tiket ='".(string)$ticket."'");
$res = $db->getResult();

echo "<pre>";
print_r($res);
echo "</pre>";

echo "json <br />";
$json = json_encode($res);

echo $json;

$var = json_decode($json);

echo "<pre>";
print_r($var);
echo "</pre>";

?>