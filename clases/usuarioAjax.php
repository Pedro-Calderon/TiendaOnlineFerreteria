<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';
$objeto = new Database();
$conexion = $objeto->getConnection();

$datos=[];

if(isset($_POST['action'])){
    $action=$_POST['action'];

    switch ($action) {
        case 'existeUsuario':
            $datos['ok'] = usuarioExiste($_POST['usuario'], $conexion);
            break;

        case 'existeEmail':
            $datos['ok'] = emailExiste($_POST['email'], $conexion);
            break;
    }



}
echo json_encode($datos);
