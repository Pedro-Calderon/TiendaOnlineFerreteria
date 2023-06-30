<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$objeto = new Database();
$conexion = $objeto->getConnection();

$id=isset($_GET['id'])? $_GET['id']: '';
$token=isset($_GET['token'])? $_GET['token']: '';

if($id==''|| $token==''){
    header('Location: ../Views/index.php');
    
    exit();
}

echo validaTokken($id,$token,$conexion); 

?>