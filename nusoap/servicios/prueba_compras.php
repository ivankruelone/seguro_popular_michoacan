<?php
	    require_once('../lib/crud_compras.php'); 
        
        $pedido = '<?xml version="1.0" encoding="UTF-8"?>
<pedido estado="1" id="2">
  <producto clave="3" pedido="100"/>
  <producto clave="11" pedido="100"/>
  <producto clave="12" pedido="100"/>
  <producto clave="14" pedido="100"/>
  <producto clave="21" pedido="100"/>
  <producto clave="22" pedido="100"/>
</pedido>';

    $xmlDoc = simplexml_load_string($pedido);
    
    
            echo "<pre>";
        print_r($xmlDoc);
        echo "</pre>";

    $a = array((integer)$xmlDoc->attributes()->estado, (integer)$xmlDoc->attributes()->id);
    
    $db = new Database();
    $db->connect();
    $db->insert('pedidos_almacenes_control', $a, 'estado, id_pedido', 'estado = values(estado)');
    $res = $db->getResult();
    
    
    if($res == 0){
    
        foreach($xmlDoc->producto as $item)
        {
            $b = array((integer)$xmlDoc->attributes()->estado, (integer)$xmlDoc->attributes()->id, (string)$item->attributes()->clave, (integer)$item->attributes()->pedido);
        echo "<pre>";
        print_r($b);
        echo "</pre>";
            $db = new Database();
            $db->connect();
            $db->insert('pedidos_almacenes_detalle', $b, 'estado, id_pedido, clave, pedido');

        }
        
        return 1;
    }else{
        return 0;
    }

?>