<?php use App\Core\Helpers; ?>

<div class="card">
  <h3>Публичные поездки пользователей</h3>
  <?php if (!$publicTrips): ?><p class="small">Нет записей</p><?php endif; ?>
  <div class="grid">
    <?php foreach ($publicTrips as $t): ?>
      <div class="card">
        <div class="trip-title"><?= Helpers::h($t['title']) ?></div>
        <div class="small">Автор: <?= Helpers::h($t['username']) ?> · <?= Helpers::h(date('d.m.Y H:i', strtotime($t['created_at']))) ?></div>
        <?php if ($t['location_name']): ?><div class="badge"><?= Helpers::h($t['location_name']) ?></div><?php endif; ?>
        <?php if ($t['latitude'] && $t['longitude']): ?>
          <div class="small">Lat: <?= Helpers::h($t['latitude']) ?>, Lon: <?= Helpers::h($t['longitude']) ?></div>
        <?php endif; ?>
        <?php if ($t['cost'] !== null): ?><div class="small">Стоимость: <?= Helpers::h($t['cost']) ?></div><?php endif; ?>
        <?php $d = $t['description'] ?? ''; ?>
        <p><?= nl2br(Helpers::h(function_exists('mb_strimwidth') ? mb_strimwidth($d,0,180,'…') : substr($d,0,180).'…')) ?></p>
        <a class="badge" href="/trips/<?= (int)$t['id'] ?>">Открыть</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php if (!empty($user)): ?>
<div class="card">
  <h3>Мои поездки</h3>
  <?php if (!$myTrips): ?><p class="small">Пока нет записей</p><?php endif; ?>
  <div class="grid">
    <?php foreach ($myTrips as $t): ?>
      <div class="card">
        <div class="trip-title"><?= Helpers::h($t['title']) ?></div>
        <div class="small"><?= Helpers::h(date('d.m.Y H:i', strtotime($t['created_at']))) ?></div>
        <?php $d = $t['description'] ?? ''; ?>
        <p><?= nl2br(Helpers::h(function_exists('mb_strimwidth') ? mb_strimwidth($d,0,180,'…') : substr($d,0,180).'…')) ?></p>
        <a class="badge" href="/trips/<?= (int)$t['id'] ?>">Открыть</a>
        <?= $t['is_public'] ? '<span class="badge">Публично</span>' : '<span class="badge">Приватно</span>' ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
