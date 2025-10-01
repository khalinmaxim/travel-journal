<?php
use App\Core\{Auth, Csrf, View, Helpers};
$user = Auth::user();
?><!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Дневник путешествий</title>
  <link rel="stylesheet" href="/css/style.css?v=1">
</head>
<body>
<header>
  <div><a href="/">Дневник путешествий</a></div>
  <nav>
    <a href="/trips">Все поездки</a>
    <?php if ($user): ?>
      <a href="/trips/create">Добавить поездку</a>
      <span class="badge"><?= Helpers::h($user['username']) ?></span>
      <form style="display:inline" method="post" action="/logout">
        <?= Csrf::input() ?>
        <button class="secondary">Выйти</button>
      </form>
    <?php else: ?>
      <a href="/login">Войти</a>
      <a href="/register">Регистрация</a>
    <?php endif; ?>
  </nav>
</header>
<div class="container">
  <?php
    // $currentView приходит из View::render()
    // прокидываем все доступные переменные (включая те, что переданы в render)
    View::include($currentView, get_defined_vars());
  ?>
</div>
</body>
</html>
