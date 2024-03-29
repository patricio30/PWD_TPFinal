<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";

if($resp){
$objAbmUsuario = new AbmUsuario();
$listaUsuarios = $objAbmUsuario->buscar(null);

//Verifica si tiene acceso este rol
$link = $_SERVER['REQUEST_URI'];
if($objTrans->tieneAcceso($objMenus, $link)){
?>

  
  <div class="container" id="mycontainer"><br>
    <div align="center"><span class="titulo2">USUARIOS</span></div><br>
    <!--div style="text-align: right;"><button id="botonAltaProducto" onclick="" title="Alta de producto">Alta</button></div-->
    <!--a class="btn btn-success" id="botonAltaProducto" role="button" href="nuevoUsuario.php?accion=nuevo&id=-1">Nuevo</a><br-->

    <a class="btn btn-success" id="botonAltaProducto" role="button" href="nuevoUsuario.php">Nuevo</a><br>

    <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">NOMBRE</th>
          <th id="columna">MAIL</th>
          <th id="columna">ESTADO</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>
        <?php foreach($listaUsuarios as $objAbmUsuario){ 
                
              //$salida = "Detalles del Usuario".'\n\n'."ID: ".$objAbmUsuario->getIdusuario(). '\n'." Nombre: ".$objAbmUsuario->getUsnombre(). '\n'."Mail: ".$objAbmUsuario->getUsmail(). '\n';

              $mensaje1 = "Detalles de Usuario";
              $mensaje2 = "Id: ".$objAbmUsuario->getIdusuario().'<br>'." Nombre: ".$objAbmUsuario->getUsnombre(). '<br>'."Mail: ".$objAbmUsuario->getUsmail(). '<br>';
                
              $editar = '<a type="button" href="editarUsuario.php?idusuario='.$objAbmUsuario->getIdusuario().'"><button id="botonEditar">Editar</button></a>';

                if($objAbmUsuario->getUsdeshabilitado()=='NULL'){
                    $estado ="INHABILITADO";
                }
                else{
                  $estado ="HABILITADO";
                }
            ?>
              <tr>
                <td id="fila"><?php echo $objAbmUsuario->getIdusuario();?></td>
                <td id="fila"><?php echo $objAbmUsuario->getUsnombre();?></td>
                <td id="fila"><?php echo $objAbmUsuario->getUsmail();?></td>
                <td id="fila"><?php echo $estado;?></td>
                <td id="fila" style="text-align: center;">
                  <!--button id="botonDetalles" onclick="verDatos('<?php //echo $salida ?>')">Detalles</button><?php //echo $editar; ?></td-->
                  <button id="botonDetalles" onclick="verDatos('<?php echo $mensaje1 ?>', '<?php echo $mensaje2 ?>')">Detalles</button><?php echo $editar; ?></td>
              </tr>
        <?php } ?>
        </tbody>
    </table>
  </div><br><br>
  </body>
</html>
<?php
    }
    else{
?>
      <br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php"/>
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
          "sSearch": "Buscar:",
          "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
          },
          "sProcessing":"Procesando...",
      },
      "columnDefs": [ { "orderable": false, "targets": 4 }], //ocultar para columna 0
    });

  });

  /*function verDatos($datos){
    swal.fire({
      title: '<span style="color:black; font-size: 16px; font-family: Arial;">'+$datos+'</span>',
      width:'650px',
      imageWidth: 300,
      imageHeight: 150,
      allowOutsideClick: false,
      confirmButtonColor: '#3498DB',
    });
  }*/

  function verDatos($mensaje1, $mensaje2){
    swal.fire({
      title: '<span style="color:black; font-size: 22px; font-family: Arial;">'+$mensaje1+'</span>',
      html: '<span style="color:black;">'+$mensaje2+'</span>',
      width:'550px',
      imageWidth: 300,
      imageHeight: 150,
      allowOutsideClick: false,
      confirmButtonColor: '#3498DB',
    });
  }
</script>