<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  // Redireccionar al formulario de inicio de sesión si no hay una sesión activa
  header("Location: ../Views/adminLogin.php");
  exit;
}



if ($_SESSION['rol'] != 'administrador') {
  // Redireccionar a una página de acceso no autorizado si el usuario no es administrador
  header("Location: ../admin/index.php");
  exit;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/home.css">
  <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon" id="logo">

</head>

<body>
  <div class="container mt-3">
    <div class="row justify-content-md-center">
      <div class="col-md-12">
        <a href="./"><i class="bi bi-house"></i></a>

        <a href="../Views/CerrarSesion.php"><i class="bi bi-box-arrow-right"></i></a>
        <hr class="mb-3">

        <div class="jumbotron jumbotron-fluid custom-jumbotron text-center">
          <div class="container1">
            <h1 class="display-4">Bienvenido, <?php echo $_SESSION['username']; ?></h1>
            <p class="lead">En esta sección puedes gestionar los usuarios de la plataforma.</p>
            <hr class="mb-3">
          </div>
        </div>
      </div>
      

      <div class="col-md-4 mb-3">
        <h3 class="text-center">Datos del Usuario</h3>
        <form action="action.php?metodo=4" method="POST">
          <div class="form-group">
            <label for="nombre" class="form-label">Nombre Usuario*</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="8" required>
          </div>
          <div class="form-group">
            <label for="username">Correo:</label><br>
            <input type="email" name="correo" id="correo" class="form-control" placeholder="" maxlength="50" required>
          </div>
          <div class="form-group">
            <label for="password">Contraseña:</label><br>
            <input type="password" name="password" id="password" class="form-control" required maxlength="20" required>
          </div>
          <div class="form-group">
    <label for="rol" class="form-label">Rol de usuario *</label>
    <input type="number" id="rol" name="rol" class="form-control" required placeholder="Escribe el rol, 1 admin, 2 User.." min="1" max="2" oninput="limitarValor(this)">
</div>


          <div class="d-grid gap-2 col-12 mx-auto">
            <button type="submit" class="btn btn-primary mt-3 mb-2">
              Registrar nuevo Usuario
              <i class="bi bi-arrow-right-circle"></i>
            </button>
          </div>
        </form>
      </div>

      <?php
      require_once('config.php');
      $conn = (new Database())->getConnection();

      $sqlUsuarios = "SELECT * FROM usuarios where elimina!=1 ORDER BY id_usuario DESC";
      $queryUsuarios = $conn->prepare($sqlUsuarios);
      $queryUsuarios->execute();
      $totalUsuarios = $queryUsuarios->rowCount();
      ?>

      <div class="col-md-8">
        <h3 class="text-center">Lista de Usuarios <?php echo '(' . $totalUsuarios . ')'; ?></h3>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th scope="col">Nombre Usuario</th>
                <th scope="col">Correo</th>
                <th scope="col">Tipo de usuario</th>
                <th scope="col">Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $conteo = 1;
              while ($dataUsuarios = $queryUsuarios->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                  <td><?php echo $conteo++ . ')'; ?></td>
                  <td><?php echo $dataUsuarios['nombre_usuario']; ?></td>
                  <td><?php echo $dataUsuarios['correo_electronico']; ?></td>
                  <td><?php echo $dataUsuarios['tipo_usuario']; ?></td>
                  <td>
                    <a href="formEditaruser.php?id=<?php echo $dataUsuarios['id_usuario']; ?>" class="btn btn-info mb-2" title="Actualizar datos del producto <?php echo $dataUsuarios['nombre_usuario']; ?>">
                      <i class="bi bi-arrow-clockwise"></i> Actualizar
                    </a>
                    <a href="#" class="btn btn-danger mb-2 confirmation-modal" title="Borrar el usuario <?php echo $dataUsuarios['nombre_usuario']; ?>" data-user-id="<?php echo $dataUsuarios['id_usuario']; ?>" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                      <i class="bi bi-trash"></i> Borrar
                    </a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal de confirmación de eliminación -->
      <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-dark" style="font-size: 14px;">¿Estás seguro de que deseas eliminar este usuario?</p> <!-- Agrega el estilo "font-size" para reducir el tamaño de fuente -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <a id="confirmDeleteLink" href="#" class="btn btn-danger">Eliminar</a>
            </div>
          </div>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
      <script>
         function limitarValor(input) {
        if (input.value < 1) {
            input.value = 1;
        } else if (input.value > 2) {
            input.value = 2;
        }
    }
        $(function() {


          // Capturar el clic en el enlace de eliminación del usuario y actualizar el enlace del modal de confirmación
          $('.confirmation-modal').on('click', function() {
            var userId = $(this).data('user-id');
            var deleteUrl = 'action.php?metodo=6&id=' + userId;
            $('#confirmDeleteLink').attr('href', deleteUrl);
          });
        });
      </script>


</body>

</html>