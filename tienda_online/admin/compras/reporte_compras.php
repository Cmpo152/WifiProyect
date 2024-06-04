<?php

/**
 * Genera reporte de compra en PDF usando la biblioteca FPDF
 * Autor: WifiTeam
 */

require '../config/config.php';
require '../fpdf/plantilla_reporte_compra.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

function ordenarFecha($fecha)
{
    $arreglo = explode("-", $fecha);
    return $arreglo[2] . '/' . $arreglo[1] . '/' . $arreglo[0];
}

$fechaIni = $_POST['fecha_ini'];
$fechaFin = $_POST['fecha_fin'];

$query = "SELECT date_format(c.fecha, '%d/%m/%Y %H:%i') AS fechaHora , c.status, c.total, c.medio_pago, CONCAT(cli.nombres,' ',cli.apellidos) AS cliente
FROM compra AS c
INNER JOIN clientes AS cli ON c.id_cliente = cli.id
WHERE DATE(c.fecha) BETWEEN ? AND ?
ORDER BY c.fecha ASC";
$resultado = $con->prepare($query);
$resultado->execute([$fechaIni, $fechaFin]);

$datos = [
    'fechaIni' => ordenarFecha($fechaIni),
    'fechaFin' => ordenarFecha($fechaFin),
];

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter', $datos);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(30, 6, $row['fechaHora'], 1, 0);
    $pdf->Cell(30, 6, $row['status'], 1, 0);
    $pdf->Cell(60, 6, mb_convert_encoding($row['cliente'], 'ISO-8859-1', 'UTF-8'), 1, 0);
    $pdf->Cell(30, 6, $row['total'], 1, 0);
    $pdf->Cell(30, 6, $row['medio_pago'], 1, 1);
}

$pdf->Output();
