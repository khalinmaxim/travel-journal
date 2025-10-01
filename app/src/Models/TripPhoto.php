<?php
namespace App\Models;

use App\Core\Database;

final class TripPhoto {
    public static function byTrip(int $tripId): array {
        $st = Database::pdo()->prepare('SELECT path FROM trip_photos WHERE trip_id = ?');
        $st->execute([$tripId]);
        return $st->fetchAll();
    }
}
