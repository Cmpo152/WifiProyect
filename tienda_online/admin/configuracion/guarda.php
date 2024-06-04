<?php

/**
 * Garda las configuraciones
 * Autor: WifiTeam
 */

require '../config/config.php';
require '../../clases/cifrado.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$moneda = $_POST['moneda'];

$smtp = $_POST['smtp'];
$puerto = $_POST['puerto'];
$email = $_POST['email'];
$password = $_POST['password'];

$paypal_cliente = $_POST['paypal_cliente'];
$paypal_moneda = $_POST['paypal_moneda'];

$mp_token = $_POST['mp_token'];
$mp_clave = $_POST['mp_clave'];

$passwordBd = '';
$sqlConfig = $con->query("SELECT valor FROM configuracion WHERE nombre = 'correo_password' LIMIT 1");
$sqlConfig->execute();
if ($row_config = $sqlConfig->fetch(PDO::FETCH_ASSOC)) {
    $passwordBd = $row_config['valor'];
}

$sql = $con->prepare("UPDATE configuracion SET valor = ? WHERE nombre = ?");
$sql->execute([$nombre, 'tienda_nombre']);
$sql->execute([$telefono, 'tienda_telefono']);
$sql->execute([$moneda, 'tienda_moneda']);
$sql->execute([$smtp, 'correo_smtp']);
$sql->execute([$puerto, 'correo_puerto']);
$sql->execute([$email, 'correo_email']);
$sql->execute([$paypal_cliente, 'paypal_cliente']);
$sql->execute([$paypal_moneda, 'paypal_moneda']);
$sql->execute([$mp_token, 'mp_token']);
$sql->execute([$mp_clave, 'mp_clave']);

if ($passwordBd != $password) {
    $password = cifrado($password, ['key' => KEY_CIFRADO, 'method' => METODO_CIFRADO]);
    $sql->execute([$password, 'correo_password']);
}

include '../header.php';

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Configuración actualizada</h2>

        <a class="btn btn-secondary" href="index.php">Regresar</a>
    </div>
</main>

<?php include '../footer.php'; ?>