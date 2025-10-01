<?php
namespace App\Controllers;

use App\Core\{View, Helpers, Auth, Csrf};
use App\Models\{Trip, TripPhoto};

final class TripsController {
    public function index(): void {
        $user = Auth::user();
        $public = Trip::publicExcept($user['id'] ?? null);
        $mine = $user ? Trip::byUser($user['id']) : [];
        View::render('trips/index', ['publicTrips' => $public, 'myTrips' => $mine, 'user' => $user]);
    }

    public function createForm(): void {
        Auth::requireAuth();
        View::render('trips/create', ['error' => null]);
    }

    public function store(): void {
        Auth::requireAuth();
        Csrf::check();

        $title = trim($_POST['title'] ?? '');
        if ($title === '') { View::render('trips/create', ['error' => 'Название обязательно']); return; }

        $data = [
            'user_id' => Auth::user()['id'],
            'title'   => $title,
            'description' => trim($_POST['description'] ?? ''),
            'latitude' => $_POST['latitude'] !== '' ? (float)$_POST['latitude'] : null,
            'longitude'=> $_POST['longitude'] !== '' ? (float)$_POST['longitude'] : null,
            'location_name' => trim($_POST['location_name'] ?? ''),
            'cost' => $_POST['cost'] !== '' ? (float)$_POST['cost'] : null,
            'heritage_sites' => trim($_POST['heritage_sites'] ?? ''),
            'places_to_visit'=> trim($_POST['places_to_visit'] ?? ''),
            'rating_convenience' => (int)($_POST['rating_convenience'] ?? 0),
            'rating_safety'      => (int)($_POST['rating_safety'] ?? 0),
            'rating_population'  => (int)($_POST['rating_population'] ?? 0),
            'rating_vegetation'  => (int)($_POST['rating_vegetation'] ?? 0),
            'is_public' => isset($_POST['is_public']),
            'photos' => [],
        ];

        // Загрузка файлов
        if (!empty($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
            $total = count($_FILES['photos']['name']);
            $dir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($dir)) { mkdir($dir, 0775, true); }
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            for ($i=0; $i<$total; $i++) {
                if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $tmp = $_FILES['photos']['tmp_name'][$i];
                $mime = $finfo->file($tmp);
                if (!in_array($mime, ['image/jpeg','image/png','image/gif','image/webp'])) continue;
                $ext = match($mime) {
                    'image/jpeg'=>'.jpg','image/png'=>'.png','image/gif'=>'.gif','image/webp'=>'.webp', default=>'',
                };
                $name = bin2hex(random_bytes(8)) . $ext;
                $dest = $dir . $name;
                if (move_uploaded_file($tmp, $dest)) {
                    $data['photos'][] = '/uploads/' . $name;
                }
            }
        }

        try {
            $id = \App\Models\Trip::create($data);
            Helpers::redirect('/trips/' . $id);
        } catch (\Throwable $e) {
            View::render('trips/create', ['error' => 'Ошибка сохранения: ' . Helpers::h($e->getMessage())]);
        }
    }

    public function show(array $params): void {
        $id = (int)($params['id'] ?? 0);
        $t = \App\Models\Trip::find($id);
        if (!$t) { http_response_code(404); echo 'Не найдено'; return; }
        $imgs = TripPhoto::byTrip($id);
        View::render('trips/show', ['t' => $t, 'imgs' => $imgs]);
    }
}
