<?php
require_once 'getConnection.php'; // Incluir archivo que contiene la clase de conexión PDO

if(isset($_POST['registrar'])) {
    $nombre = $_POST['nombre']; 
    $correo = $_POST['correo'];
    $password = hash('sha256', $_POST['password']);
    $rol = $_POST['rol'];

    // Validar que los campos no estén vacíos
    if(!empty($nombre) && !empty($correo) && !empty($password) && !empty($rol)) {

    
        $pdo = new Database();
        $conexion = $pdo->getConnection();

        // Preparar la consulta SQL para insertar los datos
        $sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasenia, tipo_usuario) VALUES (?, ?, ?, ?)";
        $statement = $conexion->prepare($sql);
        
        // Enlazar los parámetros de la consulta SQL con las variables PHP
        $statement->bindParam(1, $nombre);
        $statement->bindParam(2, $correo);
        $statement->bindParam(3, $password);
        $statement->bindParam(4, $rol);

        // Ejecutar la consulta SQL
        if($statement->execute()) {
            header('Location: ../Views/Empleados.php');
        } else {
            echo "No se pudo realizar la acción";
        }

        // Cerrar la conexión y el statement
        $statement = null;
        $pdo = null;

    } else {
        echo "Por favor, llene todos los campos";
    }
}
?>
