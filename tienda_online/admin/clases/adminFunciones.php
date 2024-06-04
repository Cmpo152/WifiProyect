<?php

/**
 * Funciones de utilidad para administración
 * Autor: WifiTeam
 */

function esNulo($parametos)
{
    foreach ($parametos as $parameto) {
        if (strlen(trim($parameto)) < 1) {
            return true;
        }
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

function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function mostrarMensajes($errors = [])
{
    if (!empty($errors)) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '<ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function validaToken($id, $token, $con)
{
    $msg = "";
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0) {
        if (activarUsuario($id, $con)) {
            $msg = "Cuenta activada.";
        } else {
            $msg = "Error al activar cuenta.";
        }
    } else {
        $msg = "No existe el registro del cliente.";
    }
    return $msg;
}

function activarUsuario($id, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]);
}

function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_type'] = 'admin';
            header("Location: inicio.php");
            exit;
        }
    }
    return 'El usuario y/o contraseña son incorrectos';
}

function solicitaPassword($userId, $con)
{
    $token = generarToken();

    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if ($sql->execute([$token, $userId])) {
        return $token;
    }
    return null;
}

function verificaTokenRequest($userId, $token, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$userId, $token]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function actualizaPassword($userId, $password, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if ($sql->execute([$password, $userId])) {
        return true;
    }
    return false;
}

function actualizaPasswordAdmin($userId, $password, $con)
{
    $sql = $con->prepare("UPDATE admin SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if ($sql->execute([$password, $userId])) {
        return true;
    }
    return false;
}

function crearTituloURL($cadena) {
    // Convertir la cadena a minúsculas y reemplazar caracteres especiales y espacios con guiones
    $url = strtolower($cadena);
    $url = preg_replace('/[^a-z0-9\-]/', '-', $url);
    $url = preg_replace('/-+/', "-", $url); // Reemplazar múltiples guiones con uno solo
    $url = trim($url, '-'); // Eliminar guiones al principio y al final
    
    return $url;
}
