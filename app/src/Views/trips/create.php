<?php use App\Core\{Csrf, Helpers}; ?>
<div class="card">
  <h3>Новая поездка</h3>
  <?php if (!empty($error)): ?><div class="card" style="border:1px solid #b33"><?= Helpers::h($error) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" action="/trips">
    <?= Csrf::input() ?>
    <label>Название *</label><input name="title" required>
    <label>Описание</label><textarea name="description" rows="4"></textarea>

    <div class="grid">
      <div><label>Широта</label><input name="latitude" id="lat" placeholder="48.8584"></div>
      <div><label>Долгота</label><input name="longitude" id="lon" placeholder="2.2945"></div>
    </div>
    <button type="button" class="secondary" id="geolocate">Определить мою геопозицию</button>

    <label>Название локации</label><input name="location_name" placeholder="Эйфелева башня">
    <label>Изображения (несколько)</label><input type="file" name="photos[]" accept="image/*" multiple>
    <label>Стоимость путешествия</label><input name="cost" type="number" step="0.01" min="0">
    <label>Места культурного наследия</label><textarea name="heritage_sites" rows="3"></textarea>
    <label>Места для посещения</label><textarea name="places_to_visit" rows="3"></textarea>

    <div class="grid">
      <div><label>Удобство (1–5)</label><input name="rating_convenience" type="number" min="1" max="5"></div>
      <div><label>Безопасность (1–5)</label><input name="rating_safety" type="number" min="1" max="5"></div>
      <div><label>Плотность населения (1–5)</label><input name="rating_population" type="number" min="1" max="5"></div>
      <div><label>Растительность (1–5)</label><input name="rating_vegetation" type="number" min="1" max="5"></div>
    </div>

    <div class="form-check">
      <input type="checkbox" name="is_public" id="is_public" checked>
      <label for="is_public">Сделать запись публичной</label>
    </div>


    <button>Сохранить</button>
  </form>
</div>

<script>
const btn = document.getElementById("geolocate");
if (btn) {
  btn.addEventListener("click", () => {
    if (!navigator.geolocation) { alert("Геолокация не поддерживается"); return; }
    navigator.geolocation.getCurrentPosition(pos => {
      document.getElementById("lat").value = pos.coords.latitude.toFixed(6);
      document.getElementById("lon").value = pos.coords.longitude.toFixed(6);
    }, err => { alert("Не удалось получить геопозицию: " + err.message); });
  });
}
</script>
