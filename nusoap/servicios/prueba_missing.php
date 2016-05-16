<?php
$sucursal = 1;
$cuenta = 5623;

    require_once ('../lib/crud_pdvoffice.php');

	    $sql = "SELECT (t1.venta + 1) as inicio,
       (SELECT MIN(t3.venta) -1 FROM ventas t3 WHERE t3.venta > t1.venta and sucursal = $sucursal) as fin
FROM ventas t1
WHERE NOT EXISTS (SELECT t2.venta FROM ventas t2 WHERE t2.venta = t1.venta + 1 and sucursal = $sucursal)
HAVING fin IS NOT NULL";

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
        echo "COMPLETAS";
    }
    
    $db->select_directo($sql);
    $res = $db->getResult();
    
    print_r($res);
    //id, ean, descripcion, precio, clave, cambio
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?><missing>';
    
    
    if(count($res) > 1){
    
        foreach($res as $row){
            $xml .= '<miss inicio="'.$row['inicio'].'" fin="'.$row['fin'].'" />';
    
        }
    
    }elseif(count($res) == 1){

        $xml .= '<miss inicio="'.$res['inicio'].'" fin="'.$res['fin'].'" />';
        
    }
    
    $xml .= '</missing>';
    
    echo $xml;

?>