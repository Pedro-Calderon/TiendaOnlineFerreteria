<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$objeto = new Database();
$conexion = $objeto->getConnection();

$errores = [];

if (!empty($_POST)) {

    $email = trim($_POST['email']);


    if ((esNulo([$email]))) {
        $errores[] = "Debe llenar todos los campos";
    }
    if (!esEmail($email)) {
        $errores[] = "El correo no es valido";
    }

    if (count($errores) == 0) {
        if (emailExiste($email, $conexion)) {
            $sql = "SELECT usuarios2.id , clientes.nombre FROM usuarios2 
            INNER JOIN clientes ON usuarios2.id_cliente=clientes.id
             WHERE clientes.email LIKE ? LIMIT 1";
            $resultado = $conexion->prepare($sql);
            $resultado->execute([$email]);
            $row = $resultado->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombre'];

            $token = solicitaPass($user_id, $conexion);
            if ($token !== null) {
                require '../clases/Mailer.php';
                $mailer = new Mailer();
                $url = SITE_URL . '/Views/reset_password.php?id=' . $user_id . '&token=' . $token;

                $asunto = "Recuperar Password - The Miner's Armory";
                $cuerpo = "Estimado $nombres: <br> Para continuar con la recuperacion de la password, 
                 de click en la siguiente liga <a href='$url'> $url</a>";
                $cuerpo .= "<br> Si no hiciste esta solicitud puede ignorar este correo.";



                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviado un correo a la direccion $email para restablecer la password</p>";
                    echo '<a href="login.php">Volver al Login</a>';
                    exit;
                }
            }
        } else {
            $errores[] = "No existe una cuenta asociada a esta direccion de correo.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Forgot Password</title>

    <!-- Enlace a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/Logo.jfif" type="image/x-icon">

    <!-- Estilos personalizados -->
    <style>
        .bg-gradient-primary {
            background: #0F2027;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #2C5364, #203A43, #0F2027);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #2C5364, #203A43, #0F2027);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-body {
            padding: 5rem !important;
        }

        #a {
            border-radius: 5%;
            width: 550px;
            height: 40 0px;
            margin: 200px auto;

            margin-top: 1rem;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5" id="a">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <?php mostarMensajes($errores); ?>


                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-2">¿Olvidaste tu contraseña?</h1>
                            <p class="mb-4">Lo entendemos, a veces ocurren cosas. Simplemente ingresa tu dirección de correo electrónico a continuación y te enviaremos un enlace para restablecer tu contraseña.</p>
                        </div>

                        <form class="user" action="forgot-password.php" method="post" autocomplete="off">
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" aria-describedby="emailHelp" placeholder="Ingresa tu dirección de email">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Restablecer contraseña</button>
                        </form>


                        <hr>
                        <div class="text-center">
                            <a class="small" href="registro.php">Crear una cuenta</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="../Views/login.php">¿Ya tienes una cuenta? Inicia sesión</a>
                        </div>


                    </div>
                </div>

            </div>

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


</body>

</html>