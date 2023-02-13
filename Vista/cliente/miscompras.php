<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";

if($resp){
$objAbmCompra = new AbmCompra();
$array = ["idusuario" => $idusuario];
$listaCompras = $objAbmCompra->buscar($array);
//print_r($objAbmProducto);

//Verifica si tiene acceso este rol
$link = $_SERVER['REQUEST_URI'];
if($objTrans->tieneAcceso($objMenus, $link)){
?>
  <div class="container" id="mycontainer"><br>
    <div align="center"><span class="titulo2">MIS COMPRAS</span></div><br>

      <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">FECHA</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>

        <?php 
            foreach($listaCompras as $objCompra){ 
                $compra = '<a type="button" href="detalleCompra.php?idcompra='.$objCompra->getIdcompra().'"><button id="botonDetalles">Detalles</button></a>';
        ?>
            <tr>
                <td id="fila"><?php echo $objCompra->getIdcompra();?></td>
                <td id="fila"><?php echo $objCompra->getCofecha();?></td>
                <td id="fila" style="text-align: center;"><?php echo $compra;?></button>
                </td>
            </tr>
           <?php } ?>
        </tbody>
      </table>
  </div>
</body>
</html>

<?php
  }
  else{ ?>
      <!--br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php //echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php //echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php"/-->
      <meta http-equiv="refresh" content="0;url=../principal/home.php"/>
<?php  }
}
else{ ?>
      <meta http-equiv="refresh" content="0;url=../pagina/index.php" />
<?php
    }
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
          "sSearch": "Buscar",
          "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
          },
          "sProcessing":"Procesando...",
      },
      "columnDefs": [ { "orderable": false, "targets": 2 }], //ocultar para columna 2
    });
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