<?php
namespace App\Core;

final class View {
    public static function render(string $tpl, array $data = []): void {
        extract($data, EXTR_SKIP);
        $tplPath = __DIR__ . '/../Views/' . $tpl . '.php';
        if (!is_file($tplPath)) { http_response_code(500); echo 'View not found: ' . htmlspecialchars($tpl); return; }
        // прокидываем имя шаблона в layout
        $currentView = $tpl;
        require __DIR__ . '/../Views/layout.php';
    }

    // Внутренний include из layout
    public static function include(string $tpl, array $data = []): void {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $tpl . '.php';
    }
}
