<?php
include_once '../../configuracion.php';

$objTrans = new Session();
$resp = $objTrans->validar();
if($resp){
  //echo "Tiene sesión abierta";
  $usuario = $objTrans->getUsuario()->getUsnombre();
  $mail = $objTrans->getUsuario()->getUsmail();
  //$conSession = true;
  $roles = $objTrans->getRoles();//Obtengo los roles del usuario
  $objRol = new AbmRol();
  $objRoles = $objRol->obtenerObjeto($roles); //OBTENGO UN ARRAY DE OBJETOS ROLES
  $objMenuRol = new AbmMenurol();
  $objMenus = $objMenuRol->menusRol($objRoles); //OBTENGO UN ARRAY DE MENUS
  //print_r($objMenus);
  //$objSession = new Session();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../css/estilos.css?v21">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="../js/dataTables.bootstrap5.min.js"></script> 
  </head>
<body class="fondo"><br><br>
  
<div class="container" id="mycontainer">
     
     <nav class="navbar bg-primary navbar-expand-lg navbar-light py-4">
 
      <!--div class="container px-4 px-lg-5"-->
            <h1 class=" text-white ms-lg-1" style="margin-right: 50px">EyeShop</h1>
            
      <?php //if($conSession){  
        ?>
            <!--div class="collapse navbar-collapse" id="navbarNav"-->
            <a href="../principal/home.php"><button class="btn" type="button" id="botonMenu">Home</button></a>

          <?php
          foreach ($objMenus as $objMenu){
          ?>
              <a href="<?php echo $objMenu->getMedescripcion(); ?>"><button class="btn" type="button" id="botonMenu"><?php echo $objMenu->getMenombre();?></button></a>
          <?php
          }
          ?>

          <button class="btn" type="button" id="botonUsuario">
              <span style="color: white;"><?php echo $usuario?></span>
          </button>
          <button class="btn" type="button" id="botonSalir" title="cerrar">Salir</button>


 <?php //}else{?>
              <div style="text-align: right;">
                <a href='../sesion/iniciarSesion.php'>INGRESAR</a>
              </div>
<?php //} ?>

  </nav>
  </div>
  </body>
  </html>
<?php
  } else{
?>
<body class="fondo"><br><br>
  <div class="container" id="mycontainer">
  </div>
</body>
<?php }
?>


<script type="text/javascript">

$('#botonSalir').click(function(evento){
    pregunta = '¿Desea cerrar sesión?';
    evento.preventDefault();
        Swal.fire({
            title: pregunta,
            width:'550px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value){
                $.ajax({
                    type:'POST',
                    url: '../estructura/cerrarSesion.php',
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
            }
        });
});


  function mensajeError($mensaje1, $mensaje2){
    swal.fire({
      type: 'error',
      title: $mensaje1,
      text: $mensaje2,
      allowOutsideClick: false,
      width:'550px',
      imageWidth: '160px',
    }).then(function(){
        window.location.replace("../pagina/index.php");
    });
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
        window.location.replace("../pagina/index.php");
    });
  }
</script>


<style type="text/css">
  #mycontainer { max-width: 1300px !important; }

#botonSalir{
  color: white; 
  background-color: red;
  height: 45px;
  width: 85px;
  font-size: 16px;
  font-family: Georgia;
}

#botonSalir:hover{
  color: red; 
  background-color: white;
  border-color: red;
}


#botonMenu{
  color: #5DADE2; 
  background-color: white;
  height: 45px;
  width: 100px;
  font-size: 13px;
  font-family: Arial;
  margin-right: 1px; 
}

#botonMenu:hover{
  color: white; 
  background-color: #5DADE2;
  border-color: #5DADE2;
}

#botonUsuario{
  color: white; 
  background-color: #3498DB;
  height: 45px;
  width: 85px;
  font-size: 15px;
  font-family: Georgia;
}
</style>