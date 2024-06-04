<?php

/**
 * Deshabilita el registro de usuario
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

$sql = $con->prepare("UPDATE usuarios SET activacion=2 WHERE id=?");
$sql->execute([$id]);

header('Location: index.php');
