<?php use App\Core\{Csrf, Helpers}; ?>
<div class="card">
  <h3>Войти</h3>
  <?php if (!empty($error)): ?><div class="card" style="border:1px solid #b33"><?= Helpers::h($error) ?></div><?php endif; ?>
  <form method="post" action="/login">
    <?= Csrf::input() ?>
    <label>Логин</label><input name="username" required>
    <label>Пароль</label><input name="password" type="password" required>
    <button>Войти</button>
  </form>
</div>
