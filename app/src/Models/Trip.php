<?php
namespace App\Models;

use App\Core\Database;

final class Trip {
    public static function publicExcept(?int $userId, int $limit = 100): array {
        $sql = 'SELECT t.*, u.username
                FROM trips t JOIN users u ON u.id = t.user_id
                WHERE t.is_public = TRUE';
        $params = [];
        if ($userId) { $sql .= ' AND t.user_id <> ?'; $params[] = $userId; }
        $sql .= ' ORDER BY t.created_at DESC LIMIT ' . (int)$limit;
        $st = Database::pdo()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public static function byUser(int $userId, int $limit=100): array {
        $st = Database::pdo()->prepare('SELECT * FROM trips WHERE user_id = ? ORDER BY created_at DESC LIMIT ' . (int)$limit);
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public static function find(int $id): ?array {
        $st = Database::pdo()->prepare('SELECT t.*, u.username FROM trips t JOIN users u ON u.id = t.user_id WHERE t.id = ?');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $st = $pdo->prepare('
                INSERT INTO trips (user_id, title, description, latitude, longitude, location_name, cost,
                                   heritage_sites, places_to_visit, rating_convenience, rating_safety,
                                   rating_population, rating_vegetation, is_public)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING id
            ');
            $st->execute([
                $data['user_id'], $data['title'], $data['description'],
                $data['latitude'], $data['longitude'], $data['location_name'],
                $data['cost'], $data['heritage_sites'], $data['places_to_visit'],
                $data['rating_convenience'], $data['rating_safety'],
                $data['rating_population'], $data['rating_vegetation'],
                $data['is_public'] ? 1 : 0
            ]);
            $trip_id = (int)$st->fetchColumn();

            // фото
            foreach ($data['photos'] as $path) {
                $pdo->prepare('INSERT INTO trip_photos (trip_id, path) VALUES (?,?)')
                    ->execute([$trip_id, $path]);
            }

            $pdo->commit();
            return $trip_id;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
