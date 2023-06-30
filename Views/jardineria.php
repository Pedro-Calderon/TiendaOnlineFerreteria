<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
$objeto = new Database();
$conexion = $objeto->getConnection();
$consulta = "SELECT id, nombre, precio, stock, descuento, foto FROM productos WHERE activo = 1 and id_categoria=1";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>The Miner's Armory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="../CSS/estilos.css">
  <link rel="stylesheet" type="text/css" href="../CSS/estiloCatalogos.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon" id="logo">

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      height: 100%;
    }

    .card-img-top {
      object-fit: contain;
      height: 300px; /* Ajusta el tamaño de la imagen según tus necesidades */
    }
  </style>
</head>

<body>
  <header>
    <?php include 'navbar.php'; ?>
  </header>

  <main>
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach ($productos as $producto) : ?>
          <?php if ($producto['stock'] > 0) : ?>
            <div class="col">
              <div class="card shadow-sm">
                <img src="../admin/fotosProductos/<?php echo $producto['foto']; ?>" class="card-img-top">

                <div class="card-body">
                  <p class="card-title">
                    <h4><?php echo $producto['nombre']; ?></h4>
                  </p>
                  <p class="card-text">$ <?php echo number_format($producto['precio'], 2, '.', ','); ?></p>
                  <p class="card-text"><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="../Views/detalles.php?id=<?php echo $producto['id']; ?>&token=<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                    </div>
                    <button class="btn btn-outline-success" type="button" <?php echo ($producto['stock'] > 0) ? '' : 'disabled'; ?> onclick="addProducto(<?php echo $producto['id']; ?>,<?php echo $producto['stock']; ?>,'<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <?php include '../Views/footer.php'; ?>

  <script>
    function addProducto(id, stock, token) {
      let url = '../clases/carrito.php'
      let formData = new FormData()
      formData.append('id', id)
      formData.append('stock', stock)
      formData.append('token', token)

      fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
      }).then(response => response.json())
        .then(data => {
          if (data.ok) {
            let elemento = document.getElementById("num_cart")
            elemento.innerHTML = data.numero
          }
        })
    }
  </script>
  <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>
  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="../JS/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
</body>

</html>
