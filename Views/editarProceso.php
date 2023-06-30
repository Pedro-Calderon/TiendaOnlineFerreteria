<?php
    print_r($_POST);
    if(!isset($_POST['id_usuario'])){
        header('Location: Empleados.php?mensaje=error');
    }

    include '../Conexion/getConnection.php';
    $id = $_POST['id_usuario'];
    $nombre = $_POST['txtNombre'];
    $correo = $_POST['txtcorreo'];
    $pass = $_POST['txtpass'];
    $rol = $_POST['txtrol'];
    
    $sentencia = $bd->prepare("UPDATE usuarios SET nombre_usuario = ?, correo_electronico = ?, contrasenia = ?, tipo_usuario = ? where id_usuario = ?;");
    $resultado = $sentencia->execute([$nombre, $correo, $pass, $id]);

    if ($resultado === TRUE) {
        header('Location: Empleados.php?mensaje=editado');
    } else {
        header('Location: Empleados.php?mensaje=error');
        exit();
    }
    
?>