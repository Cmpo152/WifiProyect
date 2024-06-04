<?php

/**
 * Pantalla para mostrar el formulario de nuevo registro
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

require '../header.php';

?>
<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Nueva categoría</h3>

        <form action="guarda.php" method="post" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>
</main>

<?php require '../footer.php'; ?>