<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
  // Redireccionar al formulario de inicio de sesión si no hay una sesión activa
  header("Location: ../Views/adminLogin.php");
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

  <style>
    .search-form {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="container mt-3">
    <div class="row justify-content-md-center">
      <div class="col-md-12">
        <a href="./"><i class="bi bi-house"></i></a>


        <?php
        if( $_SESSION['rol'] != 'empleado'){
        ?>
        <a href="usuarios.php"><i class="bi bi-person"></i></a>
        <?php
        }
        ?>



        <a href="../Views/CerrarSesion.php"><i class="bi bi-box-arrow-right"></i></a>
        <hr class="mb-3">

        <div class="jumbotron jumbotron-fluid custom-jumbotron text-center">
          <div class="container1">
            <h1 class="display-4">Bienvenido, <?php echo $_SESSION['username']; ?></h1>
            <p class="lead">En esta sección puedes gestionar los productos de la plataforma.</p>
            <hr class="mb-3">
          </div>
        </div>





      </div>


 


      <div class="col-md-4 mb-3">
        <h3 class="text-center">Datos del Producto</h3>
        <form method="POST" action="action.php" enctype="multipart/form-data">
          <input type="text" name="metodo" value="1" hidden>
          <div class="mb-3">
            <label class="form-label">Nombre del producto</label>
            <input type="text" class="form-control" name="nombre" maxlength="50" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción:</label>
            <input type="text" class="form-control" name="descripcion" required maxlength="50" required>
          </div>
          <div class="mb-3">
  <label class="form-label">Precio:</label>
  <input type="number" name="precio" class="form-control" min="0" max="1000000" step="0.01" oninput="limitarRango(this)" required>
</div>

<div class="mb-3">
  <label class="form-label">Descuento:</label>
  <input type="number" name="descuento" class="form-control" min="0" max="100" oninput="limitarDescuento(this)" required>
</div>

<div class="mb-3">
  <label class="form-label">Categoría:</label>
  <input type="number" name="categoria" class="form-control" min="1" max="3" oninput="limitarCategoria(this)"  placeholder="1-jardineria, 2-hogar, 3-carpinteria"  required>
</div>

<div class="mb-3">
  <label class="form-label">Stock:</label>
  <input type="number" name="stock" class="form-control" min="1" max="999" oninput="limitarStock(this)" required>
</div>

<div class="mb-3">
  <label class="form-label">Activo:</label>
  <input type="number" name="activo" class="form-control" placeholder="1.-SI , 2.- NO" min="1" max="2" oninput="limitarActivo(this)" required>
</div>

          <div class="mb-3">
            <label for="formFile" class="form-label">Foto del Producto</label>
            <input class="form-control" type="file" name="foto" accept="image/png,image/jpeg" required>
          </div>
          <div class="d-grid gap-2 col-12 mx-auto">
            <button type="submit" class="btn btn-primary mt-3 mb-2">
              Registrar nuevo Producto
              <i class="bi bi-arrow-right-circle"></i>
            </button>
          </div>
        </form>
      </div>

      <?php
      require_once('config.php');
      $conn = (new Database())->getConnection();
      $validar = $_SESSION['username'];
      if ($validar == null || $validar = '') {

        header("Location: ../Views/adminLogin.php");
        die();
      }


      $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

      $sqlProductos = "SELECT * FROM productos WHERE nombre LIKE :searchTerm ORDER BY id DESC";
      $queryProductos = $conn->prepare($sqlProductos);
      $queryProductos->bindValue(':searchTerm', '%' . $searchTerm . '%');
      $queryProductos->execute();
      $totalProductos = $queryProductos->rowCount();
      ?>

      <div class="col-md-8">
        <h3 class="text-center">Lista de Productos <?php echo '(' . $totalProductos . ')'; ?></h3>
        <div class="row">
          <div class="col-md-12 p-2">
            <div class="search-form">
              <form method="GET" action="index.php">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Buscar producto" name="search" value="<?php echo $searchTerm; ?>">
                  <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i> Buscar
                  </button>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Activo</th>
                    <th scope="col">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $conteo = 1;
                  while ($dataProductos = $queryProductos->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                      <td><?php echo $conteo++ . ')'; ?></td>
                      <td><?php echo $dataProductos['nombre']; ?></td>
                      <td><?php echo $dataProductos['stock']; ?></td>
                      <td><?php echo $dataProductos['activo']; ?></td>
                      <td>
                        <a href="formEditar.php?id=<?php echo $dataProductos['id']; ?>" class="btn btn-info mb-2" title="Actualizar datos del producto <?php echo $dataProductos['nombre']; ?>">
                          <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </a>
                        <a href="#" class="btn btn-danger mb-2 confirmation-modal" title="Borrar el producto <?php echo $dataProductos['nombre']; ?>" data-product-id="<?php echo $dataProductos['id']; ?>" data-name-photo="<?php echo $dataProductos['foto']; ?>" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                          <i class="bi bi-trash"></i> Borrar
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- Modal de confirmación de eliminación -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-dark" style="font-size: 14px;">¿Estás seguro de que deseas eliminar este producto?</p> <!-- Agrega el estilo "font-size" para reducir el tamaño de fuente -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" id="deleteProductLink" class="btn btn-danger">Eliminar</a>
        </div>
      </div>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script>
function limitarActivo(input) {
  if (input.value < 1) {
    input.value = 1;
  } else if (input.value > 2) {
    input.value = 2;
  }
}


function limitarStock(input) {
  if (input.value < 1) {
    input.value = 1;
  } else if (input.value > 999) {
    input.value = 999;
  }
}

function limitarCategoria(input) {
  if (input.value < 1) {
    input.value = 1;
  } else if (input.value > 3) {
    input.value = 3;
  }
}


function limitarDescuento(input) {
  if (input.value < 0) {
    input.value = 0;
  } else if (input.value > 100) {
    input.value = 100;
  }
}



function limitarRango(input) {
  if (input.value < 0) {
    input.value = 0;
  } else if (input.value > 1000000) {
    input.value = 1000000;
  }
}


    $(function() {

      // Capturar el clic en el enlace de eliminación del producto y actualizar el enlace del modal de confirmación
      $('.confirmation-modal').on('click', function() {
        var productId = $(this).data('product-id');
        var namePhoto = $(this).data('name-photo');
        var deleteUrl = 'action.php?id=' + productId + '&metodo=3&namePhoto=' + namePhoto;
        $('#deleteProductLink').attr('href', deleteUrl);
      });
    });
  </script>
</body>

</html>