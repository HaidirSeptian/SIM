<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nim           = trim($_POST['nim'] ?? '');
$nama          = trim($_POST['nama'] ?? '');
$program_studi = trim($_POST['program_studi'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');

if ($nim === '' || $nama === '' || $program_studi === '' || $alamat === '') {
    header('Location: index.php?status=kosong');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE mahasiswa
         SET nama = :nama, program_studi = :program_studi, alamat = :alamat
         WHERE nim = :nim'
    );
    $stmt->execute([
        ':nama'          => $nama,
        ':program_studi' => $program_studi,
        ':alamat'        => $alamat,
        ':nim'           => $nim,
    ]);
    header('Location: index.php?status=edit_sukses');
} catch (PDOException $e) {
    header('Location: index.php?status=edit_gagal');
}
exit;
