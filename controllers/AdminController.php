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
            "stats" => $stats,
            "statsJson" => json_encode($stats)
        ]);
    }

    public function generateReport()
    {
        $this->checkAdmin();
        $stats = $this->model->getStatistics();

        require_once __DIR__ . '/../vendor/autoload.php';

        // Prepare HTML content
        $html = '
        <html>
        <head>
            <style>
                body { font-family: sans-serif; }
                h1 { text-align: center; color: #333; }
                h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; color: #555; }
                .stat-item { margin-bottom: 10px; font-size: 14px; }
                .label { font-weight: bold; }
            </style>
        </head>
        <body>
            <h1>Reporte de Estadísticas - Preguntados</h1>
            
            <h2>Estadísticas Generales</h2>
            <div class="stat-item"><span class="label">Total de Partidas Jugadas:</span> ' . $stats['total_games'] . '</div>
            <div class="stat-item"><span class="label">Puntaje Total Acumulado:</span> ' . $stats['total_score'] . '</div>
            <div class="stat-item"><span class="label">Cantidad de Jugadores Registrados:</span> ' . $stats['total_players'] . '</div>
            <div class="stat-item"><span class="label">Cantidad de Jugadores Registrados:</span> ' . $stats['total_players'] . '</div>
            <div class="stat-item"><span class="label">Cantidad de Preguntas en el Sistema:</span> ' . $stats['total_questions'] . '</div>
            <div class="stat-item" style="margin-left: 20px;"><span class="label">- Activas:</span> ' . $stats['active_questions'] . '</div>
            <div class="stat-item" style="margin-left: 20px;"><span class="label">- Sugeridas:</span> ' . $stats['suggested_questions'] . '</div>
            <div class="stat-item" style="margin-left: 20px;"><span class="label">- Rechazadas:</span> ' . $stats['rejected_questions'] . '</div>

            <h2>Jugadores por Nivel de Dificultad</h2>
            <ul>';
        
        foreach ($stats['difficulty_levels'] as $level => $count) {
            $html .= '<li><span class="label">' . htmlspecialchars($level) . ':</span> ' . $count . ' jugadores</li>';
        }

        $html .= '
            </ul>
            <p style="text-align: center; margin-top: 50px; font-size: 12px; color: #999;">Generado el ' . date('d/m/Y H:i') . '</p>
        </body>
        </html>';

        // Instantiate Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte_estadisticas.pdf', ['Attachment' => true]);
    }
}
