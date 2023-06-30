<?php
require_once('config.php');
$objeto = new Database();
$conn = $objeto->getConnection();

$id = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
$stmt = $conn->prepare("SELECT * FROM productos WHERE id=:id LIMIT 1");
$stmt->bindParam(':id', $id);
$stmt->execute();
$dataProductos = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/home.css">
  <!-- https://icons.getbootstrap.com/ -->
  <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon" id="logo">

</head>

<body>

  <div class="container mt-3">
    <div class="row justify-content-md-center">
      <div class="col-md-12">
        <h1 class="text-center mt-3">
          <a href="./">
            <i class="bi bi-arrow-left-circle"></i>
          </a>
          Actualizar Datos del Producto
        </h1>
        <hr class="mb-3">
      </div>


      <div class="col-md-5 mb-3">
        <h3 class="text-center">Datos del Producto</h3>
        <form method="POST" action="action.php?metodo=2" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $dataProductos['id']; ?>">
          <div class="col-md-5 mb-3">
            <label class="form-label">Foto actual del Producto</label>
            <br>
            <img src="fotosProductos/<?php echo $dataProductos['foto']; ?>" alt="foto perfil" class="card-img-top fotoPerfil">
          </div>
         
 
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $dataProductos['nombre']; ?>">
          </div>
          <div class="mb-3">
  <label class="form-label">Stock:</label>
  <input type="number" name="stock" class="form-control" id="stock" value="<?php echo $dataProductos['stock']; ?>" step="1" oninput="limitarValor(this, 1, 999)">
</div>

<div class="mb-3">
  <label class="form-label">Precio:</label>
  <input type="number" name="precio" class="form-control" id="precio" value="<?php echo $dataProductos['precio']; ?>" step="any" oninput="limitarValor(this, 0, 1000000)">
</div>

<div class="mb-3">
  <label class="form-label">Descuento:</label>
  <input type="number" name="descuento" class="form-control" id="descuento" value="<?php echo $dataProductos['descuento']; ?>" step="any" oninput="limitarValor(this, 0, 100)">
</div>

<div class="mb-3">
  <label class="form-label">Activo:</label>
  <input type="number" name="activo" class="form-control" id="activo" value="<?php echo $dataProductos['activo']; ?>" step="1" oninput="limitarValor(this, 1, 2)">
</div>


            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <input type="text" class="form-control" name="descripcion" id="descripcion" value="<?php echo $dataProductos['Descripcion']; ?>">
            </div>

            <div class="mb-3">
              <label for="formFile" class="form-label">Foto del Producto</label>
              <input class="form-control" type="file" name="foto" accept="image/png,image/jpeg">
            </div>

            <div class="d-grid gap-2 col-12 mx-auto">
              <button type="submit" class="btn btn-primary mt-3 mb-2">
                Actualizar datos del Producto
                <i class="bi bi-arrow-right-circle"></i>
              </button>
            </div>
          </div>
      </div>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>

function limitarValor(input, min, max) {
  if (input.value < min) {
    input.value = min;
  } else if (input.value > max) {
    input.value = max;
  }
}

      $(function() {
        $('.toast').toast('show');
      });
    </script>

</body>

</html>