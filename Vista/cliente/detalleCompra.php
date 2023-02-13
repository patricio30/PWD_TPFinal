<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";
if($resp){
    $datos = data_submitted();
    $idcompra = $datos['idcompra'];
?>
  <div class="container" id="mycontainer"><br>
    <div align="center"><span class="titulo2">DETALLES DE COMPRA</span></div><br><br>


    <?php

      $objAbmCompraEstado = new AbmCompraEstado();
      $array = ["idcompra" => $idcompra];
      $listaEstados = $objAbmCompraEstado->buscar($array);

      $objAbmCompraItem = new AbmCompraitem();
      $listaItems = $objAbmCompraItem->buscar($array);
    ?>

    <div align="left"><span class="subtitulo2">Estados</span></div>
      <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">ID COMPRA</th>
          <th id="columna">ESTADO</th>
          <th id="columna">FECHA INICIO</th>
          <th id="columna">FECHA FIN</th>
        </tr></thead>
        <tbody>

        <?php 
        foreach($listaEstados as $objCompraEstado){
        ?>
          <tr>
            <td id="fila"><?php echo $objCompraEstado->getIdCompraEstado();?></td>
            <td id="fila"><?php echo $idcompra;?></td>
            <td id="fila"><?php echo $objCompraEstado->getCompraEstadoTipo()->getCetDescripcion();?></td>
            <td id="fila"><?php echo $objCompraEstado->getCeFechaIni();?></td>
            <td id="fila"><?php echo $objCompraEstado->getCeFechaFin();?></td>
          </tr>
  <?php } ?>
        </tbody>
      </table><br><br>


    <div align="left"><span class="subtitulo2">Productos</span></div>
      <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">ID PRODUCTO</th>
          <th id="columna">PRODUCTO</th>
          <th id="columna">CANTIDAD</th>
          <th id="columna">PRECIO UNITARIO</th>
          <th id="columna">TOTAL</th>
        </tr></thead>
        <tbody>

        <?php 
        $totalCompra=0;
        foreach($listaItems as $objCompraItem){
          $total = $objCompraItem->getCantidad()*$objCompraItem->getObjproducto()->getProprecio();
          $totalCompra = $total + $totalCompra;
        ?>
          <tr>
            <td id="fila"><?php echo $objCompraItem->getIdcompraitem();?></td>
            <td id="fila"><?php echo $objCompraItem->getObjproducto()->getIdproducto();?></td>
            <td id="fila"><?php echo $objCompraItem->getObjproducto()->getPronombre();?></td>
            <td id="fila"><?php echo $objCompraItem->getCantidad();?></td>
            <td id="fila"><?php echo "$".$objCompraItem->getObjproducto()->getProprecio();?></td>
            <td id="fila"><?php echo "$".$total;?></td>
          </tr>
  <?php } ?>
        <tr><td colspan="4">TOTAL: </td><td colspan="2"><?php echo "$".$totalCompra;?></td></tr>
        </tbody>
      </table><br><br>


  </div><br>


<?php
}
else{  ?>
    <meta http-equiv="refresh" content="0;url=../pagina/index.php" />
  <?php
}
?>
