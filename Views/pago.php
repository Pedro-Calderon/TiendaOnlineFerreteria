<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
$objeto = new Database();
$conexion = $objeto->getConnection();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

//print_r($_SESSION);



$Lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {

        $consulta = "SELECT id, nombre, precio, descuento, $cantidad as cantidad from productos where id=? and activo=1";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute([$clave]);
        $Lista_carrito[] = $resultado->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header('Location: ../Views/index.php');
    exit;
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


</head>

<body>

    <header>
        <?php include 'navbar.php'; ?>
    </header>


    <!-- contenido de los productos -->
    <main>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h4>Detalles de pago</h4>
                    <div id="paypal-button-container"></div>
                </div>

                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($Lista_carrito == null) {
                                    echo  '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                                } else {


                                    $total = 0;
                                    foreach ($Lista_carrito as $producto) {
                                        $_id = $producto['id'];
                                        $nombre = $producto['nombre'];
                                        $precio = $producto['precio'];
                                        $descuento = $producto['descuento'];
                                        $cantidad = $producto['cantidad'];
                                        $precio_desc = $precio - (($precio * $descuento) / 100);
                                        $subtotal = $cantidad * $precio_desc;
                                        $total += $subtotal;
                                ?>

                                        <tr>
                                            <td><?php echo $nombre; ?></td>

                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                        </td>
                                    </tr>
                            </tbody>
                        <?php } ?>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </main>


    <?php include '../Views/footer.php'; ?>



    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../JS/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                actions.order.capture().then(function(details) {
                    let url = '../clases/captura.php';
                    let productos = <?php echo json_encode($Lista_carrito); ?>; // Obtén la lista de productos del carrito
                    let cantidadDeducir = {}; // Objeto para almacenar las cantidades a deducir del stock
 
                    // Crea un objeto con las cantidades a deducir para cada producto
                    productos.forEach(function(producto) {
                        let idProducto = producto.id;
                        let cantidad = producto.cantidad;
                        cantidadDeducir[idProducto] = cantidad;
                    });

                    // Realiza la solicitud AJAX para actualizar el stock
                    return fetch(url, {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            details: details,
                            cantidadDeducir: cantidadDeducir
                        })
                    }).then(function(response) {
                        window.location.href = "completado.php?key="+ details['id']; 
                    })//;
                });
            },

            onCancel: function(data) {
                alert('Pago Cancelado');
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
    <script src="https://kit.fontawesome.com/c26d6306d9.js" crossorigin="anonymous"></script>
</body>

</html>