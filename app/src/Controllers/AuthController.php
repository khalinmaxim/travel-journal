<?php
namespace App\Controllers;

use App\Core\{View, Helpers, Csrf, Auth};
use App\Models\User;

final class AuthController {
    public function loginForm(): void {
        View::render('auth/login', ['error' => null]);
    }
    public function login(): void {
        Csrf::check();
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $u = User::findByUsername($username);
        if ($u && password_verify($password, $u['password_hash'])) {
            $_SESSION['user'] = ['id' => $u['id'], 'username' => $u['username']];
            Helpers::redirect('/');
        }
        View::render('auth/login', ['error' => 'Неверные логин или пароль.']);
    }

    public function registerForm(): void {
        View::render('auth/register', ['error' => null]);
    }
    public function register(): void {
        Csrf::check();
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            View::render('auth/register', ['error' => 'Заполните логин и пароль.']); return;
        }
        if (User::findByUsername($username)) {
            View::render('auth/register', ['error' => 'Пользователь уже существует.']); return;
        }
        $id = User::create($username, $password);
        $_SESSION['user'] = ['id' => $id, 'username' => $username];
        Helpers::redirect('/');
    }

    public function logout(): void {
        Csrf::check();
        session_destroy();
        Helpers::redirect('/');
    }
}
