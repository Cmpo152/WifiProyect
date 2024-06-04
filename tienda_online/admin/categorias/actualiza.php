<?php

/**
 * Modifica el registro para categorías
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$nombre = trim($_POST['nombre']);
$id = $_POST['id'];

$sql = $con->prepare("UPDATE categorias SET nombre=? WHERE id=?");
$sql->execute([$nombre, $id]);

header('Location: index.php');
