<?php
namespace App\Core;

final class Csrf {
    public static function token(): string {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }
    public static function input(): string {
        return '<input type="hidden" name="csrf" value="' . htmlspecialchars(self::token(), ENT_QUOTES) . '">';
    }
    public static function check(): void {
        if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
            http_response_code(400);
            die('Bad CSRF token');
        }
    }
}
