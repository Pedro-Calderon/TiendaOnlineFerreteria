<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
var_dump($_POST);
error_reporting(E_ALL);

require_once 'getConnection.php';

// Verificar si se recibieron datos del formulario
if (isset($_POST['registrar'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    // Validar que los campos no estén vacíos
    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password) && !empty($repeatPassword)) {
        // Validar que las contraseñas coincidan
        if ($password !== $repeatPassword) {
            echo 'Las contraseñas no coinciden. Por favor, inténtalo de nuevo.';
            return;
        }

        $pdo = new Database();
        $conexion = $pdo->getConnection();

        // Concatenar nombre y apellido en un solo campo
        $fullName = $firstName . ' ' . $lastName;

        // Cifrar la contraseña usando SHA256
        $hashedPassword = hash('sha256', $password);

        // Preparar la consulta SQL para insertar los datos
        $sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasenia, tipo_usuario) VALUES (?, ?, ?, 3)";
        $statement = $conexion->prepare($sql);

        // Enlazar los parámetros de la consulta SQL con las variables PHP
        $statement->bindParam(1, $fullName);
        $statement->bindParam(2, $email);
        $statement->bindParam(3, $hashedPassword);

        // Ejecutar la consulta SQL
        if ($statement->execute()) {
            echo 'Registro exitoso. Redirigiendo a la página de inicio de sesión.';
            header('Location: ../Views/login.html');
            exit();
        } else {
            echo 'Ocurrió un error al registrar el usuario. Por favor, inténtalo de nuevo más tarde.';
        }

        // Cerrar la conexión y el statement
        $statement = null;
        $pdo = null;
    } else {
        echo "Por favor, llene todos los campos";
    }
}
?>
