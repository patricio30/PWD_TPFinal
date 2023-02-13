<?php
include_once '../../configuracion.php';
$datos = data_submitted();
$resp = false;
$objTrans = new AbmProducto();
date_default_timezone_set('America/Argentina/Buenos_Aires');

//Se utiliza cuando se da de alta un nuevo producto***
if($datos['accion']=="nuevo"){
    
    $producto = $objTrans->buscar(["pronombre" => $datos['pronombre']]);

    if(count($producto)==1){
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Producto ya existente', 'salida' => '1'));
    }
    else{
        $resp = $objTrans->abm($datos);
        if($resp){
            if ($_FILES['archivo']['error'] <= 0){
                $direccion = '../img/';
                //mkdir($direccion, 0777);
                
                //Obtiene la extension del archivo
                $extension = pathinfo($direccion.$_FILES['archivo']['name'], PATHINFO_EXTENSION);
                
                //Copia la foto adjuntada en la carpeta img
                copy($_FILES['archivo']['tmp_name'], $direccion.$_FILES['archivo']['name']);
                
                //Cambia el nombre de la imagen al nombre del producto
                rename($direccion.$_FILES['archivo']['name'], $direccion.$datos['pronombre'].".".$extension);

                echo json_encode(array('mensaje1' => 'Éxito', 'mensaje2' => 'Producto insertado', 'salida' => '0'));
            }
            else{
                echo json_encode(array('mensaje1' => 'Éxito', 'mensaje2' => 'Producto insertado sin imagen', 'salida' => '0'));
            }
            
        }else {
            echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la insercion', 'salida' => '1'));
        }
    }
}

//Se utiliza cuando se edita un producto***
if($datos['accion']=="editar"){
    $resp = $objTrans->abm($datos);
    if($resp){
        echo json_encode(array('mensaje1' => 'Éxito', 'mensaje2' => 'Producto actualizado', 'salida' => '0'));
    }else {
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la actualizacion', 'salida' => '1'));
    }
}

//Se utiliza cuando desde gestion de compras el admin cancela la compra***
if($datos['accion']=="cancelar"){
    $idcompra = $datos['idcompra'];
    $mail = $datos['mail'];
    $estado = "CANCELADA";
    
    //Elimino las comprasitems-Los stock vuelven a su estado original
    $objCompraItem = new AbmCompraitem();
    $objCompraItem->eliminarCompras($idcompra);

    //Actualiza la compraestado y crea una nueva
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->cancelarCompraCarrito($idcompra);
    
    if($salida){
        if($objCompraEstado->enviarMailCompra($idcompra, $mail, $estado)){
            echo json_encode(array('mensaje1' => 'Compra cancelada', 'mensaje2' => "Se envio mail al cliente", 'salida' => '0'));
        }
        else{
            echo json_encode(array('mensaje1' => 'Compra cancelada', 'mensaje2' => "No se envio notificación al cliente", 'salida' => '0'));
        }
    }else{
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => "No se pudo cancelar la compra", 'salida' => '1'));
    }
}

//Se utiliza cuando desde gestion de compras, el admin acepta la compra y pasa a estado aceptada***
if($datos['accion']=="aceptar"){
    $idcompra = $datos['idcompra'];
    $mail = $datos['mail'];
    $estado = "ACEPTADA";
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->aceptarCompra($idcompra);

    if($salida){
        if($objCompraEstado->enviarMailCompra($idcompra, $mail, $estado)){
            echo json_encode(array('mensaje1' => 'Compra aceptada', 'mensaje2' => "Se envio mail al cliente", 'salida' => '0'));
        }
        else{
            echo json_encode(array('mensaje1' => 'Compra aceptada', 'mensaje2' => "No se envio notificación al cliente", 'salida' => '0'));
        }
    }else{
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => "No se pudo aceptar la compra", 'salida' => '1'));
    }
}

//Se utiliza cuando desde gestion de compras, el admin acepta la compra y pasa a estado enviada***
if($datos['accion']=="enviar"){
    $idcompra = $datos['idcompra'];
    $mail = $datos['mail'];
    $estado = "ENVIADA";
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->enviarCompra($idcompra);

    if($salida){
        if($objCompraEstado->enviarMailCompra($idcompra, $mail, $estado)){
            echo json_encode(array('mensaje1' => 'Compra enviada', 'mensaje2' => "Se envio mail al cliente", 'salida' => '0'));
        }
        else{
            echo json_encode(array('mensaje1' => 'Compra enviada', 'mensaje2' => "No se envio notificación al cliente", 'salida' => '0'));
        }
    }else{
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => "No se pudo enviar la compra", 'salida' => '1'));
    }
}

//Se utiliza cuando desde gestion de compras, el admin finaliza la compra y pasa a estado finalizada***
if($datos['accion']=="finalizar"){
    $idcompra = $datos['idcompra'];
    $mail = $datos['mail'];
    $estado = "FINALIZADA";
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->finalizarCompra($idcompra);

    if($salida){
        if($objCompraEstado->enviarMailCompra($idcompra, $mail, $estado)){
            echo json_encode(array('mensaje1' => 'Compra finalizada', 'mensaje2' => "Se envio mail al cliente", 'salida' => '0'));
        }
        else{
            echo json_encode(array('mensaje1' => 'Compra finalizada', 'mensaje2' => "No se envio notificación al cliente", 'salida' => '0'));
        }
    }else{
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => "No se pudo finalizar la compra", 'salida' => '1'));
    }
}

?>