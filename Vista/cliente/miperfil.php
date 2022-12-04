<?php
include_once "../../configuracion.php";
include_once("../estructura/cabecera.php");
if($resp){
  $nombreUsuario = $_SESSION['usnombre'];

  $objUsuario = new AbmUsuario;
  $array = ["usnombre" => $nombreUsuario];
  $objUsuario2 = $objUsuario->buscar($array);

//Verifica si tiene acceso este rol
$link = $_SERVER['REQUEST_URI'];
if($objTrans->tieneAcceso($objMenus, $link)){

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo "Mi perfil"?></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../css/estilos.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js" ></script>
</head>

<body class="fondo"><br><br>
<div class="container" align="center">

    <span class="titulo2"><?php echo "MI PERFIL" ?></span><br><br>
    <form method="post" id="formularioProducto" enctype="multipart/form-data">

        <input id="accion" name ="accion" value="<?php echo "editarPerfil" ?>" type="hidden">
        <input id="idusuario" name="idusuario" value="<?php echo $objUsuario2[0]->getIdusuario() ?>" type="hidden">

        <label class="subtitulo">Nombre *</label><br/>
        <div class="input-group mx-auto">
        <input type="text" id="usnombre" name="usnombre" class="form-control" maxlength="50" style="text-transform: uppercase; border-color: #3498DB" value="<?php echo $objUsuario2[0]->getUsnombre();?>" readonly>
        </div>

        <label class="subtitulo">Mail *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="usmail" name="usmail" class="form-control" maxlength="50" style="text-transform: uppercase; border-color: #3498DB" value="<?php echo $objUsuario2[0]->getUsmail();?>">
        </div>

        <label class="subtitulo">Clave *</label><br/>
        <div class="input-group mx-auto">
        <input type="password" id="uspass" name="uspass" class="form-control" maxlength="150" style="text-transform: uppercase; border-color: #3498DB" value="<?php echo $objUsuario2[0]->getUspass();?>">
        </div>

        <div class="form-group col-md-12">
          <div class="float-left" style="color:black">* Datos obligatorios</div>
          <button class="btn btn-responsive float-right" type="submit" id="botonActualizar" title="Actualizar">Actualizar</button><br>
        </div>
    </form><br><br>
</div>
</div>
</body>
</html>
<?php
  }else{ ?>
      <br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php" />
 <?php   
    }
  }
  else{ 
  ?>
    <meta http-equiv="refresh" content="0;url=../pagina/index.php" />
 <?php   
    }
?>



<script type="text/javascript">

$('#botonActualizar').click(function(evento){
    pregunta = '¿Desea cambiar su perfil?';
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
                var datos = new FormData($('#formularioProducto')[0]);
                $.ajax({
                    //url: 'cargarProducto.php',
                    url: 'accion.php',
                    type:'POST',
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function(data){
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
    }).then(function(){
        window.location.replace("../principal/home.php");
    });
  }
</script>



<style type="text/css">
.input-group { 
  width: 35%;
  margin-bottom: 15px;
}
</style>