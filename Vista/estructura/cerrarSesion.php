<?php
include_once '../../configuracion.php';
$objSession = new Session();
$sesionCerrada = $objSession->cerrar();

if($sesionCerrada){
    echo json_encode(array('mensaje1' => 'Éxito', 'mensaje2' => 'Sesión finalizada', 'salida' => '0'));
}else{
    echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Error al cerrar sesión', 'salida' => '1'));
}
?>