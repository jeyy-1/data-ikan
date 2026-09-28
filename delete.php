<?php
require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Metode request tidak diizinkan.');
}

verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('ID tidak valid.'); }

$stmt = $pdo->prepare("DELETE FROM ikan WHERE id = :id");
$stmt->execute(['id' => $id]);

flash($stmt->rowCount() ? 'success' : 'warning',
      $stmt->rowCount() ? 'Data ikan berhasil dihapus.' : 'Data ikan tidak ditemukan.');
redirect('index.php?status=deleted');
