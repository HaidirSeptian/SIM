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
        'INSERT INTO mahasiswa (nim, nama, program_studi, alamat)
         VALUES (:nim, :nama, :program_studi, :alamat)'
    );
    $stmt->execute([
        ':nim'           => $nim,
        ':nama'          => $nama,
        ':program_studi' => $program_studi,
        ':alamat'        => $alamat,
    ]);
    header('Location: index.php?status=tambah_sukses');
} catch (PDOException $e) {
    $status = $e->getCode() === '23000' ? 'nim_duplikat' : 'tambah_gagal';
    header("Location: index.php?status=$status");
}
exit;
