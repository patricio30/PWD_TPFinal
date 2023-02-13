<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link rel="stylesheet" href="./bootstrap-5.1.3-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <title>Inicio - EyeShop</title>
</head>

<body>
    <!--nav class="navbar bg-primary navbar-expand-lg navbar-light py-4 text-center"-->

    <nav class="navbar bg-primary navbar-expand-lg py-4">     
        <div class="container px-4 px-lg-1">
            <!--h1 class="text-white ms-lg-4">EyeShop</h1-->
            <h1 class="text-white">EyeShop</h1>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <a href="registrarse.php" title="Registrarse" style="text-decoration: none;">
                <button class="botonMenup">Registrarse</button>
            </a>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <a href="../sesion/iniciarSesion.php" title="Iniciar sesión" style="text-decoration: none;">
                <button class="botonMenup">Iniciar Sesión</button>
            </a>
        </ul>
    </nav>
    

    <section><br>
        <!--div class="container text-center mt-4"-->
        <!--div class="container text-center" id="mycontainer"><br-->
        <div class="container" id="mycontainer">
            <img src="./assets/fondo4.jpg" class="img-fluid">
        </div>

        
        <div class="container" id="mycontainer">
            <h1 class="fw-bolder text-center py-5">Quiénes Somos?</h1>
        </div>
        
        <div class="opacity-bg text-center container" id="mycontainer">
            <h4>Somos una pequeña empresa de ventas de anteojos y suplementos para los mismos. <br>
                Desde hace ya 10 años, nos enfocamos exclusivamente en marcas economicas que ofrecen calidad/precio,
                como Spy+, Rip Curl y Reef. Si usted está buscando anteojos de sol para este verano, este es su sitio de
                confianza, aqui tendrá una buena financiación y se acepta incluso tarjetas prepagas. Si necesita de un
                cambio de lente de sus anteojos o un marco nuevo, aqui tendrá una gran variedad, tanto en aspecto como
                en material.</h4>
        </div>
    </section>
    
    <footer class="py-3 mt-5 bg-dark">
        <div class="container" id="mycontainer">
            <p class="m-0 text-center text-white">Patricio Rubio</p>
            <p class="m-0 text-center text-white">Copyright &copy; Grupo N°3 2022</p>
        </div>
    </footer>

    <script src="./bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
</body>

</html>



<style type="text/css">

#mycontainer { max-width: 1300px !important; }

.botonMenup{
  color: #0d6efd;
  background-color: white;
  border: 2px solid;
  border-radius: 10px;
  height: 40px;
  width: 125px;
  font-size: 18px;
  margin-right: 10px;
  text-align: center;
}
.botonMenup:hover{
  color: white;
  background-color: #0d6efd;
}
</style>