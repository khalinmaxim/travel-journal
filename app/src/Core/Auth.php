<?php
namespace App\Core;

final class Auth {
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }
    public static function requireAuth(): void {
        if (!self::user()) {
            header('Location: /login');
            exit;
        }
    }
}
