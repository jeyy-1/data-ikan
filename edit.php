<?php
require_once __DIR__ . '/_bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('ID tidak valid.'); }

$stmt = $pdo->prepare("SELECT * FROM ikan WHERE id = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();
if (!$data) { http_response_code(404); exit('Data ikan tidak ditemukan.'); }

$nama = $data['nama']; $jenis = $data['jenis']; $habitat = $data['habitat'];
$harga = $data['harga']; $stok = $data['stok']; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nama = trim($_POST['nama'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $habitat = trim($_POST['habitat'] ?? '');
    $harga = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_FLOAT);
    $stok = filter_input(INPUT_POST, 'stok', FILTER_VALIDATE_INT);

    if (mb_strlen($nama) < 3) $errors['nama'] = 'Nama ikan minimal 3 karakter.';
    elseif (mb_strlen($nama) > 100) $errors['nama'] = 'Nama ikan maksimal 100 karakter.';
    if ($jenis === '') $errors['jenis'] = 'Jenis ikan wajib diisi.';
    if (!in_array($habitat, ['Air tawar','Air laut','Air payau'], true)) $errors['habitat'] = 'Habitat tidak valid.';
    if ($harga === false || $harga === null || $harga <= 0) $errors['harga'] = 'Harga harus lebih besar dari 0.';
    if ($stok === false || $stok === null || $stok < 0) $errors['stok'] = 'Stok tidak boleh negatif.';

    if (!$errors) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM ikan WHERE nama = :nama AND id <> :id");
        $check->execute(['nama' => $nama, 'id' => $id]);
        if ((int)$check->fetchColumn() > 0) $errors['nama'] = 'Nama ikan sudah digunakan.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "UPDATE ikan SET nama=:nama, jenis=:jenis, habitat=:habitat, harga=:harga, stok=:stok
             WHERE id=:id"
        );
        $stmt->execute(compact('nama','jenis','habitat','harga','stok','id'));
        flash('success', 'Data ikan berhasil diperbarui.');
        redirect('index.php?status=updated');
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Data Ikan</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<header class="header"><div class="container header-inner"><div><h1>Edit Data Ikan</h1><p>Perbarui informasi ikan.</p></div><a class="btn light" href="index.php">← Kembali</a></div></header>
<main class="container narrow"><section class="form-card"><form method="POST">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="form-group">
<label for="nama">Nama Ikan</label>
<input id="nama" name="nama" value="<?= e($nama) ?>" minlength="3" maxlength="100" required>
<?php if (isset($errors['nama'])): ?><div class="error"><?= e($errors['nama']) ?></div><?php endif; ?>
</div>

<div class="form-group">
<label for="jenis">Jenis / Nama Ilmiah</label>
<input id="jenis" name="jenis" value="<?= e($jenis) ?>" maxlength="60" required>
<?php if (isset($errors['jenis'])): ?><div class="error"><?= e($errors['jenis']) ?></div><?php endif; ?>
</div>

<div class="form-group">
<label for="habitat">Habitat</label>
<select id="habitat" name="habitat" required>
  <?php foreach (['Air tawar','Air laut','Air payau'] as $h): ?>
    <option value="<?= e($h) ?>" <?= $habitat === $h ? 'selected' : '' ?>><?= e($h) ?></option>
  <?php endforeach; ?>
</select>
<?php if (isset($errors['habitat'])): ?><div class="error"><?= e($errors['habitat']) ?></div><?php endif; ?>
</div>

<div class="form-row">
<div class="form-group">
<label for="harga">Harga</label>
<input id="harga" name="harga" type="number" step="0.01" min="0.01" value="<?= e((string)$harga) ?>" required>
<?php if (isset($errors['harga'])): ?><div class="error"><?= e($errors['harga']) ?></div><?php endif; ?>
</div>
<div class="form-group">
<label for="stok">Stok</label>
<input id="stok" name="stok" type="number" min="0" value="<?= e((string)$stok) ?>" required>
<?php if (isset($errors['stok'])): ?><div class="error"><?= e($errors['stok']) ?></div><?php endif; ?>
</div>
</div>

<div class="actions"><a class="btn light" href="index.php">Batal</a><button class="btn primary" type="submit">Simpan Perubahan</button></div>
</form></section></main>
</body></html>
