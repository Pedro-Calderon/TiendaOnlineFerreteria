<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$objeto = new Database();
$conexion = $objeto->getConnection();

$errores = [];
 
if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $curp = trim($_POST['curp']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if ((esNulo([$nombres, $apellidos, $email, $telefono, $curp, $usuario, $password, $repassword]))) {
        $errores[] = "Debe llenar todos los campos";
    }
    if (!esEmail($email)) {
        $errores[] = "El correo no es valido";
    }

    if (!validaPassword($password, $repassword)) {
        $errores[] = "No coinciden los password";
    }

    if (usuarioExiste($usuario, $conexion)) {
        $errores[] = "El nombre de usuario $usuario ya existe";
    }
    if (emailExiste($email, $conexion)) {
        $errores[] = "El correo electronic $email ya existe";
    }
    if (curpExiste($curp, $conexion)) {
        $errores[] = "El la curp $curp ya existe";
    }

    if (count($errores) == 0) {


        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $curp], $conexion);

        if ($id > 0) {


            require '../clases/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
           

            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $id_usuario=registraUsuario([$usuario, $pass_hash, $token, $id], $conexion);
            if ($id_usuario>0) {

                $url = SITE_URL . '/Views/activa_cliente.php?id=' . $id_usuario . '&token=' . $token;
                $asunto = "Activar cuenta - The Miner's Armory";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el registro, es indispensable
                de que de click en la siguiente liga <a href='$url'>Activar cuenta</a>";
    

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "Para terminar el proceso de registro, siga las instrucciones que 
                        le hemos enviado a la direccion de correo electronico $email";
                        
                          echo '<a href="login.php">Volver al Login</a>';
                    exit();
                }
            } else {
                $errores[] = "Error al registrar usuario!";
            }
        } else {
            $errores[] = "Error al registrar usuario";
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>The Miner's Armory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../CSS/estilos.css">
<!--         <link rel="stylesheet" type="text/css" href="../CSS/estiloCatalogos.css">
 -->    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon" id="logo">
    <style>
    body {
  background: #0F2027;
  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2C5364, #203A43, #0F2027);
  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2C5364, #203A43, #0F2027);
  /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
    </style>

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <main>
        <div class="container">
            <div class="login-form">

                <?php mostarMensajes($errores); ?>

                <form action="../Views/registro.php" method="post" class="row g-3" autocomplete="off">
                    <h2 class="text-center">Registro de Datos</h2>
                    <div class="col-md-6">
                        <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                        <input type="text" name="nombres" id="nombres" maxlength="30" class="form-control">

                    </div>
                    <div class="col-md-6">
                        <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" maxlength="100" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                        <input type="email" name="email" id="email" maxlength="50" class="form-control">
                        <span id="validaEmail" class="text-danger"></span>
                    </div>
                    <div class="col-md-6">
    <label for="telefono"><span class="text-danger">*</span> Teléfono</label>
    <input type="tel" name="telefono" id="telefono" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
    <small class="text-muted">Ingresa un número de teléfono válido (10 dígitos).</small>
</div>

<div class="col-md-6">
    <label for="curp"><span class="text-danger">*</span> CURP</label>
    <input type="text" name="curp" id="curp" class="form-control" pattern="[A-Z0-9a-z]{18}" maxlength="18" required>
    <small class="text-muted">Ingresa un CURP válido (18 caracteres alfanuméricos).</small>
</div>
                    <div class="col-md-6">
                        <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control">
                        <span id="validaUsuario" class="text-danger"maxlength="20"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="password"><span class="text-danger">*</span> Password </label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="repassword"><span class="text-danger">*</span> Repetir Password</label>
                        <input type="password" name="repassword" id="repassword" class="form-control">
                    </div>

                    <i>Nota: <b>Los campos con * son obligatorios</b></i>

                    <div class="row">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                    <div class="text-center">
                        <a class="small" href="login.php" id="cancel">Ya tienes una uenta!</a>
                    </div>

                </form>
            </div>

        </div>
    </main>
    <script>
        let txtusuario = document.getElementById('usuario')
        txtusuario.addEventListener("blur", function() {
            existeUsuario(txtusuario.value)
        }, false)

        let txtemail = document.getElementById('email')
        txtemail.addEventListener("blur", function() {
            existeEmail(txtemail.value)
        }, false)

        function existeUsuario(usuario) {
            let url = "../clases/usuarioAjax.php"
            let formData = new FormData()
            formData.append("action", "existeUsuario")
            formData.append("usuario", usuario)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('usuario').value = ''
                        document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                    } else {
                        document.getElementById('validaUsuario').innerHTML = ''
                    }

                })
        }

        function existeEmail(email) {
            let url = "../clases/usuarioAjax.php"
            let formData = new FormData()
            formData.append("action", "existeEmail")
            formData.append("email", email)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('email').value = ''
                        document.getElementById('validaEmail').innerHTML = 'Email no disponible'
                    } else {
                        document.getElementById('validaEmail').innerHTML = ''
                    }

                })
        }
    </script>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="../JS/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
</body>

</html>