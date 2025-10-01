<?php
namespace App\Models;

use App\Core\Database;

final class User {
    public static function findByUsername(string $u): ?array {
        $stmt = Database::pdo()->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
        $stmt->execute([$u]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
    public static function create(string $u, string $password): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::pdo()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?) RETURNING id');
        $stmt->execute([$u, $hash]);
        return (int)$stmt->fetchColumn();
    }
}
