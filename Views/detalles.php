<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
$objeto = new Database();
$conexion = $objeto->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';


if ($id == '' || $token == '') {
    echo 'Error al procesar la peticion';
    exit;
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {

        $consulta = "SELECT count(id) from productos where id=? and activo=1";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute([$id]);
        if ($resultado->fetchColumn() > 0) {

            $consulta = "SELECT  nombre, descripcion, precio, descuento, stock,foto from productos where  id=? and activo=1
            limit 1";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id]);
            $data = $resultado->fetch(PDO::FETCH_ASSOC);
            $nombre = $data['nombre'];
            $descripcion = $data['descripcion'];
            $precio = $data['precio'];
            $descuento = $data['descuento'];
            $stock = $data['stock'];
            $foto = $data['foto'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
        }
    } else {
        echo 'Error al procesar la peticion';
        exit;
    }
}



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
    <style>
        .card {
            height: 100%;
        }

        .card-img-top {
            object-fit: contain;
            max-height: 400px;
            /* Ajusta el tamaño máximo de la imagen según tus necesidades */
        }
    </style>

</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    </header>

    <!-- contenido de los productos -->
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <img src="../admin/fotosProductos/<?php echo $foto; ?>" class="card-img-top">
                </div>
                <div class="col-md-6 order-md-2">
                    <h2><?php echo $nombre; ?></h2>
                    <?php if ($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                        <h2>
                            <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                            <small class="text-success"> <?php echo  $descuento; ?>% descuento</small>
                        </h2>

                    <?php } else { ?>
                        <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                    <?php } ?>

                    <p class="ui-pdp-description__content" id="label">

                        <?php echo $descripcion; ?>
                    <p class="card-text" id="stok" name=""><strong>Stock: </strong> <?php echo $stock; ?>
                    </p>
                    <div class="col-3 my-3">
                        Cantidad: <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" max="10" value="1">

                    </div>
                   



                    <div class="d-grid gap-3 col-10 mx-auto">
                      <a href="../Views/checkout.php" style="color: white; text-decoration: none;">
        <button class="btn btn-primary " style="width: 100%;" type="button" onclick="addProducto(<?php echo $id; ?>, cantidad.value, <?php echo $stock; ?>,'<?php echo $token_tmp; ?>')">Comprar ahora</button>
    </a>
                        <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, cantidad.value, <?php echo $stock; ?>,'<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include '../Views/footer.php'; ?>

    <script>
    function addProducto(id, cantidad, stock, token) {
        let url = '../clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('cantidad', cantidad);
        formData.append('stock', stock);
        formData.append('token', token);
        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        })
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                let elemento = document.getElementById("num_cart");
                elemento.innerHTML = data.numero;
               
            } else {
                alert(data.mensaje); // Mostrar el mensaje de error en un alert
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>




    <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../JS/scripts.js"></script>
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>