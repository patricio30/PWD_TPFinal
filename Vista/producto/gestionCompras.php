<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";

if($resp){
$objAbm = new AbmCompraEstado();
$array = ["cefechafin" => "0000-00-00 00:00:00"];
$lista = $objAbm->buscar($array);

//Verifica si tiene acceso este rol
$link = $_SERVER['REQUEST_URI'];
if($objTrans->tieneAcceso($objMenus, $link)){

?>

  <div class="container" id="mycontainer"><br>
    <div align="center"><span class="titulo2">GESTIÓN DE COMPRAS</span></div><br>

    <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID COMPRA</th>
          <th id="columna">ESTADO</th>
          <th id="columna">FECHA DE INICIO</th>
          <th id="columna">USUARIO</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>
        <?php 
          if($lista!=null){
            foreach($lista as $objAbm){ 
                if(($objAbm->getCompraEstadoTipo()->getCetDescripcion()!="cancelada")&&($objAbm->getCompraEstadoTipo()->getCetDescripcion()!="borrador")){

                  //Obtengo el mail del cliente
                  $mailUsuario = $objAbm->getCompra()->getObjusuario()->getUsmail();
           ?>
              <tr>
                <td id="fila"><?php echo $objAbm->getCompra()->getIdcompra();?></td>
                <td id="fila"><?php echo $objAbm->getCompraEstadoTipo()->getCetDescripcion();?></td>
                <td id="fila"><?php echo $objAbm->getCeFechaIni();?></td>
                <td id="fila"><?php echo $objAbm->getCompra()->getObjusuario()->getUsnombre()?></td>
                <td id="fila" style="text-align: center;">

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="iniciada"){?>
                  <button id="botonAceptarG" title="Aceptar compra" onclick="aceptar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "aceptar" ?>', '<?php echo $mailUsuario ?>')">Aceptar</button>
                  <button id="botonCancelarG" title="Cancelar compra" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>', '<?php echo $mailUsuario ?>')">Cancelar</button>
                <?php } ?>

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="aceptada"){?>
                  <button id="botonAceptarG" title="Enviar compra" onclick="enviar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "enviar" ?>', '<?php echo $mailUsuario ?>')">Enviar</button>
                  <button id="botonCancelarG" title="Cancelar compra" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>', '<?php echo $mailUsuario ?>')">Cancelar</button>
                <?php } ?>

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="enviada"){?>
                  <button id="botonAceptarG" title="Finalizar compra" onclick="finalizar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "finalizar" ?>', '<?php echo $mailUsuario ?>')">Finalizar</button>
                  <button id="botonCancelarG" title="Cancelar compra" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>', '<?php echo $mailUsuario ?>')">Cancelar</button>
                <?php } ?>


                </td>
              </tr>
        <?php   } 
            } 
            }       
          ?>
        </tbody>
    </table>

    <div id="loader" style="display:none"></div>

  </div><br><br> 
  </body>
</html>

<?php
    }
    else{ ?>
      <br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php"/>
<?php   
    }
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

function cancelar(idcompra, accion, mail){
  var datos = {"idcompra":idcompra, "accion":accion, "mail":mail};
  Swal.fire({
    title: '¿Desea cancelar la compra?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((result) => {
      if (result.value){
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            $("#loader").css('display','block');
          },
          success: function(data){
          $("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}

function aceptar(idcompra, accion, mail){
  var datos = {"idcompra":idcompra, "accion":accion, "mail":mail};
  Swal.fire({
    title: '¿Desea aceptar la compra?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((result) => {
      if (result.value){
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            $("#loader").css('display','block');
          },
          success: function(data){
          $("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}

function enviar(idcompra, accion, mail){
  var datos = {"idcompra":idcompra, "accion":accion, "mail":mail};
  Swal.fire({
    title: '¿Desea enviar la compra?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value){
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            $("#loader").css('display','block');
          },
          success: function(data){
          $("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}

function finalizar(idcompra, accion, mail){
  var datos = {"idcompra":idcompra, "accion":accion, "mail":mail};
  Swal.fire({
    title: '¿Desea finalizar la compra?',
    width:'550px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((result) => {
    if (result.value){
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          beforeSend: function() {
            $("#loader").css('display','block');
          },
          success: function(data){
          $("#loader").css('display','none');
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}


function mensajeError($mensaje1, $mensaje2){
    swal.fire({
      type: 'error',
      title: $mensaje1,
      text: $mensaje2,
      allowOutsideClick: false,
      width:'600px',
      imageWidth: '160px',
    });
    return false;
  }

  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){window.location.replace("gestionCompras.php");});
  }

</script>


<style type="text/css">
#loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../img/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

#botonCancelarG{
  color: white;
  background-color: red;
  border: 2px solid;
  border-radius: 10px;
  height: 30px;
  width: 95px;
  font-size: 16px;
  font-family: Georgia;
}
#botonCancelarG:hover{
   color: red;
   background-color:white;
}

#botonAceptarG{
  color: white;
  background-color: #058B32;
  border: 2px solid;
  border-radius: 10px;
  height: 30px;
  width: 95px;
  font-size: 16px;
  font-family: Georgia;
}
#botonAceptarG:hover{
   color: #058B32;
   background-color:white;
}
</style>