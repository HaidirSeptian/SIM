<?php
require_once 'db_config.php';

$nim = trim($_GET['nim'] ?? '');

if ($nim === '') {
    header('Location: index.php?status=kosong');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE nim = :nim');
    $stmt->execute([':nim' => $nim]);
    header('Location: index.php?status=hapus_sukses');
} catch (PDOException $e) {
    header('Location: index.php?status=hapus_gagal');
}
exit;
