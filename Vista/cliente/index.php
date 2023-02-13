<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";

if($resp){
  $objAbmProducto = new AbmProducto();
  $listaProducto = $objAbmProducto->buscar(null);

//Verifica si tiene acceso este rol
$link = $_SERVER['REQUEST_URI'];
if($objTrans->tieneAcceso($objMenus, $link)){

?>
  <div class="container" id="mycontainer"><br>
    <div align="center"><span class="titulo2">PRODUCTOS</span></div>

    <!--a class="btn btn-success" id="botonAltaProducto" role="button" href="micarrito.php">Mi carrito</a><br-->

      <a href="micarrito.php"><img src="../img/carrito2.png" width="70" height="40" title="Mi Carrito"></a><br>

    <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">NOMBRE</th>
          <th id="columna">STOCK</th>
          <th id="columna">PRECIO</th>
          <th id="columna" style="text-align: center;" width="200">IMAGEN</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>
        <?php foreach($listaProducto as $objAbmProducto){ 
                
              $salida = "ID: ".$objAbmProducto->getIdproducto(). '\n'." NOMBRE: ".$objAbmProducto->getPronombre(). '\n'."STOCK: ".$objAbmProducto->getProcantstock(). '\n'."PRECIO: ".$objAbmProducto->getProprecio(). '\n'."DETALLES".'\n'.$objAbmProducto->getProdetalle(). '\n';
                
              $comprar = '<a type="button" href="compra.php?idproducto='.$objAbmProducto->getIdproducto().'"><button id="botonEditar">Comprar</button></a>';

              $imagenGuardada = "../img/".$objAbmProducto->getPronombre().".jpg";

                if($objAbmProducto->getProestado()=="1"){
                    $estado ="HABILITADO";
                }
                else{
                  $estado ="INHABILITADO";
                }
            ?>
              <tr>
                <td id="fila"><?php echo $objAbmProducto->getIdproducto();?></td>
                <td id="fila"><?php echo $objAbmProducto->getPronombre();?></td>
                <td id="fila"><?php echo $objAbmProducto->getProcantstock();?></td>
                <td id="fila"><?php echo $objAbmProducto->getProprecio();?></td>
                <td id="fila" style="text-align: center;">
                  <img id="imagenBorde" src="<?php echo $imagenGuardada ?>" width="250" height="90">
                </td>
                <td id="fila" style="text-align: center;"><button id="botonDetalles" onclick="verDatos('<?php echo $salida ?>', '<?php echo $imagenGuardada ?>')">Detalles</button><?php echo $comprar; ?></td>
              </tr>
        <?php } ?>
        </tbody>
    </table>
  </div><br><br>
  </body>
</html>

<?php
  }
  else{ ?>
      <br>
      <!--div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php //echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php //echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php" /-->
      <meta http-equiv="refresh" content="0;url=../principal/home.php" />
<?php  }
}
else{ ?>
      <meta http-equiv="refresh" content="0;url=../pagina/index.php" />
<?php    }
?>



<script>
  $(document).ready(function () {
    $('#ejemplo').DataTable({
      "language": {
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sSearch": "Buscar:",
          "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
          },
          "sProcessing":"Procesando...",
      },
      "columnDefs": [ { "orderable": false, "targets": 5 }, { "orderable": false, "targets": 4 }], //ocultar para columna 0
    });

  /*  $('#botonAltaProducto').on('click', function() {
      window.location='nuevoProducto.php';
      return false;
    });*/

  });

  function verDatos($datos, $foto){
    swal.fire({
      title: '<span style="color:black; font-size: 16px; font-family: Arial;">'+$datos+'</span>', 
      width:'650px',
      imageUrl: $foto,
      imageWidth: 300,
      imageHeight: 150,
      allowOutsideClick: false,
      confirmButtonColor: '#3498DB',
    });
  }
</script>


<style type="text/css">
  #mycontainer { max-width: 1300px !important; }
</style>