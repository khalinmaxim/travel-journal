<?php
namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Models\Trip;

final class HomeController {
    public function index(): void {
        $user = Auth::user();

        // Публичные всех (кроме вас) + ваши приватные и публичные
        $publicTrips = Trip::publicExcept($user['id'] ?? null, 100);
        $myTrips = $user ? Trip::byUser($user['id'], 100) : [];

        // Можно объединить в один список, но оставим два блока — понятнее
        View::render('home', [
            'publicTrips' => $publicTrips,
            'myTrips' => $myTrips,
            'user' => $user,
        ]);
    }
}
