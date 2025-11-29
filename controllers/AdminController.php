<?php

class AdminController
{
    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    private function checkAdmin()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ADMIN') {
            header("Location: /");
            exit();
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $stats = $this->model->getStatistics();
        
        $this->renderer->render("admin/dashboard", [
            "stats" => $stats
        ]);
    }

    public function generateReport()
    {
        $this->checkAdmin();
        $stats = $this->model->getStatistics();

        require_once __DIR__ . '/../vendor/autoload.php';

        $pdf = new \Fpdf\Fpdf();
        $pdf->AddPage();
        
        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Estadísticas - Preguntados'), 0, 1, 'C');
        $pdf->Ln(10);

        // General Stats
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode('Estadísticas Generales'), 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Total de Partidas Jugadas: ' . $stats['total_games']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Puntaje Total Acumulado: ' . $stats['total_score']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Cantidad de Jugadores Registrados: ' . $stats['total_players']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Cantidad de Preguntas en el Sistema: ' . $stats['total_questions']), 0, 1);
        $pdf->Ln(10);

        // Difficulty Stats
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode('Jugadores por Nivel de Dificultad'), 0, 1);
        $pdf->SetFont('Arial', '', 12);
        
        foreach ($stats['difficulty_levels'] as $level => $count) {
            $pdf->Cell(0, 10, utf8_decode($level . ': ' . $count . ' jugadores'), 0, 1);
        }

        // Output
        $pdf->Output('D', 'reporte_estadisticas.pdf');
    }
}
