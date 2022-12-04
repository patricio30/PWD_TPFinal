<?php
include_once '../../configuracion.php';
$datos = data_submitted();

$resp = false;
$objTrans = new AbmUsuario();

if($datos['accion']=="editarPerfil"){
    $resp = $objTrans->abm($datos);
    
    if($resp){
    	echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Datos actualizados', 'salida' => '0'));
    }else{
    	echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar', 'salida' => '1'));
    }
}

//Cuando el cliente selecciona "Comprar" el producto***
//Se carga compraitem - se actualiza stock - Se carga la compra
if($datos['accion']=="nuevo"){
	$idusuario = $datos['idusuario'];
	$objCompra = new AbmCompra();
	$objProducto = new AbmProducto();
	$objCompraItem = new AbmCompraitem();

	if($objCompra->esCarritoActivo($idusuario)){ //Si tiene una compra pendiente
		$idcompra = $objCompra->idUltimaCompra($idusuario);

		if(!$objCompraItem->cargarCompraItem($datos, $idcompra)){
			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar item de la compra', 'salida' => '1'));
		}else{
			if($objProducto->actualizarStock($datos['idproducto'], $datos['cantidad'])){
				echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Agregado al carrito', 'salida' => '0'));
			}
			else{
				echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar stock', 'salida' => '1'));
			}
		}	    
	}
	else{//Es la 1 compra del carrito - Creo la compra, compraitem y compraEstado
		$objAbmCompra = new AbmCompra();
		if($objAbmCompra->alta($datos)){ //se dio de alta la compra

			//Cargo compraitem
			$idUltimaCompra = $objAbmCompra->ultimaCompraCargada();
			$objCompraItem = new AbmCompraitem();
			if(!$objCompraItem->alta(["idproducto" => $datos['idproducto'], "idcompra" => $idUltimaCompra, "cicantidad" => $datos['cantidad']])){
				
				echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar item de la compra', 'salida' => '1'));
			}
			else{//Cargo compraEstado
				$objProducto = new AbmProducto();
				if($objProducto->actualizarStock($datos['idproducto'], $datos['cantidad'])){
					
					$objCompraEstado = new AbmCompraEstado();
					$fechaInicio = date('Y-m-d H:i:s', time()); //Fecha actual
					$arrayAsociativoCompra = ["idcompra" => $idUltimaCompra,"idcompraestadotipo" => 1,"cefechaini" => $fechaInicio,"cefechafin" => '0000-00-00 00:00:00'];
		    		
		    		if(!$objCompraEstado->alta($arrayAsociativoCompra)){
		    			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar estado de la compra', 'salida' => '1'));
		    		}
		    		else{
		    			echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Agregado al carrito', 'salida' => '0'));
		    		}
		    	}
		    	else{
		    		echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar stock', 'salida' => '1'));
		    	}
			}
		}
		else{
			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar la compra', 'salida' => '1'));
		}
	}
}

//Cuando el cliente elimina un producto de su carrito***
if($datos['accion']=="eliminar"){
	$objCompraItem = new AbmCompraitem();
	$objCompraEstado = new AbmCompraEstado();
	$objProducto = new AbmProducto();
	$idcompraitem = $datos['idcompraitem'];
	$idcompra = $datos['idcompra'];
	$cantidad = $datos['cantidad'];
	$idproducto = $datos['valor'];


	//Es el unico producto del carrito
	//Elimino compraitem - actualizo compraestado - inserto compraestado
	if($objCompraItem->esUltimaCompraItemss($idcompra)){ 

		//Actualiza la compraestado (la fechafin) e inserta una nueva
		$salida = $objCompraEstado->cancelarCompraCarrito($idcompra); 

		//Elimina compraitem
		$objCompraItem = new AbmCompraitem();
		$bajaCompraItem = $objCompraItem->abm(["idcompraitem" => $idcompraitem, "accion" => "borrar"]);

		if(($salida)&&($bajaCompraItem)){
			if($objProducto->actualizarStockEliminado($idproducto, $cantidad)){ //Actualizo stock
				echo json_encode(array('mensaje1'=>'Exito', 'mensaje2'=>"Producto eliminado", 'salida'=>'0'));
			}
			else{
				echo json_encode(array('mensaje1'=>'Error', 'mensaje2'=>"No pudo actualizarse stock", 'salida'=>'1'));
			}
		}
		else{
			echo json_encode(array('mensaje1'=>'Error', 'mensaje2'=>"No pudo eliminarse el producto", 'salida'=>'1'));
		}
	}
	else{//NO es el unico producto del carrito - Solo elimino compraitem
		$objCompraItem = $objCompraItem->buscar(["idcompraitem" => $idcompraitem]);
		$objProducto->actualizarStockEliminado($objCompraItem[0]->getObjproducto()->getIdproducto(), $objCompraItem[0]->getCantidad());

		//Elimino compraitem
		$objCompraItem = new AbmCompraitem();
		$bajaCompraItem = $objCompraItem->abm(["idcompraitem" => $idcompraitem, "accion" => "borrar"]);

		if($bajaCompraItem){
			echo json_encode(array('mensaje1'=>'Exito', 'mensaje2'=>"Producto eliminado", 'salida'=>'0'));
		}
		else{
			echo json_encode(array('mensaje1'=>'Exito', 'mensaje2'=>"Producto eliminado", 'salida'=>'0'));
		}
	}
}

//Cuando el cliente cancela la compra del carrito***
if($datos['accion']=="cancelar"){
	$idcompra = $datos['idcompra'];
	$objCompraEstado = new AbmCompraEstado();
	$objCompraItem = new AbmCompraitem();
	
	//Elimina las comprasitems y actualiza el stock
	$eliminar = $objCompraItem->eliminarCompras($idcompra);
	if($eliminar){
		//Actualiza la compraestado y crea una nueva
		$salida = $objCompraEstado->cancelarCompraCarrito($idcompra);
		if($salida){
			echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra cancelada", 'salida' => '0'));
		}
		else{
			echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo cancelar la compra", 'salida' => '1'));
		}
	}
	else{
		echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => "No se pudo cancelar la compra", 'salida' => '1'));
	}

	
}

//Cuando desde Mi carrito selecciona Comprar. La compra pasa de estado borrador a estado iniciada***
if($datos['accion']=="comprar"){
	$idcompra = $datos['idcompra'];
	$objCompraEstado = new AbmCompraEstado();

	if($objCompraEstado->aceptarCompraCarrito($idcompra)){
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra realizada", 'salida' => '0'));
	}else{
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo realizar la compra", 'salida' => '1'));
	}
}

?>