<?php
include_once '../Conexion/config.php';
include_once '../Conexion/getConnection.php';
include_once '../clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
if ($user_id == '' || $token == '') {
    header("Location: ../Views/index.php");
    exit;
}




$objeto = new Database();
$conexion = $objeto->getConnection();

$errores = [];

if (!verificaTokenRequest($user_id, $token, $conexion)) {
    echo "No se pudo verificar la informacion";
    exit;
}



if (!empty($_POST)) {
   
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if ((esNulo([$user_id,$token,$password, $repassword]))) {
        $errores[] = "Debe llenar todos los campos";
    }
   

    if (!validaPassword($password, $repassword)) {
        $errores[] = "No coinciden los password";
    }

if(count($errores)==0){
    $pass_has=password_hash($password,PASSWORD_DEFAULT);

    if(actualizaPassword($user_id,$pass_has,$conexion)){
        echo "Password modificada.<br><a href='../Views/login.php'>Iniciar sesion</a>";
    exit;
    }else{
        $errores[]="Error al modificar la password";
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

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <main>
       
        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5" id="a">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <?php mostarMensajes($errores); ?>
                            <h3 style="text-align: center;">Reset Password</h3>

                            

                            <form class="user" action="reset_password.php" method="post" autocomplete="off">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>"/>
                                <input type="hidden" name="token" id="token" value="<?= $token; ?>"/>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingresa tu nueva password" >
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" id="repassword" name="repassword" placeholder="Confirma tu nueva password" >
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">Restablecer contraseña</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="../Views/login.php">¿Ya tienes una cuenta? Inicia sesión</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>