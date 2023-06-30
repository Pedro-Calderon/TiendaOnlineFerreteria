<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function getUsernameById($id, $conexion)
{
    $sql = "SELECT nombre_usuario FROM usuarios WHERE id_usuario = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['nombre_usuario'];
    }

    return null;
}



function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}

function generarToken()
{
    return sha1(uniqid(mt_rand(), false));
}



function registraCliente(array $datos, $conexion)
{

    $sql = "INSERT INTO clientes(nombre, apellidos,email, telefono, curp, estatus, fecha_alta) VALUES(?,?,?,?,?,1,now())";
    $resultado = $conexion->prepare($sql);

    if ($resultado->execute($datos)) {
        return $conexion->lastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos, $conexion)
{
    $sql = "INSERT INTO usuarios2 (usuario,password,token,id_cliente)VALUES(?,?,?,?)";
    $resultado = $conexion->prepare($sql);
    if ($resultado->execute($datos)) {
        return $conexion->lastInsertId();
    }
    return 0;
}


function usuarioExiste($usuario, $conexion)
{

    $sql = "SELECT id FROM usuarios2 WHERE usuario LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$usuario]);

    if ($resultado->fetchcolumn() > 0) {
        return true;
    }
    return false;
}
function emailExiste($email, $conexion)
{

    $sql = "SELECT id FROM clientes WHERE email LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$email]);

    if ($resultado->fetchcolumn() > 0) {
        return true;
    }
    return false;
}
function curpExiste($curp, $conexion)
{

    $sql = "SELECT id FROM clientes WHERE curp LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$curp]);

    if ($resultado->fetchcolumn() > 0) {
        return true;
    }
    return false;
}

function mostarMensajes(array $errores)
{
    if (count($errores) > 0) {
        echo ' <div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}



function validaTokken($id, $token, $conexion)
{
    $mensaje = "hola";
    $sql = "SELECT id FROM usuarios2 WHERE id=? and token LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$id, $token]);

    if ($resultado->fetchcolumn() > 0) {
        if (activaUsuario($id, $conexion)) {
            $mensaje = "Cuenta Activada";
            $mensaje .= '<br><a href="login.php">Aceptar</a>'; // Agrega el enlace al botón "Aceptar" que redirige a la página de login
        } else {
            $mensaje = "Error al activar cuenta";
            $mensaje .= '<br><a href="login.php">Volver al Login</a>'; // Agrega el enlace al botón "Volver al Login" que redirige a la página de login
        }
    } else {
        $mensaje = "No existe el registro del usuario";
        $mensaje .= '<br><a href="login.php">Volver al Login</a>'; // Agrega el enlace al botón "Volver al Login" que redirige a la página de login
    }
    return $mensaje;
}


function activaUsuario($id, $conexion)
{
    $sql = "UPDATE usuarios2 SET activacion=1,token='' where id=?";
    $resultado = $conexion->prepare($sql);
    return $resultado->execute([$id]);
}

function login($usuario, $password, $conexion,$proceso)
{
    $sql = "SELECT id, usuario, password, id_cliente FROM usuarios2 where usuario LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$usuario]);
    if ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if (esActivo($usuario, $conexion)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                $_SESSION['user_cliente'] = $row['id_cliente'];
                if($proceso=='pago'){
                    header("Location: ../Views/checkout.php");     
                }else if($proceso=='compra'){
                header("Location: ../Views/compras.php");
                }else {
                    header("Location: ../Views/index.php");
                }
                exit;
            }
        } else {
            return 'El usuario no ah sido activado';
        }
    }
    return 'El usuario y/o contrasenia no coinciden';
}
function usuarioExisteUser($nombre, $conexion)
{

    $sql = "SELECT id_usuario FROM usuarios WHERE nombre_usuario LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$nombre]);

    if ($resultado->fetchcolumn() > 0) {
        return true;
    }
    return false;
}
function emailExisteUser($email, $conexion)
{

    $sql = "SELECT id_usuario FROM usuarios WHERE correo_electronico LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$email]);

    if ($resultado->fetchcolumn() > 0) {
        return true;
    }
    return false;
}

function esActivo($usuario, $conexion)
{
    $sql = "SELECT activacion FROM usuarios2 where usuario LIKE ? LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$usuario]);
    $row = $resultado->fetch(PDO::FETCH_ASSOC);
    if ($row['activacion'] == 1) {
        return true;
    } else {
        return false;
    }
}

function solicitaPass($user_id, $conexion)
{
    $token = generarToken();

    $sql = "UPDATE usuarios2 SET token_password=?, password_request=1 where id=?";
    $resultado = $conexion->prepare($sql);
    if ($resultado->execute([$token, $user_id])) {
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id, $token, $conexion)
{
    $sql = "SELECT id FROM usuarios2 where id=? and token_password LIKE ? and password_request=1 LIMIT 1";
    $resultado = $conexion->prepare($sql);
    $resultado->execute([$user_id, $token]);
    if ($resultado->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function actualizaPassword($user_id, $password, $conexion)
{
    $sql = "UPDATE usuarios2 set password=?, token_password='', password_request=0 where id=?";
    $resultado = $conexion->prepare($sql);
    if ($resultado->execute([$password, $user_id])) {
        return true;
    }
    return false;
}
