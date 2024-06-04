<?php

/**
 * Pantalla para modificar contraseña
 * Autor: WifiTeam
 */

require 'config/config.php';
require 'clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($user_id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!verificaTokenRequest($user_id, $token, $con)) {
    echo 'No se pudo verificar la información';
    exit;
}

if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    if (empty($errors)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            echo "Contraseña modificada.<br><a href='login.php'>Iniciar sesión</a>";
            exit;
        } else {
            $errors[] = "Error al modificar contraseña. Intentalo nuevamente.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda en linea</title>

    <link href="<?php echo SITE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="form-login m-auto">
        <h3>Cambiar contraseña</h3>

        <?php mostrarMensajes($errors); ?>

        <form class="row g-3" action="reset_password.php" method="post" autocomplete="off">

            <input type="hidden" id="user_id" name="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" id="token" name="token" value="<?= $token; ?>" />

            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña" required autofocus>
                <label for="password">Nueva contraseña</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Confirmar contraseña" required>
                <label for="repassword">Confirmar contraseña</label>
            </div>

            <div class="d-grid gap-3 col-12">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>

            <div class="col-12">
                <a href="login.php">Iniciar sesión</a>
            </div>
        </form>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>

</body>

</html>