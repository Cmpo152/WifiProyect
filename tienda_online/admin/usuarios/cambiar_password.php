<?php

/**
 * Pantalla para mostrar el formulario de cambiar contraseña
 * Autor: WifiTeam
 */

require '../config/config.php';
require '../clases/adminFunciones.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? '';

if ($user_id == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (empty($errors)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            $errors[] = "Contraseña modificada.";
        } else {
            $errors[] = "Error al modificar contraseña. Intentalo nuevamente.";
        }
    }
}

$sql = "SELECT id, usuario FROM usuarios WHERE id = $user_id";
$resultado = $con->query($sql);
$usuario = $resultado->fetch(PDO::FETCH_ASSOC);

require '../header.php';

?>

<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Actualiza contraseña</h3>

        <?php mostrarMensajes($errors); ?>

        <form action="cambiar_password.php" method="post" enctype="multipart/form-data" autocomplete="off">

            <input type="hidden" id="user_id" name="user_id" value="<?php echo $usuario['id']; ?>" />

            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario['usuario']; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required autofocus>
            </div>

            <div class="mb-3">
                <label for="repassword" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" name="repassword" id="repassword" required>
            </div>

            <a href="index.php" class="btn btn-secondary">Regresar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>
</main>

<?php require '../footer.php'; ?>