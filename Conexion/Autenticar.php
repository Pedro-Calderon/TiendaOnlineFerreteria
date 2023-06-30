<?php

require_once('getConnection.php');
$username = $_POST['username'];
$password = $_POST['password'];
session_start();
$_SESSION['username'] = $username;

$user = login($username, $password);

function login($username, $password)
{
  $conexion = new Database();
  $pdo = $conexion->getConnection();
  $query = "SELECT * FROM usuarios WHERE nombre_usuario  = :username AND contrasenia = SHA2(:password,256)";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  $query2 = "SELECT * FROM usuarios WHERE correo_electronico  = :username AND contrasenia = SHA2(:password,256)";

  $stmt2 = $pdo->prepare($query2);
  $stmt2->bindParam(':username', $username);
  $stmt2->bindParam(':password', $password);
  $stmt2->execute();

  if ($stmt->rowCount() > 0) {
    // La consulta encontró al menos un resultado
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Aquí puedes realizar acciones adicionales con la variable $user
  } else {
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);
    // La consulta no encontró resultados
  }

  if ($user) {
    if ($user['tipo_usuario'] == 1) { // Administrador
      $_SESSION['rol'] = 'administrador'; // Asigna el rol 'administrador'
      header('Location: ../admin/index.php');
      exit();
    } else if ($user['tipo_usuario'] == 2) { // Empleado
      $_SESSION['rol'] = 'empleado'; // Asigna el rol 'empleado'
      header('Location: ../admin/index.php');
      exit();
    } 
  }

  // Si el usuario no tiene rol válido, redirigir a la página de login con mensaje de error
  header('Location: ../Views/adminLogin.php?error=1');
  header('Location: ../Views/adminLogin.php');
  exit();
}
