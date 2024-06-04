<?php

/**
 * Plantila de FPDF para genera reporte de compras
 * Autor: WifiTeam
 */

require 'fpdf.php';

class PDF extends FPDF
{
    private $fechaIni;
    private $fechaFin;

    public function __construct($orientacion, $medidas, $tamanio, $datos)
    {
        parent::__construct($orientacion, $medidas, $tamanio);
        $this->fechaIni = $datos['fechaIni'];
        $this->fechaFin = $datos['fechaFin'];
    }

    // Cabecera de página
    public function Header()
    {
        // Logo
        $this->Image('../images/logo.png', 10, 5, 20);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 11);

        // Título
        $y = $this->GetY();
        $this->SetX(30);
        $this->MultiCell(130, 8, 'Reporte de compras', 0, 'C');

        $this->SetFont('Arial', '', 9);
        $this->SetX(30);
        $this->MultiCell(130, 5, 'Del ' . $this->fechaIni . ' al '. $this->fechaFin, 0, 'C');

        $this->SetXY(160, $y);
        $this->Cell(40, 10, 'Fecha: ' . date('d/m/Y'), 0, 'L');

        // Salto de línea
        $this->Ln(15);

        $this->SetFont('Arial', 'B', 9);
        $this->Cell(30, 6, 'Fecha', 1, 0);
        $this->Cell(30, 6, 'Estatus', 1, 0);
        $this->Cell(60, 6, 'Cliente', 1, 0);
        $this->Cell(30, 6, 'Total', 1, 0);
        $this->Cell(30, 6, 'Medio de pago', 1, 1);
        $this->SetFont('Arial', '', 9);
    }

    // Pie de página
    public function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, mb_convert_encoding('Página ', 'ISO-8859-1', 'UTF-8') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
