<?php

include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
$objeto = new Database();
$conexion = $objeto->getConnection();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : 0;

$error = '';
if ($id_transaccion == '') {
    $error = 'Error al procesar la peticion';
} else {

    $consulta = "SELECT count(id) from compra where id_transaccion=? and status=?";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute([$id_transaccion, 'COMPLETED']);
    if ($resultado->fetchColumn() > 0) {

        $consulta = "SELECT  id, fecha, email, total from compra where id_transaccion=? and status=?
        limit 1";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute([$id_transaccion, 'COMPLETED']);
        $data = $resultado->fetch(PDO::FETCH_ASSOC);

        $idCompra = $data['id'];
        $total = $data['total'];
        $fecha = $data['fecha'];

        $sqlDet = "SELECT  nombre,precio,cantidad from detalle_compra where id_compra=?";
        $resultado2 = $conexion->prepare($sqlDet);
        $resultado2->execute([$idCompra]);
    } else {
        $error = 'Error al ccomprobar la ccompra';
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


</head>

<body>
    <?php include '../Views/navbar.php'; ?>
    </header>

    <!-- contenido de los productos -->
    <main>
        <div class="container">

            <?php if (strlen($error) > 0) { ?>
                <div class="roe">
                    <div class="col">
                        <h3> <?php echo $error; ?></h3>
                    </div>
                </div>
            <?php } else { ?>

                <div class="row">
                    <div class="col">
                        <b>Folio de la compra</b><?php echo $id_transaccion; ?><br>
                        <b>Fecha de la compra</b><?php echo $fecha; ?><br>
                        <b>Total: </b><?php echo MONEDA . number_format($total, 2, '.', ','); ?><br>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Importe $ </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row_det = $resultado2->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                                    <tr>
                                        <td><?php echo $row_det['cantidad']; ?></td>
                                        <td><?php echo $row_det['nombre']; ?></td>
                                        <td>$ <?php echo $importe; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../JS/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
</body>

</html>