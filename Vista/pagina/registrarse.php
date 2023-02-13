<?php
include_once '../../configuracion.php';
$objTrans = new Session();
$resp = $objTrans->validar();
if(!$resp){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../css/estilos.css?v7">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../js/dataTables.bootstrap5.min.js"></script> 
  </head>
<body class="fondo"><br><br>
  
<div class="container" align="center" id="mycontainer"><br>
  <div><span class="titulo2">REGISTRACIÓN DE USUARIO</span></div><br>

  	<form method="post" id="formularioProducto" enctype="multipart/form-data">

        <input id="accion" name ="accion" value="<?php echo "registrarse" ?>" type="hidden">

        <label class="subtitulo">Nombre *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="usnombre" name="usnombre" class="form-control" maxlength="50" style="text-transform: uppercase; border-color: #3498DB" autocomplete="off">
        </div>

        <label class="subtitulo">Mail *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="usmail" name="usmail" class="form-control" maxlength="50" style="text-transform: uppercase; border-color: #3498DB" autocomplete="off">
        </div>

        <label class="subtitulo">Clave *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="uspass" name="uspass" class="form-control" maxlength="150" style="text-transform: uppercase; border-color: #3498DB" autocomplete="off">
        </div>

        <label class="subtitulo">Reingresar Clave*</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="uspassR" name="uspassR" class="form-control" maxlength="150" style="text-transform: uppercase; border-color: #3498DB" autocomplete="off">
        </div>

        <div class="form-group col-md-12">
          <button class="btn btn-responsive float-right" type="submit" id="botonRegistrarse" title="Registrarse">Registrarse</button><br>
          <div class="float-left" style="color:red">* Datos obligatorios</div>
          <a class="botonVolver" href="index.php">Volver</a>
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



<style type="text/css">
.input-group { 
  width: 27%;
  margin-bottom: 15px;
}
</style>

<script type="text/javascript">
$('#botonRegistrarse').click(function(evento){
    pregunta = '¿Desea darse de alta?';
    evento.preventDefault();
    if(validarFormulario()){
        Swal.fire({
            title: pregunta,
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value){
              var password = document.getElementById("uspass").value;
              var hash = hex_md5(password).toString();
              document.getElementById("uspass").value = hash;
              var datos = new FormData($('#formularioProducto')[0]);
                $.ajax({
                    url: '../usuario/accion.php',
                    type:'POST',
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        //console.log(data);
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
});

function validarFormulario(){

    if($("#usnombre").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar NOMBRE");
      $("#usnombre").focus();
      return false;
    }

    if($("#usmail").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar MAIL");
      $("#usmail").focus();
      return false;
    }
    else{
        if(!$("#usmail").val().includes('@')){
          return mensajeError("Error", "Formato de MAIL erroneo");
          $("#usmail").focus(); 
          return false;
        }
    }

    if($("#uspass").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar CLAVE");
      $("#uspass").focus();
      return false;
    }
    if($("#uspassR").val() == ""){
      return mensajeError("Faltan datos", "Debe reingresar CLAVE");
      $("#uspassR").focus();
      return false;
    }

    if($("#uspass").val()!=$("#uspassR").val()){
      return mensajeError("Error", "Las contraseñas no coinciden");
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
      width:'550px',
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
      width:'550px',
      allowOutsideClick: false,
    }).then(function(){
        document.getElementById("formularioProducto").reset();
        window.location.replace("index.php");
    });
    return false;
  }
</script>
 <script src="../js/md5.js"></script>