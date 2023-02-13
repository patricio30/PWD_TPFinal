<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AbmCompraEstado{

    public function abm($datos){
        $resp = false;
        if($datos['accion']=='editar'){
            if($this->modificacion($datos)){
                $resp = true;
            }
        }
        if($datos['accion']=='borrar'){
            if($this->baja($datos)){
                $resp =true;
            }
        }
        if($datos['accion']=='nuevo'){
            if($this->alta($datos)){
                $resp =true;
            }
        }
        return $resp;
    }



    private function cargarObjeto($param){
        $obj = null;
        if (array_key_exists('idcompraestado', $param) and array_key_exists('idcompra', $param)
            and array_key_exists('idcompraestadotipo', $param) and array_key_exists('cefechaini', $param)
            and array_key_exists('cefechafin', $param)){

            $objCompra = new Compra();
            $objCompra->setIdcompra($param['idcompra']);
            $objCompra->cargar();

            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setIdCompraEstadoTipo($param['idcompraestadotipo']);
            $objCompraEstadoTipo->cargar();

            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $objCompra, $objCompraEstadoTipo, $param['cefechaini'], $param['cefechafin']);
        }
        return $obj;
    }

     /**
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], null, null, null, null);
        }
        return $obj;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }

    /**
     * Inserta en la base de datos
     * @param array $param
     * @return boolean
     */
    public function alta($param){
        //print_r($param);
        $resp = false;
        $param['idcompraestado'] = null;
        $obj = $this->cargarObjeto($param);
        //print_r($obj);

        if ($obj != null and $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $obj = $this->cargarObjetoConClave($param);
            if ($obj !=null and $obj->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    } 

    /**
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null and $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraEstado']))
                $where .= " and idcompraEstado =" . $param['idcompraestado'];
            if (isset($param['idcompra']))
                $where .= " and idcompra =" . $param['idcompra'];
            if (isset($param['idcompraestadotipo']))
                $where .= " and idcompraestadotipo ='" . $param['idcompraestadotipo'] . "'";
            if (isset($param['cefechafin']))
                $where .= " and cefechafin ='" . $param['cefechafin'] . "'";
        }
        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
       //print_r($arreglo);
        return $arreglo;
    }

    public function buscarCompraBorrador($arrayCompra){
        $objCompraEstadoInciada = null;
        $i = 0;
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        while (($objCompraEstadoInciada == null) && ($i < count($arrayCompra))) {
            $idCompra["idcompra"] = $arrayCompra[$i]->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getCetDescripcion() == "borrador") {
                $objCompraEstadoInciada = $arrayCompraEstado[0];
            } else {
                $i++;
            }
        }
        return $objCompraEstadoInciada;
    }

    public function buscarCompras($arrayCompra){
        $arrayCompraIniciadas = [];
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        foreach($arrayCompra as $compra){
            $idCompra["idcompra"] = $compra->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getIdCompraEstadoTipo() >= 2) {
                array_push($arrayCompraIniciadas, $arrayCompraEstado[0]);
            }
        }
        return $arrayCompraIniciadas;
    }


    //Obtengo la ultima compraestado de la compra con idcompra - NO ME FUNCIONA
    public function actualizarCompraEstado($idcompra){
        $arrayAsociativo = ["idcompra" => $idcompra, "cefechafin" => "0000-00-00 00:00:00"];
        //$objCompraEstado = new AbmCompraEstado();
        $array = $this->buscar($arrayAsociativo);
        //print_r($array);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
        $array[0]->setCeFechaFin($fechaActual);
        $array[0]->modificar();
        return $array;
    }

    public function nuevaCompraEstado($idcompra){
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 2, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);
        return $salida;
    }


    //Obtengo la ultima compraestado de la compra con idcompra
    public function ultimaCompraEstado($idcompra){
        $arrayAsociativo = ["idcompra" => $idcompra];
        $array = $this->buscar($arrayAsociativo);
        return end($array);
    }

    public function ultimaCompraEstadoCompra($idcompra){
        $arrayAsociativo = ["idcompra" => $idcompra, "cefechafin" => '0000-00-00 00:00:00'];
        $array = $this->buscar($arrayAsociativo);
        return $array;
    }


    //Se invoca cuando se selecciona cancelar la compra desde el carrito
    //Actualiza la compraestado (la fechafin) e inserta una nueva
    public function cancelarCompraCarrito($idcompra){
    
        //Actualiza la compraestado (la fechafin)
        $obj = $this->ultimaCompraEstadoCompra($idcompra);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time());
        $obj[0]->setCeFechaFin($fechaActual); //Le seteo la fecha actual
        $obj[0]->modificar();

        //Doy de alta una nueva compraestado
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 5, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);

        return $salida;
    }


    //Obtengo el estado actual de la compra con idcompra
    public function estadoActualCompra($idcompra){
        $arrayAsociativo = ["idcompra" => $idcompra, "cefechafin" => "0000-00-00 00:00:00"];
        $array = $this->buscar($arrayAsociativo);
        $estado = $array[0]->getCompraEstadoTipo()->getIdCompraEstadoTipo();
     return $estado;
    }


    //Se invoca cuando se selecciona aceptar la compra desde gestion de compras
    //Actualiza la compraestado (la fechafin) e inserta una nueva
    public function aceptarCompra($idcompra){
    
        //Actualiza la compraestado (la fechafin)
        $obj = $this->ultimaCompraEstadoCompra($idcompra);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time());
        $obj[0]->setCeFechaFin($fechaActual); //Le seteo la fecha actual
        $obj[0]->modificar();

        //Doy de alta una nueva compraestado con estado aceptada
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 3, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);

        return $salida;
    }


    //Se invoca cuando se selecciona enviar la compra desde gestion de compras
    //Actualiza la compraestado (la fechafin) e inserta una nueva
    public function enviarCompra($idcompra){
    
        //Actualiza la compraestado (la fechafin)
        $obj = $this->ultimaCompraEstadoCompra($idcompra);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time());
        $obj[0]->setCeFechaFin($fechaActual); //Le seteo la fecha actual
        $obj[0]->modificar();

        //Doy de alta una nueva compraestado con estado aceptada
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 4, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);

        return $salida;
    }



    //Se invoca cuando el cliente selecciona comprar desde mi carrito
    //Actualiza la compraestado (la fechafin) e inserta una nueva
    public function aceptarCompraCarrito($idcompra){
    
        //Actualiza la compraestado (la fechafin)
        $obj = $this->ultimaCompraEstadoCompra($idcompra);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time());
        $obj[0]->setCeFechaFin($fechaActual); //Le seteo la fecha actual
        $obj[0]->modificar();

        //Doy de alta una nueva compraestado con estado iniciada
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 2, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);

        return $salida;
    }


     //Se invoca cuando se selecciona finalizar la compra desde gestion de compras
    //Actualiza la compraestado (la fechafin) e inserta una nueva
    public function finalizarCompra($idcompra){
    
        //Actualiza la compraestado (la fechafin)
        $obj = $this->ultimaCompraEstadoCompra($idcompra);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s', time());
        $obj[0]->setCeFechaFin($fechaActual); //Le seteo la fecha actual
        $obj[0]->modificar();

        //Doy de alta una nueva compraestado con estado aceptada
        $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 6, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
        $salida = $this->alta($arrayAsociativo);

        return $salida;
    }



    public function enviarMailCompra($idcompra, $mailUsuario, $estado){
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('d/m/y h:i:s');
        $mail = new PHPMailer(true);
        try {
            //Para enviar mail desde localhost
            $mail->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
            $mail->SMTPDebug = 0;                   
            $mail->isSMTP();                                            
            $mail->Host = 'smtp.gmail.com';                    
            $mail->SMTPAuth = true;                                   
            $mail->Username = 'patohrg@gmail.com';                   
            $mail->Password = 'uibrzglpkpqqvnlf'; //Contraseña de aplicacion en gmail                          
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('patohrg@gmail.com', 'Patricio RG');
            $mail->addAddress($mailUsuario);
            $mail->isHTML(true);                                
            $mail->Subject = 'Notificacion';
            $mail->Body = "Su compra con número de id ".$idcompra." paso a estado ".$estado." con fecha y hora: ".$fechaActual;
            $mail->send();
            $salida = true;
        }
        catch (Exception $e) {
         $salida = false;
        }
    return $salida;
    }

}

?>