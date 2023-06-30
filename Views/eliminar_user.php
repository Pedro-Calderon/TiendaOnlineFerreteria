<?php
require_once '../Conexion/getConnection.php'; //  conexión

if(isset($_GET['id_usuario'])) {

    $id = $_GET['id_usuario'];

    $pdo = new Database();
    $conexion = $pdo->getConnection();

    // Preparar la consulta SQL para eliminar el usuario con el id especificado
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?;";
    $statement = $conexion->prepare($sql);
    
    // Enlazar el parámetro de la consulta SQL con la variable PHP
    $statement->bindParam(1, $id);

    // Ejecutar la consulta SQL
    if($statement->execute()) {
        header('Location: ../Views/Empleados.php');
    } else {
        echo "No se pudo realizar la acción";
    }

    // Cerrar la conexión y el statement
    $statement = null;
    $pdo = null;
}
?>
