<?php
include_once '../../configuracion.php';
$objTrans = new Session();
$resp = $objTrans->validar();
if(!$resp){
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo "Inicio de sesion"?></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../css/estilos.css?v7">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js" ></script>
</head>

<body class="fondo"><br><br>
<div class="container" align="center"><br>

    <span class="titulo2"><?php echo "INICIAR SESIÓN" ?></span><br><br>

    <form method="post" id="formularioSesion" enctype="multipart/form-data">

        <label class="subtitulo">Nombre *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="usnombre" name="usnombre" class="form-control" maxlength="50" autocomplete="off" style="text-transform: uppercase; border-color: #3498DB;">
        </div>

        <label class="subtitulo">Clave *</label><br/>
        <div class="input-group mx-auto">
          <input type="password" id="uspass" name="uspass" class="form-control" maxlength="150" autocomplete="off" style="text-transform: uppercase; border-color: #3498DB">
        </div>

        <div class="form-group col-md-12">
          <button class="btn btn-responsive float-right" type="submit" id="botonIngresar" title="Ingresar">Ingresar</button><br>
          <div class="float-left" style="color:red">* Datos obligatorios</div>
          <a class="botonVolver" href="../pagina/index.php">Volver</a>
        </div>
    </form><br><br>
</div>
</body>
</html>
<?php
}
else{ ?>
      <meta http-equiv="refresh" content="0;url=../principal/home.php" />
<?php
}
?>


<script type="text/javascript">
$('#botonIngresar').click(function(evento){
    pregunta = '¿Desea iniciar sesión?';
    evento.preventDefault();
    if(validarFormulario()){
        /*Swal.fire({
            title: pregunta,
            width:'500px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value){*/
              var password = document.getElementById("uspass").value;
              var hash = hex_md5(password).toString();
              document.getElementById("uspass").value = hash;
              var datos = new FormData($('#formularioSesion')[0]);
                $.ajax({
                    url: 'accion.php',
                    type:'POST',
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        var jsonData = JSON.parse(data);
                        if(jsonData.salida == 0){
                            //return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
                            return mensajeExito2(jsonData.mensaje);
                        }
                        else{
                         return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
                        }
                    }
                });
                return false;
            }
        //});
    //} 
});


function validarFormulario(){

    if($("#usnombre").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar NOMBRE");
      $("#usnombre").focus();
      return false;
    }
    if($("#uspass").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar CLAVE");
      $("#uspass").focus();
      return false;
    }
    return true;
  }
    
  function mensajeError($mensaje1, $mensaje2){
    swal.fire({
      type: 'error',
      title: $mensaje1,
      text: $mensaje2,
      allowOutsideClick: false,
      width:'500px',
      imageWidth: '160px',
    }).then(function(){
        document.getElementById("formularioSesion").reset();
    });
    return false;
  }

  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      //type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'500px',
      allowOutsideClick: false,
    }).then(function(){
        //document.getElementById("formularioSesion").reset();
        window.location.replace("../principal/home.php");
    });
    return false;
  }

  function mensajeExito2($mensaje1){
    Swal.fire({
      //type: 'success',
      title: $mensaje1,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){
        //document.getElementById("formularioSesion").reset();
        window.location.replace("../principal/home.php");
    });
    return false;
  }
</script>
 <script src="../js/md5.js"></script>


<style type="text/css">
.input-group { 
  width: 22%;
  margin-bottom: 15px;
}
</style>