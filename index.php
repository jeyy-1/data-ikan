<?php
require_once __DIR__ . '/_bootstrap.php';

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $stmt = $pdo->prepare(
        "SELECT id, nama, jenis, habitat, harga, stok, created_at
         FROM ikan
         WHERE nama LIKE :q OR jenis LIKE :q OR habitat LIKE :q
         ORDER BY id DESC"
    );
    $stmt->execute(['q' => "%{$q}%"]);
} else {
    $stmt = $pdo->query(
        "SELECT id, nama, jenis, habitat, harga, stok, created_at
         FROM ikan ORDER BY id DESC"
    );
}

$dataIkan = $stmt->fetchAll();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Ikan</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="header">
  <div class="container header-inner">
    <div>
      <h1>🐟 Data Ikan</h1>
      <p>Sistem informasi data ikan berbasis PHP & MySQL</p>
    </div>
    <a class="btn primary" href="create.php">+ Tambah Data Ikan</a>
  </div>
</header>

<main class="container">
<?php if ($flash): ?>
  <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>

<section class="toolbar">
  <div>
    <h2>Daftar Ikan</h2>
    <p><?= count($dataIkan) ?> data ditemukan.</p>
  </div>
  <form method="GET" class="search">
    <input name="q" value="<?= e($q) ?>" placeholder="Cari nama, jenis, habitat...">
    <button class="btn" type="submit">Cari</button>
    <?php if ($q !== ''): ?><a class="btn light" href="index.php">Reset</a><?php endif; ?>
  </form>
</section>

<?php if (!$dataIkan): ?>
  <section class="empty">
    <h3>Data ikan belum tersedia</h3>
    <p>Silakan tambahkan data ikan baru.</p>
    <a class="btn primary" href="create.php">Tambah Data</a>
  </section>
<?php else: ?>
<section class="cards">
<?php foreach ($dataIkan as $ikan): ?>
  <article class="card">
    <div class="top">
      <span class="badge"><?= e($ikan['habitat']) ?></span>
      <span>Stok: <?= (int)$ikan['stok'] ?></span>
    </div>
    <h3><?= e($ikan['nama']) ?></h3>
    <p class="latin"><?= e($ikan['jenis']) ?></p>
    <div class="price">Rp <?= number_format((float)$ikan['harga'], 0, ',', '.') ?></div>
    <p class="date">Ditambahkan: <?= e(date('d-m-Y H:i', strtotime($ikan['created_at']))) ?></p>
    <div class="actions">
      <a class="btn" href="edit.php?id=<?= (int)$ikan['id'] ?>">Edit</a>
      <form method="POST" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus data ikan ini?');">
        <input type="hidden" name="id" value="<?= (int)$ikan['id'] ?>">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <button class="btn danger" type="submit">Hapus</button>
      </form>
    </div>
  </article>
<?php endforeach; ?>
</section>
<?php endif; ?>
</main>
<footer class="footer">Tugas Akhir Pemrograman Web • Sistem Data Ikan</footer>
</body>
</html>
