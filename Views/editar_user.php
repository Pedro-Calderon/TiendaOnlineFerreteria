<?php

// incluir la conexión a la base de datos
include('../Conexion/getConnection.php');

// validar si se reciben los datos por POST
if(isset($_POST['nombre_usuario']) && isset($_POST['correo_electronico']) && isset($_POST['tipo_usuario']) && isset($_POST['id_usuario'])) {

    // validar que los datos no estén vacíos
    if(!empty($_POST['nombre_usuario']) && !empty($_POST['correo_electronico']) && !empty($_POST['tipo_usuario']) && !empty($_POST['id_usuario'])) {

        // validar que el tipo de usuario sea un número de 1 o 2 dígitos
        if(preg_match("/^[1-2]$/", $_POST['tipo_usuario'])) {

            // obtener los datos recibidos por POST
            $id_usuario = $_POST['id_usuario'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $correo_electronico = $_POST['correo_electronico'];
            $tipo_usuario = $_POST['tipo_usuario'];

            // crear una instancia de la clase Database
            $database = new Database();
            $conexion = $database->getConnection();

            // preparar la consulta SQL para actualizar los datos del usuario
            $query = "UPDATE usuarios SET nombre_usuario='$nombre_usuario', correo_electronico='$correo_electronico', tipo_usuario='$tipo_usuario' WHERE id_usuario=$id_usuario";

            // ejecutar la consulta SQL
            try {
                $stmt = $conexion->prepare($query);
                $stmt->execute();

                // si se actualizó correctamente, redirigir a la página principal
                header('Location: ../Views/Empleados.php');
                exit();
            } catch(PDOException $e) {
                // si hubo un error al ejecutar la consulta, mostrar mensaje de error
                echo "Error al actualizar los datos del usuario: " . $e->getMessage();
            }

        } else {
            // si el tipo de usuario no es válido, mostrar mensaje de error
            echo "Tipo de usuario inválido. Debe ser un número de 1 o 2 dígitos.";
        }

    } else {
        // si hay datos vacíos, mostrar mensaje de error
        echo "Todos los campos son requeridos.";
    }

} else {
    // si no se recibieron los datos por POST, mostrar mensaje de error
    echo "No se recibieron los datos correctamente.";
}
?>
