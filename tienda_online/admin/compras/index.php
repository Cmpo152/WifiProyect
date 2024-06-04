<?php

/**
 * Pantalla historial de compras
 * Autor: WifiTeam
 */

require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT id_transaccion, fecha, status, total, medio_pago, CONCAT(nombres,' ',apellidos) AS cliente
FROM compra
INNER JOIN clientes ON compra.id_cliente = clientes.id
ORDER BY fecha DESC";
$resultado = $con->query($sql);

require '../header.php';

?>
<!-- Contenido -->
<main class="flex-shrink-0">
    <div class="container mt-3">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h3>Compras</h3>
            <a class="btn btn-success" href="genera_reporte_compras.php">
                Reporte de compras
            </a>
        </div>

        <hr>

        <table id="datatablesSimple" class="table table-bordered table-sm">

            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th style="width: 5%" data-sortable="false"></th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['id_transaccion']; ?></td>
                        <td><?php echo $row['cliente']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detalleModal" data-bs-orden="<?php echo $row['id_transaccion']; ?>">
                                <i class="fas fa-shopping-basket"></i> Ver
                            </button>
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="detalleModalLabel">Detalles de compra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const detalleModal = document.getElementById('detalleModal')
    detalleModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const orden = button.getAttribute('data-bs-orden')
        const modalBody = detalleModal.querySelector('.modal-body')

        const url = '<?php echo ADMIN_URL; ?>compras/getCompra.php'

        let formData = new FormData()
        formData.append('orden', orden)

        fetch(url, {
                method: 'post',
                body: formData,
            })
            .then((resp) => resp.json())
            .then(function(data) {
                modalBody.innerHTML = data
            })
    })

    detalleModal.addEventListener('hide.bs.modal', event => {
        const modalBody = detalleModal.querySelector('.modal-body')
        modalBody.innerHTML = ''
    })
</script>

<?php include '../footer.php'; ?>