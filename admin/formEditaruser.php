<?php
require_once('config.php');
$objeto = new Database();
$conn = $objeto->getConnection();

$id = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario=:id LIMIT 1");
$stmt->bindParam(':id', $id);
$stmt->execute();
$dataUsuarios = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/home.css">
  <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon" id="logo">
  <style>
   
  </style>
</head>

<body>

  <div class="container mt-3">
    <div class="row justify-content-md-center">
      <div class="col-md-12">
        <h1 class="text-center mt-3">
          <a href="usuarios.php">
            <i class="bi bi-arrow-left-circle"></i>
          </a>
          Actualizar Datos del Usuario
        </h1>
        <hr class="mb-3">
      </div>


      <div class="col-md-5 mb-3">
        <h3 class="text-center">Datos del Usuario</h3>
        <form action="action.php?metodo=5" method="POST">
        <input type="hidden" name="id" value="<?php echo $dataUsuarios['id_usuario']; ?>">

          <div class="form-group">
            <label for="nombre" class="form-label">Nombre Usuario*</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="8" >
          </div>
          <div class="form-group">
            <label for="correo">Correo:</label><br>
            <input type="email" name="correo" id="correo" class="form-control" placeholder="" maxlength="50" >
          </div>
          <div class="form-group">
            <label for="password">Contraseña:</label><br>
            <input type="password" name="password" id="password" class="form-control"  maxlength="20">
          </div>
          <div class="form-group">
  <label for="rol" class="form-label">Rol de usuario *</label>
  <input type="number" id="rol" name="rol" class="form-control" placeholder="Escribe el rol, 1 admin, 2 User.." value="<?php echo $dataUsuarios['tipo_usuario']; ?>" oninput="limitarRol()" min="1" max="2">
</div>

          <div class="d-grid gap-2 col-12 mx-auto">
            <button type="submit" class="btn btn-primary mt-3 mb-2">
              Actualizar Usuario
              <i class="bi bi-arrow-right-circle"></i>
            </button>
          </div>


        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>

function limitarRol() {
  var input = document.getElementById("rol");
  if (input.value < 1) {
    input.value = 1;
  } else if (input.value > 2) {
    input.value = 2;
  }
}

      $(function() {
        $('.toast').toast('show');
      });
    </script>

</body>

</html>
