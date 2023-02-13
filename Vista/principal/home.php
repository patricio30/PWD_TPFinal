<?php
include_once("../estructura/cabecera.php");
if($resp){
?>

<!DOCTYPE html>
<html lang="es">
<body>
  <div class="container" id="mycontainer"><br>
      <img src="../pagina/assets/fondo6.jpg" class="img-fluid">
    </div><br>
<?php 
}
else{
  header('Location: ../index.php');
}
?>
</body>
</html>

<style type="text/css">
  #mycontainer { max-width: 1300px !important; }
</style>
