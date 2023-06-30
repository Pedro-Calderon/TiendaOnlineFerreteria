<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
$objeto = new Database();
$conexion = $objeto->getConnection();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

/* 
echo'<pre>';
print_r($datos);
echo'</pre>';
*/

if (is_array($datos)) {

    $id_cliente=$_SESSION['user_cliente'];

    $consulta = "SELECT email FROM clientes WHERE id = ? AND estatus = 1";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute([$id_cliente]);
    $row_cliente = $resultado->fetch(PDO::FETCH_ASSOC);




    $id_transaccion = $datos['details']['id'];
    $total = $datos['details']['purchase_units'][0]['amount']['value'];
    $status = $datos['details']['status'];
    $fecha = $datos['details']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $row_cliente['email'];
    //$id_cliente = $datos['details']['payer']['payer_id'];

    $consulta = "INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES (?, ?, ?, ?, ?, ?)";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
    $id = $conexion->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $consulta = "SELECT id, nombre, precio, stock, descuento FROM productos WHERE id = ? AND activo = 1";
                $resultado = $conexion->prepare($consulta);
                $resultado->execute([$clave]);
                $row_prod = $resultado->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $cantidad = $cantidad;
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $sql_insert = "INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?)";
                $resultado = $conexion->prepare($sql_insert);
                $resultado->execute([$id, $clave, $row_prod['nombre'], $precio_desc, $cantidad]);
            }

            // Código adicional para deducir la cantidad del stock
            $cantidadDeducir = isset($datos['cantidadDeducir']) ? $datos['cantidadDeducir'] : null;
            if ($cantidadDeducir != null) {
                foreach ($cantidadDeducir as $idProducto => $cantidad) {
                    $consultaStock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id";
                    $resultadoStock = $conexion->prepare($consultaStock);
                    $resultadoStock->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
                    $resultadoStock->bindValue(':id', $idProducto, PDO::PARAM_INT);
                    $resultadoStock->execute();
                }
            }
            

           require 'Mailer.php';
           
           $asunto="Detalles de su compra";
           $cuerpo='<h4>Gracias por su compra</h4>';
           $cuerpo .='<p>El ID de su compra es <b> '.$id_transaccion.'</b></p>';
           $mailer=new Mailer();
           $mailer->enviarEmail($email,$asunto,$cuerpo);

        }
        unset($_SESSION['carrito']);
    }
}

// Envía una respuesta de éxito al cliente
http_response_code(200); // Código de respuesta 200 (éxito)
