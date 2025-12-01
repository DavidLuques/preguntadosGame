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

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream('reporte_estadisticas.pdf', ['Attachment' => true]);
    }

    public function users()
    {
        $this->checkAdmin();
        $users = $this->model->getAllUsers();
        
        foreach ($users as &$user) {
            $user['isEditor'] = ($user['rol'] === 'editor');
            $user['isPlayer'] = ($user['rol'] === 'JUGADOR');
            $user['canModify'] = ($user['rol'] !== 'ADMIN'); 
        }

        $this->renderer->render("admin/users", ["users" => $users]);
    }

    public function updateRole()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $newRole = $_POST['new_role'];

            if ($newRole !== 'JUGADOR' && $newRole !== 'editor') {
                header("Location: /admin/users");
                exit();
            }

            if ($userId == $_SESSION['usuario_id']) {
                 header("Location: /admin/users?error=self_modification");
                 exit();
            }

            $this->model->updateUserRole($userId, $newRole);
            header("Location: /admin/users?success=1");
        }
    }
}
