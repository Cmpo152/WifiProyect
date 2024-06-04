<?php

require 'config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$hoy = date('Y-m-d');
$lunes = date('Y-m-d', strtotime('monday this week', strtotime($hoy)));
$domingo = date('Y-m-d', strtotime('sunday this week', strtotime($hoy)));

$fechaInicial = new DateTime($lunes);
$fechaFinal = new DateTime($domingo);

$diasVentas = [];

for ($i = $fechaInicial; $i <= $fechaFinal; $i->modify('+1 day')) {
    $diasVentas[] = totalDia($con, $i->format('Y-m-d'));
}

$diasVentas = implode(',', $diasVentas);

$fechaIni = date('Y-m') . '-01';
$ultimoDia = date("d", (mktime(0, 0, 0, date('m') + 1, 1, date('y')) - 1));
$fechaFin = date('Y-m') . '-' . $ultimoDia;

$listaProductos = productosMasVendidos($con, $fechaIni, $fechaFin);
$nombreProductos = [];
$cantidadProductos = [];

foreach ($listaProductos as $producto) {
    $nombreProductos[] = $producto['nombre'];
    $cantidadProductos[] = $producto['cantidad'];
}

$nombreProductos = implode("','", $nombreProductos);
$cantidadProductos = implode(',', $cantidadProductos);

function totalDia($con, $fecha)
{
    $sql = "SELECT IFNULL(SUM(total), 0) AS total FROM compra
    WHERE DATE(fecha) = '$fecha' AND (status LIKE 'COMPLETED' OR status LIKE 'approved')";
    $resultado = $con->query($sql);
    $row = $resultado->fetch(PDO::FETCH_ASSOC);

    return $row['total'];
}

function productosMasVendidos($con, $fechaInicial, $fechaFinal)
{
    $sql = "SELECT SUM(dc.cantidad) AS cantidad, dc.nombre FROM detalle_compra AS dc
    INNER JOIN compra AS c ON dc.id_compra = c.id
    WHERE DATE(c.fecha) BETWEEN '$fechaInicial' AND '$fechaFinal'
    GROUP BY dc.id_producto, dc.nombre
    ORDER BY SUM(dc.cantidad) DESC
    LIMIT 5";
    $resultado = $con->query($sql);

    return $resultado->fetchAll(PDO::FETCH_ASSOC);
}

include 'header.php';

?>
<main>
    <div class="container-fluid px-4">
        <h1 class="my-3">Dashboard</h1>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Ventas de la semana
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="100%" height="75"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Productos más vendidos del mes
                    </div>
                    <div class="card-body">
                        <canvas id="chart-productos" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('myChart');

    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
            datasets: [{
                labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
                data: [<?php echo $diasVentas; ?>],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    // Pie Chart Example
    const ctxProdctos = document.getElementById('chart-productos');

    let chartProd = new Chart(ctxProdctos, {
        type: 'pie',
        data: {
            labels: ['<?php echo $nombreProductos; ?>'],
            datasets: [{
                data: [<?php echo $cantidadProductos; ?>],
                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#697bff'],
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            }
        }
    });
</script>

<?php include 'footer.php'; ?>