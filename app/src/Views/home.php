<?php use App\Core\Helpers; ?>

<div class="card">
  <h2 style="margin:0 0 8px 0;">Добро пожаловать!</h2>
  <p style="margin:0">Это «Дневник путешествий» на PHP + PostgreSQL (MVC).</p>
</div>

<div class="card">
  <h3 style="margin-top:0">Публичные путешествия</h3>
  <?php if (empty($publicTrips)): ?>
    <p class="small">Пока нет записей</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($publicTrips as $t): ?>
        <div class="card">
          <div class="trip-title"><?= Helpers::h($t['title']) ?></div>
          <div class="small">Автор: <?= Helpers::h($t['username']) ?> · <?= Helpers::h(date('d.m.Y H:i', strtotime($t['created_at']))) ?></div>
          <?php if ($t['location_name']): ?><div class="badge"><?= Helpers::h($t['location_name']) ?></div><?php endif; ?>
          <?php if ($t['cost'] !== null): ?><div class="badge">Стоимость: <?= Helpers::h($t['cost']) ?></div><?php endif; ?>
          <?php $d = $t['description'] ?? ''; ?>
          <p><?= nl2br(Helpers::h(function_exists('mb_strimwidth') ? mb_strimwidth($d,0,160,'…') : substr($d,0,160).'…')) ?></p>
          <a class="badge" href="/trips/<?= (int)$t['id'] ?>">Открыть</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php if (!empty($user)): ?>
<div class="card">
  <h3 style="margin-top:0">Мои путешествия</h3>
  <?php if (empty($myTrips)): ?>
    <p class="small">Пока нет записей</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($myTrips as $t): ?>
        <div class="card">
          <div class="trip-title"><?= Helpers::h($t['title']) ?></div>
          <div class="small"><?= Helpers::h(date('d.m.Y H:i', strtotime($t['created_at']))) ?></div>
          <?php $d = $t['description'] ?? ''; ?>
          <p><?= nl2br(Helpers::h(function_exists('mb_strimwidth') ? mb_strimwidth($d,0,160,'…') : substr($d,0,160).'…')) ?></p>
          <a class="badge" href="/trips/<?= (int)$t['id'] ?>">Открыть</a>
          <?= $t['is_public'] ? '<span class="badge">Публично</span>' : '<span class="badge">Приватно</span>' ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
