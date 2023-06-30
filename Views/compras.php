<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$objeto = new Database();
$conexion = $objeto->getConnection();

$token=generarToken();
$_SESSION['token']=$token;

$idCliente = $_SESSION['user_cliente'];
$consulta = "SELECT id_transaccion,fecha,status,total FROM compra WHERE id_cliente = ? ORDER BY date(fecha) DESC";
$resultado = $conexion->prepare($consulta);
$resultado->execute([$idCliente]);


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
            <h4>Mis Compras</h4>
            <hr>

                <?php while($row=$resultado->fetch(PDO::FETCH_ASSOC)) {?>
            <div class="card mb-3 ">
                <div class="card-header">
                    <?php echo $row['fecha'];?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Folio: <?php echo $row['id_transaccion'];?></h5>
                    <p class="card-text">Total:  <?php echo $row['total'];?></p>
                    <a href="../Views/compra_detalle.php?order=<?php echo $row['id_transaccion'];?>&token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>
                </div>
            </div>
                    <?php } ?>
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