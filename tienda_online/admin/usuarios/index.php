<?php

/**
 * Pantalla principal para mostrar el listado de usuarios
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT usuarios.id, CONCAT(clientes.nombres,' ',clientes.apellidos) AS cliente, usuario, activacion,
CASE
    WHEN activacion = 1 THEN 'Activo'
    WHEN activacion = 0 THEN 'No activado'
    ELSE 'deshabilitado'
END AS estatus
FROM clientes
INNER JOIN usuarios ON clientes.id = usuarios.id_cliente";
$usuarios = $con->query($sql);

require '../header.php';

?>

<!-- Contenido -->
<main class="flex-shrink-0">
    <div class="container-fluid px-3">
        <h3 id="titulo" class="mt-2">Usuarios</h3>

        <hr>

        <table class="table table-bordered table-sm" aria-describedby="titulo">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>usuario</th>
                    <th>Estatus</th>
                    <th style="width: 10%" data-sortable="false"></th>
                    <th style="width: 10%" data-sortable="false"></th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo $row['cliente']; ?></td>
                        <td><?php echo $row['usuario']; ?></td>
                        <td><?php echo $row['estatus']; ?></td>
                        <td>
                            <a href="cambiar_password.php?user_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-key"></i> Cambia
                            </a>
                        </td>
                        <td>

                            <?php if ($row['activacion'] == 1) { ?>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-user="<?php echo $row['id']; ?>">
                                    <i class="fas fa-arrow-down"></i> Baja
                                </button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#activaModal" data-bs-user="<?php echo $row['id']; ?>">
                                    <i class="fas fa-check-circle"></i> Activa
                                </button>
                            <?php } ?>
                        </td>
                    </tr>

                <?php endwhile; ?>

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
                ¿Desea deshabilitar este usuario?
            </div>
            <div class="modal-footer">
                <form action="deshabilita.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Deshabilitar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Body -->
<div class="modal fade" id="activaModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Alerta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea activar este usuario?
            </div>
            <div class="modal-footer">
                <form action="activa.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Activar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let eliminaModal = document.getElementById('eliminaModal')
    eliminaModal.addEventListener('show.bs.modal', function(event) {

        let button = event.relatedTarget
        let user = button.getAttribute('data-bs-user')

        let modalInputId = eliminaModal.querySelector('.modal-footer input')
        modalInputId.value = user
    })

    let activaModal = document.getElementById('activaModal')
    activaModal.addEventListener('show.bs.modal', function(event) {

        let button = event.relatedTarget
        let user = button.getAttribute('data-bs-user')

        let modalInputId = activaModal.querySelector('.modal-footer input')
        modalInputId.value = user
    })
</script>

<?php require '../footer.php'; ?>