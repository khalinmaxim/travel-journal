<?php
namespace App\Core;

use PDO, PDOException;

final class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (self::$pdo) return self::$pdo;
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            Config::get('db.host'), Config::get('db.port'), Config::get('db.name')
        );
        try {
            self::$pdo = new PDO($dsn, Config::get('db.user'), Config::get('db.pass'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die('DB connection error: ' . htmlspecialchars($e->getMessage()));
        }
        return self::$pdo;
    }
}
