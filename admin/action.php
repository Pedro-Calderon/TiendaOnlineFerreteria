<?php
session_start();
require_once('config.php');
require_once '../clases/clienteFunciones.php';
$objeto = new Database();
$conn = $objeto->getConnection();


$metodoAction = isset($_REQUEST['metodo']) ? (int) filter_var($_REQUEST['metodo'], FILTER_SANITIZE_NUMBER_INT) : null;

if (empty($metodoAction)) {
    header("Location: index.php");
    exit();
}


if ($metodoAction == 1) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
    $precio = (float) filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_FLOAT);
    $descuento = (float) filter_var($_POST['descuento'], FILTER_SANITIZE_NUMBER_FLOAT);
    $categoria = (int) filter_var($_POST['categoria'], FILTER_SANITIZE_NUMBER_INT);
    $activo = (int) filter_var($_POST['activo'], FILTER_SANITIZE_NUMBER_INT);
    $stock = (int) filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);

    $filename = $_FILES["foto"]["name"];
    $tipo_foto = $_FILES['foto']['type'];
    $sourceFoto = $_FILES["foto"]["tmp_name"];
    $tamano_foto = $_FILES['foto']['size'];

    if ((strpos($tipo_foto, "image/png") !== false || strpos($tipo_foto, "image/jpeg") !== false) && $tamano_foto < 100000) {
        $logitudPass = 8;
        $newNameFoto = substr(md5(microtime()), 1, $logitudPass);
        $explode = explode('.', $filename);
        $extension_foto = array_pop($explode);
        $nuevoNameFoto = $newNameFoto . '.' . $extension_foto;

        $dirLocal = "fotosProductos";
        if (!file_exists($dirLocal)) {
            mkdir($dirLocal, 0777, true);
        }

        $urlFotoProducto = $dirLocal . '/' . $nuevoNameFoto;

        if (move_uploaded_file($sourceFoto, $urlFotoProducto)) {
            $SqlInsertProducto = "INSERT INTO productos (nombre, descripcion, precio, descuento, id_categoria, activo, stock, foto) VALUES (:nombre, :descripcion, :precio, :descuento, :categoria, :activo, :stock, :foto)";
            $stmt = $conn->prepare($SqlInsertProducto);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descuento', $descuento);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':activo', $activo);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':foto', $nuevoNameFoto);
            $stmt->execute();

            header("Location:index.php");
            exit();
        } else {
            echo "<script>alert('No se pudo cargar la imagen.'); window.location.href = 'index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No se pudo cargar la imagen.'); window.location.href = 'index.php';</script>";
        exit();
    }
}

if ($metodoAction == 2) {
    $id = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $stock = (int) filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);
    $precio = (float) $_POST['precio']; // Agrega el campo 'precio' y su respectivo valor
    $descuento = (float) $_POST['descuento']; // Agrega el campo 'descuento' y su respectivo valor
    $activo = (int) $_POST['activo']; // Agrega el campo 'activo' y su respectivo valor
    $descripcion = $_POST['descripcion']; // Agrega el campo 'descripcion' y su respectivo valor

    $UpdateProducto = "UPDATE productos SET nombre=:nombre, stock=:stock, precio=:precio, descuento=:descuento, activo=:activo, descripcion=:descripcion WHERE id=:id";
    $stmt = $conn->prepare($UpdateProducto);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':descuento', $descuento);
    $stmt->bindParam(':activo', $activo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if (!empty($_FILES["foto"]["name"])) {
        $filename = $_FILES["foto"]["name"];
        $tipo_foto = $_FILES['foto']['type'];
        $sourceFoto = $_FILES["foto"]["tmp_name"];
        $tamano_foto = $_FILES['foto']['size'];

        if (!((strpos($tipo_foto, "PNG") || strpos($tipo_foto, "jpg")) && ($tamano_foto < 100000))) {
            $logitudPass = 8;
            $newNameFoto = substr(md5(microtime()), 1, $logitudPass);
            $explode = explode('.', $filename);
            $extension_foto = array_pop($explode);
            $nuevoNameFoto = $newNameFoto . '.' . $extension_foto;

            $dirLocal = "fotosProductos";
            $miDir = opendir($dirLocal);
            $urlFotoProducto = $dirLocal . '/' . $nuevoNameFoto;

            if (move_uploaded_file($sourceFoto, $urlFotoProducto)) {
                $updateFoto = "UPDATE productos SET foto=:foto WHERE id=:id";
                $stmt = $conn->prepare($updateFoto);
                $stmt->bindParam(':foto', $nuevoNameFoto);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }
        } else {
            header("Location: index.php?errorimg=1");
            exit; // Asegúrate de incluir la instrucción exit para terminar la ejecución del script
        }
    }

    header("Location: index.php");
    exit; // Asegúrate de incluir la instrucción exit para terminar la ejecución del script
}


if($metodoAction == 3){
    $id  = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
    
    $namePhoto = filter_var($_REQUEST['namePhoto'], FILTER_SANITIZE_STRING);

    $SqlDeleteProducto = ("DELETE FROM productos WHERE  id=:id");
    $stmt = $conn->prepare($SqlDeleteProducto);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    if($stmt->rowCount() != 0){
        $fotoProducto = "fotosProductos/".$namePhoto;
        unlink($fotoProducto);
    }
    header("Location:index.php");
    exit();
}


if ($metodoAction == 4) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $contrasenia = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $tipo_usuario = (int)filter_var($_POST['rol'], FILTER_SANITIZE_NUMBER_INT);

    // Aplicar hash SHA-256 a la contraseña
    $contrasenia_hash = hash('sha256', $contrasenia);

    $objeto = new Database();
    $conn = $objeto->getConnection();
    if (!emailExisteUser($correo, $conn)) {

        if (!usuarioExisteUser($nombre, $conn)) {




            $SqlInsertUsuario = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasenia, tipo_usuario) VALUES (:nombre, :correo, :contrasenia, :tipo_usuario)";
            $stmt = $conn->prepare($SqlInsertUsuario);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':contrasenia', $contrasenia_hash); // Guardar la contraseña hasheada en la base de datos
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
            $stmt->execute();

            header("Location: usuarios.php");
            exit();
        }
        echo "<script>alert('usuario ya existente.'); window.location.href = 'usuarios.php';</script>";
        exit();
    }
    echo "<script>alert('correo ya existente.'); window.location.href = 'usuarios.php';</script>";
    exit();
}



if ($metodoAction == 5) {
    $id = (int) filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $rol = (int) filter_var($_POST['rol'], FILTER_SANITIZE_NUMBER_INT);

    $objeto = new Database();
    $conn = $objeto->getConnection();

    // Verificar si se proporcionó un nuevo correo electrónico
    $usuarioExistente = emailExisteUser($correo, $conn);
    if (empty($usuarioExistente) || (is_array($usuarioExistente) && $usuarioExistente['id_usuario'] == $id)) {
        // Obtener los datos del usuario actual de la base de datos
        $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuarioActual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuarioActual || $nombre != $usuarioActual['nombre_usuario']) {
            // Verificar si se proporcionó una nueva contraseña
            if (!empty($_POST['password'])) {
                $contrasenia = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                // Aplicar hash SHA-256 a la nueva contraseña
                $contrasenia_hash = hash('sha256', $contrasenia);
            }

            // Construir la consulta de actualización
            $UpdateUsuario = "UPDATE usuarios SET nombre_usuario=:nombre";
            $params = array(':nombre' => $nombre);

            // Verificar si se proporcionó un nuevo correo electrónico
            if (!empty($correo)) {
                $UpdateUsuario .= ", correo_electronico=:correo";
                $params[':correo'] = $correo;
            }

            // Verificar si se proporcionó una nueva contraseña
            if (isset($contrasenia_hash)) {
                $UpdateUsuario .= ", contrasenia=:contrasenia";
                $params[':contrasenia'] = $contrasenia_hash;
            }

            $UpdateUsuario .= ", tipo_usuario=:rol WHERE id_usuario=:id";
            $params[':rol'] = $rol;
            $params[':id'] = $id;

            // Ejecutar la consulta de actualización
            $stmt = $conn->prepare($UpdateUsuario);
            $stmt->execute($params);

            header("Location: usuarios.php");
            exit;
        } else {
            echo "<script>alert('Usuario ya existente.'); window.location.href = 'usuarios.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Correo electrónico ya existente.'); window.location.href = 'usuarios.php';</script>";
        exit();
    }
}




if ($metodoAction == 6) {
  $id = (int) filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

   // Verificar si el usuario a eliminar es el usuario autenticado actualmente
if ($_SESSION['username'] == getUsernameById($id, $conn)) {
    // Eliminar la sesión actual
    session_destroy();

    // Redirigir al usuario al login
    header("Location: ../Views/adminLogin.php");
    exit();
} else {
    // Eliminar el usuario de la base de datos
    $SqlDeleteUsuario = "DELETE FROM usuarios WHERE id_usuario = :id";
    $stmt = $conn->prepare($SqlDeleteUsuario);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() != 0) {
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Hubo un error al eliminar el usuario.";
        exit();
    }
}

}



?>

