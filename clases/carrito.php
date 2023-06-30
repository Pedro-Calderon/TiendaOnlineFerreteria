<?php
include_once '../Conexion/config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $cantida = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;
    $stock = isset($_POST['stock']) ? $_POST['stock'] : 1;

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp && $cantida > 0 && is_numeric($cantida) && $stock >= $cantida) {
        // Validar si la cantidad a agregar no excede el stock disponible
        if (isset($_SESSION['carrito']['productos'][$id])) {
            // Verificar si la cantidad a agregar supera el stock disponible
            $cantidadExistente = $_SESSION['carrito']['productos'][$id];
            if (($cantidadExistente + $cantida) > $stock) {
                $datos['ok'] = false;
                $datos['mensaje'] = "La cantidad solicitada excede el stock disponible";
                echo json_encode($datos);
                exit(); // Detener la ejecución del código
            } else {
                $_SESSION['carrito']['productos'][$id] += $cantida;
                $datos['numero'] = count($_SESSION['carrito']['productos']);
                $datos['ok'] = true;
            }
        } else {
            $_SESSION['carrito']['productos'][$id] = $cantida;
            $datos['numero'] = count($_SESSION['carrito']['productos']);
            $datos['ok'] = true;
        }
    } else {
        $datos['ok'] = false;
        $datos['mensaje'] = "Los datos enviados no son válidos";
        echo json_encode($datos);
        exit(); // Detener la ejecución del código
    }
} else {
    $datos['ok'] = false;
    $datos['mensaje'] = "No se recibieron los datos necesarios";
    echo json_encode($datos);
    exit(); // Detener la ejecución del código
}

echo json_encode($datos);
