<?php use App\Core\Helpers; ?>
<div class="card">
  <div class="trip-title"><?= Helpers::h($t['title']) ?></div>
  <div class="small">Автор: <?= Helpers::h($t['username']) ?> · <?= Helpers::h(date('d.m.Y H:i', strtotime($t['created_at']))) ?></div>
  <?php if ($t['location_name']): ?><div class="badge"><?= Helpers::h($t['location_name']) ?></div><?php endif; ?>
  <?php if ($t['cost'] !== null): ?><div class="badge">Стоимость: <?= Helpers::h($t['cost']) ?></div><?php endif; ?>
  <p><?= nl2br(Helpers::h($t['description'] ?? '')) ?></p>

  <?php if (!empty($imgs)): ?>
    <div class="grid">
    <?php foreach ($imgs as $img): $src = $img['path']; ?>
      <div><img class="thumb" loading="lazy" src="<?= Helpers::h($src) ?>" alt="Фото"></div>
    <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php
    $ratings = [
      'Удобство' => (int)$t['rating_convenience'],
      'Безопасность' => (int)$t['rating_safety'],
      'Плотность населения' => (int)$t['rating_population'],
      'Растительность' => (int)$t['rating_vegetation'],
    ];
  ?>
  <hr>
  <div class="grid">
    <?php foreach ($ratings as $label => $val): if ($val): ?>
      <div><strong><?= Helpers::h($label) ?>:</strong> <?= str_repeat('★', $val) . str_repeat('☆', 5-$val) ?></div>
    <?php endif; endforeach; ?>
  </div>

  <?php if ($t['latitude'] && $t['longitude']): ?>
    <?php
      $lat = (float)$t['latitude']; $lon = (float)$t['longitude'];
      $delta = 0.01;
      $bbox = ($lon-$delta) . ',' . ($lat-$delta) . ',' . ($lon+$delta) . ',' . ($lat+$delta);
      $marker = $lat . ',' . $lon;
      $mapSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=' . rawurlencode($bbox) . '&layer=mapnik&marker=' . rawurlencode($marker);
    ?>
    <div class="map"><iframe src="<?= Helpers::h($mapSrc) ?>" loading="lazy"></iframe></div>
    <div class="small">Координаты: <?= Helpers::h($lat) ?>, <?= Helpers::h($lon) ?></div>
  <?php endif; ?>
</div>
