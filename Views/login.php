<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

$objeto = new Database();
$conexion = $objeto->getConnection();

$proceso = isset($_GET['pago']) ? 'pago' : (isset($_GET['compra']) ? 'compra' : 'login');

$errores = [];

if (!empty($_POST)) {
 
  $usuario = trim($_POST['usuario']);
  $password = trim($_POST['password']);
  $proceso=$_POST['proceso']??'login';
 
  if ((esNulo([$usuario, $password]))) {
    $errores[] = "Debe llenar todos los campos";
 
  }
  if(count($errores)==0){
  $errores[]=login($usuario,$password,$conexion,$proceso);
}

}

?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="../CSS/estiloLogin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-REaCXNO5teq0r9WRBr9qz+drqH9ObT1lG4DlcVrJG3rr8AqOSAaaad7A3eB4gCL+qJZmP0T+amwJ1mCGQ8bVmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" integrity="sha512-6IcH/B0gQ+VzUCFToD6n3Q6ZvC9G1NE/3WQhdGze6xdDkmUQmG2fdQ+edfNajZe/7j17UbjSvCX2IbG2DqMSKw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Hojas de estilo de Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Biblioteca de iconos Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Custom fonts for this template-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Google Sign-In library -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <!-- Google Sign-In API -->
  <script src="https://apis.google.com/js/platform.js" async defer></script>
</head>

<body>
  <div class="login-form">
    <?php mostarMensajes($errores); ?>

    <form method="post" id="a" autocomplete="off">


    <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

      <h2 class="text-center">
        <img src="../recursos/engrana.gif" alt="Inicio de Sesión" />
      </h2>
      <h2 class="text-center">Inicio de Sesión</h2>

      <div class="form-group">
        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario" minlength="1" maxlength="50" required>
        
      </div>

      <div class="form-group">
        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" minlength="1" maxlength="50" required>
      </div>

      <button type="submit" class="btn btn-primary">Iniciar sesión</button>
      <hr>


      <div class="text-center">
        <a class="small" href="forgot-password.php">¿Olvidaste tu contraseña?</a>
      </div>

      <div class="text-center">
      <a class="small" href="registro.php">¡Crear una cuenta!</a>
      </div>
      <hr>
      <div class="text-center">
        <a class="small" href="adminLogin.php">Admin</a>
      </div>

    </form>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-wIbZa5nXGf5lOQxigAxzUp5ajS+Eh14Cx7CqmOMz6UIk2FWw+YIty9gx+8fZaHN7tP1gJ3hXLoId6eClXePYmQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js" integrity="sha512-z3tQy2LSj7bOpKUpEPEwbzGgM6mFAumrt1uYtng1jItvrNSBp7rxdiED+nnpzANClXv7NXXA8uyFzqj6N0wakQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  
</body>

</html>