<?php

/**
 * Pantalla principal para mostrar el listado de categorías
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

require '../header.php';

?>
<main>
    <div class="container-fluid px-3">
        <h3 class="mt-2">Categorías</h3>

        <a class="btn btn-primary" href="nuevo.php">Agregar</a>

        <hr>

        <table id="datatablesSimple" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th style="width: 5%" data-sortable="false"></th>
                    <th style="width: 5%" data-sortable="false"></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($categorias as $categoria) { ?>
                    <tr>
                        <td><?php echo $categoria['id']; ?></td>
                        <td><?php echo $categoria['nombre']; ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="edita.php?id=<?php echo $categoria['id']; ?>">
                                <i class="fas fa-pen"></i> Editar
                            </a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="<?php echo $categoria['id']; ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal Body -->
<div class="modal fade" id="eliminaModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Alerta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar este registro?
            </div>
            <div class="modal-footer">
                <form action="elimina.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let eliminaModal = document.getElementById('eliminaModal')
    eliminaModal.addEventListener('show.bs.modal', function(event) {

        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let modalInputId = eliminaModal.querySelector('.modal-footer input')
        modalInputId.value = id
    })
</script>

<?php require '../footer.php'; ?>