<?php
require_once 'db_config.php';

$pesan = [
    'tambah_sukses' => ['success', 'bi-check-circle-fill', 'Data mahasiswa berhasil ditambahkan ke dalam sistem.'],
    'edit_sukses'   => ['success', 'bi-check-circle-fill', 'Data mahasiswa berhasil diperbarui.'],
    'hapus_sukses'  => ['success', 'bi-trash3-fill', 'Data mahasiswa berhasil dihapus dari sistem.'],
    'nim_duplikat'  => ['danger',  'bi-exclamation-triangle-fill', 'NIM sudah terdaftar! Gunakan NIM yang berbeda.'],
    'kosong'        => ['warning', 'bi-exclamation-circle-fill', 'Semua kolom wajib diisi dengan lengkap.'],
    'tambah_gagal'  => ['danger',  'bi-x-octagon-fill', 'Terjadi kesalahan saat menambahkan data mahasiswa.'],
    'edit_gagal'    => ['danger',  'bi-x-octagon-fill', 'Terjadi kesalahan saat memperbarui data mahasiswa.'],
    'hapus_gagal'   => ['danger',  'bi-x-octagon-fill', 'Terjadi kesalahan saat menghapus data mahasiswa.'],
];
$status = $_GET['status'] ?? '';

$daftarMahasiswa = $pdo->query('SELECT * FROM mahasiswa ORDER BY nim ASC')->fetchAll();
$totalMahasiswa  = count($daftarMahasiswa);
$prodiUnik       = count(array_unique(array_column($daftarMahasiswa, 'program_studi')));

function getInisial(string $nama): string {
    $kata = preg_split('/\s+/', trim($nama));
    $inisial = strtoupper(substr($kata[0] ?? 'M', 0, 1));
    if (count($kata) > 1) {
        $inisial .= strtoupper(substr(end($kata), 0, 1));
    }
    return $inisial;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mahasiswa | Sistem Informasi Akademik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #4f46e5 100%);
            --surface-bg: #f1f5f9;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .hero-header {
            background: var(--primary-gradient);
            padding: 2.5rem 0 4.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero-header::after {
            content: '';
            position: absolute;
            right: -60px;
            top: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }
        .main-wrapper {
            margin-top: -3rem;
            flex: 1;
        }
        .stat-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(15, 23, 42, 0.1);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .content-card {
            border: 0;
            border-radius: 1.15rem;
            box-shadow: 0 12px 32px -8px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            background: #fff;
        }
        .search-box .form-control {
            border-radius: 0.75rem;
            padding-left: 2.6rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .search-box .form-control:focus {
            background-color: #fff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }
        .search-box .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .table tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dbeafe, #e0e7ff);
            color: #1d4ed8;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .nim-badge {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 0.88rem;
            background-color: #f1f5f9;
            color: #334155;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        .prodi-badge {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 999px;
            font-size: 0.78rem;
        }
        .btn-action {
            border-radius: 0.6rem;
            font-weight: 600;
            padding: 0.38rem 0.75rem;
            font-size: 0.82rem;
        }
        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border: 0;
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            box-shadow: 0 6px 16px -3px rgba(37, 99, 235, 0.4);
        }
        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8, #4338ca);
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="hero-header">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-mortarboard-fill fs-3 text-white"></i>
                    </div>
                    <div>
                        <span class="badge bg-white bg-opacity-25 text-white mb-1 fw-medium">Portal Akademik</span>
                        <h3 class="fw-bold mb-0">Sistem Informasi Data Mahasiswa</h3>
                    </div>
                </div>
                <a href="tambah_mahasiswa.php" class="btn btn-light fw-semibold px-4 py-2 rounded-3 shadow-sm text-primary">
                    <i class="bi bi-plus-circle-fill me-2"></i>Tambah Mahasiswa
                </a>
            </div>
        </div>
    </header>

    <main class="container main-wrapper pb-5">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Total Mahasiswa</div>
                            <h4 class="fw-bold mb-0"><?= $totalMahasiswa ?> <span class="fs-6 fw-normal text-muted">Orang</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-indigo bg-info bg-opacity-10 text-info">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Program Studi Aktif</div>
                            <h4 class="fw-bold mb-0"><?= $prodiUnik ?> <span class="fs-6 fw-normal text-muted">Prodi</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-database-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-medium">Status Database</div>
                            <h4 class="fw-bold mb-0 fs-5 text-success">Terhubung (db_akademik)</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($pesan[$status])): ?>
            <div class="alert alert-<?= $pesan[$status][0] ?> alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi <?= $pesan[$status][1] ?> fs-5"></i>
                <div><?= $pesan[$status][2] ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card content-card">
            <div class="card-header bg-white border-0 p-4 pb-3">
                <div class="row g-3 align-items-center justify-content-between">
                    <div class="col-md-5">
                        <h5 class="fw-bold mb-1">Daftar Mahasiswa Terdaftar</h5>
                        <p class="text-muted small mb-0">Kelola data akademik mahasiswa secara cepat dan terstruktur.</p>
                    </div>
                    <div class="col-md-7 d-flex flex-wrap justify-content-md-end gap-2">
                        <div class="position-relative search-box flex-grow-1" style="max-width: 320px;">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" id="inputCari" class="form-control" placeholder="Cari NIM, nama, atau prodi...">
                        </div>
                        <a href="tambah_mahasiswa.php" class="btn btn-primary-gradient">
                            <i class="bi bi-person-plus-fill me-1"></i>Tambah Data
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0" id="tabelMahasiswa">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 65px;">No</th>
                            <th style="width: 150px;">NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Alamat Domisili</th>
                            <th class="text-center" style="width: 185px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMahasiswa)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <p class="fw-semibold mb-1">Belum ada data mahasiswa</p>
                                        <small>Klik tombol "Tambah Data" untuk menambahkan mahasiswa pertama.</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
                            <tr class="baris-data">
                                <td class="text-center fw-semibold text-secondary"><?= $i + 1 ?></td>
                                <td><span class="nim-badge"><?= htmlspecialchars($mhs['nim']) ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle"><?= getInisial($mhs['nama']) ?></div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?= htmlspecialchars($mhs['nama']) ?></div>
                                            <small class="text-muted">Mahasiswa Aktif</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="prodi-badge">
                                        <i class="bi bi-mortarboard me-1"></i><?= htmlspecialchars($mhs['program_studi']) ?>
                                    </span>
                                </td>
                                <td class="text-secondary">
                                    <i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($mhs['alamat']) ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="edit.php?nim=<?= urlencode($mhs['nim']) ?>" class="btn btn-warning btn-action me-1">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-action"
                                            data-bs-toggle="modal" data-bs-target="#modalHapus"
                                            data-nim="<?= htmlspecialchars($mhs['nim']) ?>"
                                            data-nama="<?= htmlspecialchars($mhs['nama']) ?>">
                                        <i class="bi bi-trash3 me-1"></i>Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="barisTidakDitemukan" class="d-none">
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-search me-1"></i>Data mahasiswa yang dicari tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top-0 px-4 py-3 d-flex justify-content-between align-items-center text-muted small">
                <span>Menampilkan <strong><?= $totalMahasiswa ?></strong> data mahasiswa</span>
                <span>PDO &bull; Bootstrap 5.3</span>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-trash3-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Hapus Data Mahasiswa?</h5>
                    <p class="text-muted mb-4">
                        Anda akan menghapus data <strong class="text-dark" id="namaHapus"></strong>
                        dengan NIM <code id="nimHapus"></code>. Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <a href="#" id="tombolHapus" class="btn btn-danger px-4 fw-semibold">
                            <i class="bi bi-trash3 me-1"></i>Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-muted small py-3 border-top bg-white">
        &copy; <?= date('Y') ?> Sistem Informasi Data Mahasiswa &mdash; Praktikum Keamanan Sistem Informasi
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('modalHapus').addEventListener('show.bs.modal', function (event) {
            const tombol = event.relatedTarget;
            const nim = tombol.getAttribute('data-nim');
            document.getElementById('namaHapus').textContent = tombol.getAttribute('data-nama');
            document.getElementById('nimHapus').textContent = nim;
            document.getElementById('tombolHapus').href = 'hapus.php?nim=' + encodeURIComponent(nim);
        });

        const inputCari = document.getElementById('inputCari');
        const barisData = document.querySelectorAll('#tabelMahasiswa .baris-data');
        const barisKosong = document.getElementById('barisTidakDitemukan');

        inputCari.addEventListener('input', function () {
            const kataKunci = this.value.toLowerCase().trim();
            let ditemukan = 0;

            barisData.forEach(function (baris) {
                const cocok = baris.textContent.toLowerCase().includes(kataKunci);
                baris.classList.toggle('d-none', !cocok);
                if (cocok) ditemukan++;
            });

            if (barisData.length > 0) {
                barisKosong.classList.toggle('d-none', ditemukan > 0);
            }
        });
    </script>
</body>
</html>
