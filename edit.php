<?php
require_once 'db_config.php';

$nim = trim($_GET['nim'] ?? '');

$stmt = $pdo->prepare('SELECT * FROM mahasiswa WHERE nim = :nim');
$stmt->execute([':nim' => $nim]);
$mhs = $stmt->fetch();

if (!$mhs) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Mahasiswa | Sistem Informasi Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #4f46e5 100%);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .hero-header {
            background: var(--primary-gradient);
            padding: 2.25rem 0 4.5rem;
            color: #fff;
        }
        .main-wrapper {
            margin-top: -3.25rem;
            flex: 1;
        }
        .form-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.1);
            overflow: hidden;
        }
        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #475569;
        }
        .form-control {
            border-color: #cbd5e1;
            padding: 0.65rem 0.9rem;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .btn-warning-gradient {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: 0;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 8px 18px -4px rgba(245, 158, 11, 0.4);
        }
        .btn-warning-gradient:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="hero-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-pencil-square fs-3 text-white"></i>
                    </div>
                    <div>
                        <span class="badge bg-white bg-opacity-25 text-white mb-1 fw-medium">Pembaruan Data</span>
                        <h3 class="fw-bold mb-0">Edit Data Mahasiswa</h3>
                    </div>
                </div>
                <a href="index.php" class="btn btn-light fw-semibold rounded-3 px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </header>

    <main class="container main-wrapper pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card form-card bg-white">
                    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">Perbarui Informasi Mahasiswa</h5>
                            <p class="text-muted small mb-0">Ubah data yang diperlukan lalu klik tombol Simpan Perubahan.</p>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            NIM: <?= htmlspecialchars($mhs['nim']) ?>
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_edit.php" method="post">
                            <input type="hidden" name="nim" value="<?= htmlspecialchars($mhs['nim']) ?>">

                            <div class="mb-3">
                                <label for="nim" class="form-label fw-semibold">Nomor Induk Mahasiswa (NIM)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="text" class="form-control bg-light" id="nim"
                                           value="<?= htmlspecialchars($mhs['nim']) ?>" disabled>
                                </div>
                                <div class="form-text">NIM bersifat permanen (Primary Key) dan tidak dapat diubah.</div>
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" class="form-control" id="nama" name="nama" maxlength="100"
                                           value="<?= htmlspecialchars($mhs['nama']) ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="program_studi" class="form-label fw-semibold">Program Studi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                    <input type="text" class="form-control" id="program_studi" name="program_studi"
                                           list="daftarProdi" maxlength="100"
                                           value="<?= htmlspecialchars($mhs['program_studi']) ?>" required>
                                    <datalist id="daftarProdi">
                                        <option value="Teknik Informatika">
                                        <option value="Sistem Informasi">
                                        <option value="Teknik Komputer">
                                        <option value="Manajemen Informatika">
                                        <option value="Rekayasa Perangkat Lunak">
                                    </datalist>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-semibold">Alamat Domisili</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                              required><?= htmlspecialchars($mhs['alamat']) ?></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="index.php" class="btn btn-light px-4 fw-semibold">Batal</a>
                                <button type="submit" class="btn btn-warning-gradient">
                                    <i class="bi bi-check2-circle me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center text-muted small py-3 border-top bg-white">
        &copy; <?= date('Y') ?> Sistem Informasi Data Mahasiswa &mdash; Praktikum Keamanan Sistem Informasi
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
