<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$objeto = new Database();
$conexion = $objeto->getConnection();
$conexion = $objeto->getConnection();

$token_session = isset($_SESSION['token']) ? $_SESSION['token'] : null;

if ($token_session === null) {
    if (isset($_SESSION['user_cliente'])) {
        header("Location: compras.php");
    } else {
        header("Location: login.php?compra");
    }
    exit;
}
$orde = isset($_GET['order']) ? $_GET['order'] : null;
$token = isset($_GET['token']) ? $_GET['token'] : null;

if ($orde == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}

$consulta = "SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ?";
$resultado = $conexion->prepare($consulta);
$resultado->execute([$orde]);
$row_compra = $resultado->fetch(PDO::FETCH_ASSOC);
$idCompra = $row_compra['id'];

$fecha = new DateTime($row_compra['fecha']);
$fecha = $fecha->format('d-m-Y H:i:s');

$consultaDetalle = "SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?";
$resultado2 = $conexion->prepare($consultaDetalle);
$resultado2->execute([$idCompra]);

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
</head>

<body>
<?php include '../Views/navbar.php'; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong><?php echo $fecha; ?></p>
                            <p><strong>Orden: </strong><?php echo $row_compra['id_transaccion']; ?></p>
                            <p><strong>Total: </strong><?php echo MONEDA . ' ' . number_format($row_compra['total'],2,'.',','); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php   
                                while($row = $resultado2->fetch(PDO::FETCH_ASSOC)) {
                                    $precio=$row['precio'];
                                    $cantidad=$row['cantidad'];
                                    $subtotal=$precio*$cantidad;
                                    ?>
                                    <tr>
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><?php echo MONEDA . ' ' . number_format($precio,2,'.',','); ?></td>
                                        <td><?php echo $cantidad; ?></td>
                                        <td><?php echo  MONEDA . ' ' . number_format($subtotal,2,'.',',');; ?></td>
                                    </tr>
                                    <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <?php include '../Views/footer.php'; ?>

    <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../JS/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
</body>

</html>