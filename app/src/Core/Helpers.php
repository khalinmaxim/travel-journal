<?php
namespace App\Core;

final class Helpers {
    public static function h($v): string {
        return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
    public static function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}
